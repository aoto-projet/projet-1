<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_login']; 

try {
    // ดึงข้อมูลห้องพักของผู้เช่า
    $stmtRoom = $conn->prepare("SELECT * FROM room WHERE user_id = :user_id");
    $stmtRoom->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtRoom->execute();
    $room = $stmtRoom->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "คุณยังไม่มีห้องพัก";
    } else {
        // แสดงข้อมูลห้องพัก
        echo "<h3>ข้อมูลห้องพักของคุณ</h3>";
        echo "<p>หมายเลขห้อง: " . htmlspecialchars($room['room_number']) . "</p>";
        echo "<p>โซน: " . htmlspecialchars($room['room_zone']) . "</p>";
        echo "<p>สถานะ: " . ($room['room_status'] == 'available' ? 'ว่าง' : 'ไม่ว่าง') . "</p>";
        // เพิ่มข้อมูลเพิ่มเติมตามต้องการ
    }
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้เช่า</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .content {
            margin: 20px;
        }
        .room-box {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="content">
    <h1>ข้อมูลผู้เช่า</h1>

    <?php if ($user): ?>
        <h3>ชื่อผู้เช่า: <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h3>
        <h4>ห้องที่เช่า</h4>

        <?php if ($room): ?>
            <div class="room-box">
                <p>หมายเลขห้อง: <?= htmlspecialchars($room['room_number']) ?></p>
                <p>โซน: <?= htmlspecialchars($room['room_zone']) ?></p>
                <p>สถานะ: <?= $room['room_status'] == 'available' ? 'ว่าง' : 'ไม่ว่าง' ?></p>
            </div>
        <?php else: ?>
            <p>คุณยังไม่มีห้องเช่า</p>
        <?php endif; ?>
    <?php else: ?>
        <p>ไม่พบข้อมูลผู้เช่า</p>
    <?php endif; ?>
    
    <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
</div>

</body>
</html>
