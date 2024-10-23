<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['admin_login'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ปัญหา</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
<h5>รายการปัญหา</h5>
<?php

$stmt = $conn->prepare("
    SELECT chat.*, 
           CONCAT(users.firstname, ' ', users.lastname) AS complainant 
    FROM chat 
    JOIN users ON chat.send_user = users.id 
    WHERE chat.active_admin = :id AND chat.status = 'ยังไม่แก้ไข'
");
$stmt->bindParam(':id', $user_id, PDO::PARAM_STR);
$stmt->execute();
$show = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<table class="table table-bordered">
<?php if(isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger" role="alert">
    <?php
    echo $_SESSION['error'];
    unset($_SESSION['error']);
    ?>
    </div>
    <?php } ?>


    <?php if(isset($_SESSION['success'])) { ?>
    <div class="alert alert-success" role="alert">
    <?php
    echo $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
    </div>
    <?php } ?>
    <thead class="table-info">
        <th style="width: 120px;">เลชที่คำร้อง</th>
        <th style="width: 300px;">ปัญหา</th>
        <th style="width: 200px;">ผู้ร้องเรียน</th>
        <th style="width: 170px;">เวลา</th>
        <th style="width: 170px;">ดำเนินการแก้ไข</th>
    </thead>
    <tbody>
        <?php foreach ($show as $showA) { ?>
            <?php
$date = $showA['time'];
$formattedDate = date("H:i:s d-m-Y", strtotime($date));
?>


            <tr>
                <td><?php echo htmlspecialchars($showA['id']); ?></td>
                <td><?php echo htmlspecialchars($showA['text']); ?></td>
                <td><?php echo htmlspecialchars($showA['complainant']); ?></td> 
                <td><?php echo $formattedDate; ?></td> 
                <td>

<form action="update_edit_pro.php" method="post">
<input type="hidden" name="id" value="<?php echo htmlspecialchars($showA['id']); ?>">

<button type="submit" name="edit" class="btn btn-warning">ดำเนินการ</button>


        </form>

                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>





</div>
</body>
</html>