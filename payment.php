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
    // ดึงข้อมูลผู้เช่า
    $stmtUser = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // ดึงข้อมูลบิลที่ยังไม่ชำระ
    $stmtBill = $conn->prepare("SELECT * FROM bill WHERE user_id = :user_id AND status = 'ยังไม่จ่าย'");
    $stmtBill->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtBill->execute();
    $bills = $stmtBill->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .content {
            margin: 20px;
        }
        .bill-box {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="content">
    <h1>การชำระเงิน</h1>

    <?php if ($user): ?>
        <h3>ผู้เช่า: <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h3>

        <?php if (!empty($bills)): ?>
            <h4>บิลที่ยังไม่ชำระ</h4>
            <?php foreach ($bills as $bill): ?>
                <div class="bill-box">
                    <p>หมายเลขบิล: <?= htmlspecialchars($bill['bill_id']) ?></p>
                    <p>จำนวนเงิน: <?= htmlspecialchars($bill['amount']) ?> บาท</p>
                    <p>วันที่ออกบิล: <?= htmlspecialchars($bill['bill_date']) ?></p>
                    <form action="process_payment.php" method="post">
                        <input type="hidden" name="bill_id" value="<?= htmlspecialchars($bill['bill_id']) ?>">
                        <button type="submit" class="btn btn-success">ชำระเงิน</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>คุณไม่มีบิลที่ยังไม่ชำระ</p>
        <?php endif; ?>
    <?php else: ?>
        <p>ไม่พบข้อมูลผู้เช่า</p>
    <?php endif; ?>

    <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
</div>

</body>
</html>
