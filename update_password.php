<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];

    if ($password !== $c_password) {
        $_SESSION['error'] = "รหัสผ่านไม่ตรงกัน";
        header("Location: admin.php?selectadmin=8&manageuser=3&id=" . $id);
        exit();
    }

    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $_SESSION['success'] = "แก้ไขรหัสผ่านสำเร็จ";
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    header("Location: admin.php?selectadmin=8");
    exit();
}
?>
