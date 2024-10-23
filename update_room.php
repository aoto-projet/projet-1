<?php
session_start();
require_once 'config/db.php';

if(!isset($_SESSION['admin_login'])){
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location; signin.php');
    exit();
}
if(isset($_POST['update'])){
    $room_id = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $room_zone = $_POST['room_zone'];
    $room_status = $_POST['room_status'];
if(empty($room_number)||empty($room_zone)||empty($room_status) ){
    $_SESSION['error'] = "ข้อมูลไม่คบถ้วน";
    header('Location:  admin.php?selectadmin=6&room=2&id=' . urlencode($id));
    exit();
}
try {

    $stmt = $conn->prepare("UPDATE room SET 
    room_number = :room_number,
    room_zone = :room_zone,
    room_status = :room_status
    WHERE room_id = :room_id");
     $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
     $stmt->bindParam(':room_zone', $room_zone, PDO::PARAM_STR);
     $stmt->bindParam(':room_status', $room_status, PDO::PARAM_STR);
     $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
     $stmt->execute();
     $_SESSION['success'] = "อัปเดตข้อมูลเรียบร้อยแล้ว";
     header('Location: admin.php?selectadmin=6');
     exit();
}   catch (PDOException $e) {
    $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    header('Location: admin.php?selectadmin=6&room=2&id=' . urlencode($id));
    exit();
}
} else {
    $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการอัปเดต";
    header('Location: admin.php?selectadmin=6&room=2&id=' . urlencode($id));
    exit();
}

?>


