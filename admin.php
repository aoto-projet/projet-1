<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['admin_login'];

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #fff;
        }
        .hoverable {
            transition: transform 0.2s;
        }
        .hoverable:hover {
            transform: scale(1.05);
        }
        .bgstyle {
            background-color: #28a745;
            transition: background-color 0.3s;
        }
        .bgstyle:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a href="#" class="navbar-brand">ระบบจัดการหอพักหลิงหลิง</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarToggle">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="admin.php?selectadmin=14" class="nav-link">หน้าหลัก</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">เมนู</a>
                    <ul class="dropdown-menu">
                        <li><a href="admin.php?selectadmin=14" class="dropdown-item">หน้าหลัก/ออกบิล</a></li>
                        <li><a href="admin.php?selectadmin=13" class="dropdown-item">รายการรอชำระเงิน</a></li>
                        <li><a href="admin.php?selectadmin=12" class="dropdown-item">รายการชำระเงินแล้ว</a></li>
                        <li><a href="admin.php?selectadmin=11" class="dropdown-item">รอย้ายออก</a></li>
                        <li><a href="admin.php?selectadmin=10" class="dropdown-item">ย้ายออกแล้ว</a></li>
                        <li><a href="admin.php?selectadmin=9" class="dropdown-item">จัดการข้อมูลผู้เข้าพัก</a></li>
                        <li><a href="admin.php?selectadmin=8" class="dropdown-item">จัดการข้อมูลเจ้าของหอพัก/แอดมิน</a></li>
                        <li><a href="admin.php?selectadmin=7" class="dropdown-item">จัดการข้อมูลประเภทห้องพัก</a></li>
                        <li><a href="admin.php?selectadmin=6" class="dropdown-item">จัดการห้องพัก</a></li>
                        <li><a href="admin.php?selectadmin=5" class="dropdown-item">จัดการเรทค่าน้ำค่าไฟ</a></li>
                        <li><a href="logout.php" class="dropdown-item">ออกจากระบบ</a></li>
                    </ul>
                </li>

                <?php
                $stmt = $conn->prepare("SELECT * FROM chat WHERE active_admin = :active_admin AND status = 'ยังไม่แก้ไข'");
                $stmt->bindParam(':active_admin', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $chat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $countA = count($chat);
                ?>

                <li class="nav-item">
                    <a href="admin.php?selectadmin=15" class="nav-link">ปัญหา&nbsp;<span class="badge bg-success"><?php echo $countA;?></span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 sidebar">
            <?php
    // ตรวจสอบว่ามีเส้นทางรูปโปรไฟล์ในฐานข้อมูลหรือไม่
    $profilePicture = !empty($row['profile_picture']) ? './uploads/' . htmlspecialchars($row['profile_picture']) : 'default_profile.jpg';
    ?>
    <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-img">
    <p>สวัสดีคุณ <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></p>
            <div class="bgstyle hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=14" class="text-decoration-none text-light w-100 h-100 text-start">
                    <i class="fas fa-home" style="font-size: 24px;"></i> หน้าหลัก/ออกบิล
                </a>
            </div>

            <?php
            $stmt = $conn->prepare("SELECT COUNT(id) AS count_unpaid FROM bill WHERE status = :status");
            $status = 'ยังไม่จ่าย';
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $count_unpaid = $result['count_unpaid'];
            ?>

            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=13" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-dollar-sign"></i> รายการรอชำระเงิน<span class="badge bg-success"><?php echo $count_unpaid;?></span>
                </a>
            </div>

            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=12" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-check-circle"></i> รายการชำระเงินแล้ว
                </a>
            </div>
            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=11" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-exclamation-circle"></i> รอย้ายออก
                </a>
            </div>
            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=10" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-door-open"></i> ย้ายออกแล้ว
                </a>
            </div>
            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=9" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-users"></i> จัดการข้อมูลผู้เข้าพัก
                </a>
            </div>
            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=8" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-user-shield"></i> จัดการข้อมูลเจ้าของหอพัก/แอดมิน
                </a>
            </div>
            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=7" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-bed"></i> จัดการข้อมูลประเภทห้องพัก
                </a>
            </div>
            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=6" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-key"></i> จัดการห้องพัก
                </a>
            </div>
            <div class="bg-light hoverable p-2 mb-2">
                <a href="admin.php?selectadmin=5" class="text-decoration-none text-dark w-100 text-start">
                    <i class="fas fa-water"></i> จัดการเรทค่าน้ำค่าไฟ
                </a>
            </div>
            <div class="bgred hoverable p-2">
                <a href="logout.php" class="text-decoration-none text-danger w-100 text-start">
                    <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                </a>
            </div>
        </div>

        <div class="col-md-9 content">
            <?php 
            $selectadmin = $_GET['selectadmin'] ?? '';
            if ($selectadmin == "") {
                // Default content or message
            }
            ?>     
            <?php
            $pageMap = [
                "1" => "room.php",
                "2" => "e_bill.php",
                "3" => "room_form.php",
                "4" => "add_room.php",
                "5" => "rate.php",
                "6" => "room_manage.php",
                "7" => "roomtype.php",
                "8" => "manage_adminuser.php",
                "9" => "manage_guests.php",
                "10" => "getout.php",
                "11" => "wait_getout.php",
                "12" => "success_bill.php",
                "13" => "wait_bill.php",
                "14" => "main.php",
                "15" => "edit_pro.php",
            ];
            if (array_key_exists($selectadmin, $pageMap)) {
                include($pageMap[$selectadmin]);
            }
            ?>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
