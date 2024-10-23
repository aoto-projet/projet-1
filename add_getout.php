<?php
session_start();
require_once 'config/db.php';

if(!isset($_SESSION['admin_login'])){
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: sigin.php");
    exit();
}

if(isset($_POST['getout'])){
    $id = $_POST['id'];
    $room_number = $_POST["room_number"];
    $national_id = $_POST["national_id"];
    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];
    $contact = $_POST["contact"];
    $day = $_POST["day"];

    try {
       
        $stmt = $conn->prepare("INSERT INTO getout (id, room_number, national_id, name, phone_number, emergency_contact, day)
        VALUES (:id, :room_number, :national_id, :name, :phone_number, :emergency_contact, :day);");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        $stmt->bindParam(':national_id', $national_id, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':emergency_contact', $contact, PDO::PARAM_STR);
        $stmt->bindParam(':day', $day, PDO::PARAM_STR);
        $stmt->execute();

    
        $deleteStmt = $conn->prepare("DELETE FROM guests WHERE guests_id = :id");
        $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $deleteStmt->execute();

        $_SESSION['success'] = "ย้ายข้อมูลสำเร็จและลบข้อมูลออกจาก guests เรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=11');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=11');
        exit();
    }
}
?>
