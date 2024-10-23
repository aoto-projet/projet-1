<?php
session_start();
require_once 'config/db.php';

if(!isset($_SESSION['admin_login'])){
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location; signin.php');
    exit();
}if(isset($_POST['update'])){
    $id = $_POST['id'];
    $room_number = $_POST['room_number'];
    $national_id = $_POST['national_id']; 
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $contact = $_POST['contact'];
    $day = $_POST['day'];

    try {
        $stmt = $conn->prepare("UPDATE guests SET
            room_number = :room_number,
            national_id = :national_id,
            name = :name,
            phone_number = :phone_number,
            emergency_contact = :emergency_contact,
            check_in_date = :check_in_date
            WHERE guests_id = :guests_id");

        $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        $stmt->bindParam(':national_id', $national_id, PDO::PARAM_STR); 
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':emergency_contact', $contact, PDO::PARAM_STR);
        $stmt->bindParam(':check_in_date', $day, PDO::PARAM_STR);
        $stmt->bindParam(':guests_id', $id, PDO::PARAM_INT);
        
        $stmt->execute();

        $_SESSION['success'] = "อัปเดตข้อมูลเรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=9');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=9&guests=2&id=' . urlencode($id));
        exit();
    }
}

?>