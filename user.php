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

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
try {
    $stmtRooms = $conn->prepare("SELECT * FROM room");
    $stmtRooms->execute();
    $rooms = $stmtRooms->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูลห้อง: " . $e->getMessage();
}
try {
    $stmtUserInfo = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmtUserInfo->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtUserInfo->execute();
    $userInfo = $stmtUserInfo->fetch(PDO::FETCH_ASSOC);

    $stmtPayments = $conn->prepare("SELECT * FROM payment_history WHERE user_id = :user_id");
    $stmtPayments->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtPayments->execute();
    $paymentHistory = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage();
}
try {
    $stmtUserInfo = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmtUserInfo->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtUserInfo->execute();
    $userInfo = $stmtUserInfo->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบและตั้งค่ารูปโปรไฟล์
    if (empty($userInfo['profile_picture'])) {
        $profilePicture = 'path/to/default/profile_picture.jpg'; // รูปโปรไฟล์เริ่มต้น
    } else {
        $profilePicture = htmlspecialchars($userInfo['profile_picture']);
    }

    $stmtPayments = $conn->prepare("SELECT * FROM payment_history WHERE user_id = :user_id");
    $stmtPayments->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtPayments->execute();
    $paymentHistory = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage();
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script> 
<style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .footer {
            padding: 10px;
            background-color: #343a40;
            color: white;
            text-align: center;
        }
     
        .room-box {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 5px; 
            font-weight: bold;
        }
        .available {
            background-color: #28a745; 
        }
        .occupied {
            background-color: #dc3545; 
            color: white;
        }
  body {
            background-color: #f8f9fa;
        }
        /* ... (สไตล์อื่นๆ) ... */
    </style>
</head>
<body>

<?php
$stmt = $conn->prepare("SELECT * FROM chat WHERE active_user = :active_user");
$stmt->bindParam(':active_user', $user_id, PDO::PARAM_INT);
$stmt->execute();
$chat = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = count($chat); 
?>







    <div class="container">
   
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="img/logo.png" width="100" height="80" alt="">ระบบหอพักหลิงหลิง</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="user.php">หน้าหลัก</a>
                    </li>
					<li class="nav-item">
    <a class="nav-link" href="#">ยินดีต้อนรับ, <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></a>
</li>

                    <li class="nav-item">
                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#settingsModal2">รายงานปัญหา</a>


                    <div class="modal fade" id="settingsModal2" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="settingsModalLabel">รายงานปัญหา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      
                        <form action="problem.php" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                        
                            <div class="mb-3">
                                <label for="problem" class="form-label">ปัญหา</label>
                                <textarea name="problem" class="form-control" id="problem"></textarea>
                            </div>
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM users WHERE urole = 'admin'");
                                $stmt->execute();
                                $showuser = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>

                        <div class="mb-3">
                                <label for="admin_id" class="form-label">รายงานไปยัง</label>


                     
                                <select name="admin_id"  class="form-control"  id="admin_id">

                             <option value="">เลือกผู้รับรางาน</option>
                             <?php foreach($showuser as $userA){?>

                      <option value="<?php echo $userA['id'];?>"><?php echo $userA['firstname'].' '.$userA['lastname'];?></option>
                     <?php } ?>

                            </select>

                 </div>
                            
                            <button type="submit" name="send"class="btn btn-primary">ส่ง</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                      
                    </div>
                </div>
            </div>
        </div>
 </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user.php?showuser=1">จ่ายบิล&nbsp;<span class="badge bg-success"><?php echo $count;?></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ออกจากระบบ</a>
                    </li>




                </ul>
            </div>
        </div>
    </nav>

    <?php
    $showuser = $_GET['showuser'];
    if($showuser == ""){

?>
 
        <h3 class="mt-2">รายการห้องพัก</h3>
    <br>
        <div class="container">
    <h3>ข้อมูลของผู้เช่า</h3>
    <div class="text-center mb-4">
        <img src="<?php echo isset($profilePicture) ? $profilePicture : 'path/to/default/profile_picture.jpg'; ?>" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
    </div>
    <ul class="list-group">
        <li class="list-group-item">ชื่อ: <?php echo htmlspecialchars($userInfo['firstname'] . ' ' . $userInfo['lastname']); ?></li>
        <li class="list-group-item">ห้อง: <?php echo htmlspecialchars($userInfo['room_number']); ?></li>
        <li class="list-group-item">อีเมล: <?php echo htmlspecialchars($userInfo['email']); ?></li>
    </ul>

    <h3 class="mt-4">ประวัติการชำระเงิน</h3>
    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>บิลเลขที่</th>
                <th>จำนวนเงิน (บาท)</th>
                <th>วันที่ชำระ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paymentHistory as $payment) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($payment['bill_id']); ?></td>
                    <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                    <td><?php echo date("d-m-Y", strtotime($payment['payment_date'])); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



<?php
    }
    ?>
<?php
if($showuser == "1"){
    include("user_pay.php");
}
    ?>





<br>




 
    <div class="footer">
        <p c>By Ment Autotech</p>
    </div>
    </div>
</body>
</html>
