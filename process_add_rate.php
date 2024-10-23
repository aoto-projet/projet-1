<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addrate'])) {
    $water_rate = $_POST['w_rate'];
    $electricity_rate = $_POST['e_rate'];
    $effectivedate = $_POST['effectivedate'];
    $user_id = $_SESSION['admin_login'];

    try {
     
        $stmt = $conn->prepare("INSERT INTO rate (water_rate, electricity_rate, effective_date) 
        VALUES (:water_rate, :electricity_rate, :effective_date) 
        ON DUPLICATE KEY UPDATE water_rate = VALUES(water_rate), electricity_rate = VALUES(electricity_rate)");
        $stmt->bindParam(':water_rate', $water_rate, PDO::PARAM_STR);
        $stmt->bindParam(':electricity_rate', $electricity_rate, PDO::PARAM_STR);
        $stmt->bindParam(':effective_date', $effectivedate, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = "เพิ่มข้อมูลเรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=5');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=5');
        exit();
    }
}
?>
