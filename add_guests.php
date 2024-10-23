<?php
session_start();
require_once 'config/db.php';

if(!isset($_SESSION['admin_login'])){
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: signin.php");
    exit();
}

if(isset($_POST['save'])){
    $room_number = $_POST["room_number"];
    $national_id = $_POST["national_id"];
    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];
    $contact = $_POST["contact"];
    $day = $_POST["day"];

    try{
      
        $stmt = $conn->prepare("INSERT INTO guests (room_number, national_id, name, phone_number, emergency_contact, check_in_date)
        VALUES (:room_number, :national_id, :name, :phone_number, :emergency_contact, :check_in_date);");

        $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        $stmt->bindParam(':national_id', $national_id, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':emergency_contact', $contact, PDO::PARAM_STR);
        $stmt->bindParam(':check_in_date', $day, PDO::PARAM_STR);
        $stmt->execute();

       
        $stmt = $conn->prepare("UPDATE room SET room_status = 'occupied' WHERE room_number = :room_number");
        $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = "เพิ่มข้อมูลสำเร็จและเปลี่ยนสถานะห้องเป็น 'occupied'";
        header('Location: admin.php?selectadmin=9');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=9');
        exit();
    }
}


?>
