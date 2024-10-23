<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $upstatus = $_POST['upstatus'];

    try {
        $stmt = $conn->prepare("UPDATE bill SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $upstatus, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute(); 

        $_SESSION['success'] = "จ่ายเงินแล้ว";
        header('Location: admin.php?selectadmin=13');
        exit(); 

    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
        $_SESSION['error'] = "ดำเนินการไม่สำเร็จ";
        header('Location: admin.php?selectadmin=13');
        exit(); 
    }
}
?>
