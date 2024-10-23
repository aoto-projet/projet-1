<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $w_rate = isset($_POST['w_rate']) ? $_POST['w_rate'] : '';
    $e_rate = isset($_POST['e_rate']) ? $_POST['e_rate'] : '';
    $effectiveDate = isset($_POST['effectivedate']) ? $_POST['effectivedate'] : '';

    if (empty($id) || empty($w_rate) || empty($e_rate) || empty($effectiveDate)) {
        $_SESSION['error'] = "ข้อมูลไม่ครบถ้วน";
        header('Location: admin.php?selectadmin=5&rate=2&id=' . urlencode($id));
        exit();
    }

    try {
       
        $stmt = $conn->prepare("UPDATE rate SET 
                                water_rate = :w_rate,
                                electricity_rate = :e_rate,
                                effective_date = :effectiveDate
                                WHERE id = :id");
        $stmt->bindParam(':w_rate', $w_rate, PDO::PARAM_STR);
        $stmt->bindParam(':e_rate', $e_rate, PDO::PARAM_STR);
        $stmt->bindParam(':effectiveDate', $effectiveDate, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success'] = "อัปเดตข้อมูลเรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=5');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=5&rate=2&id=' . urlencode($id));
        exit();
    }
} else {
    $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการอัปเดต";
    header('Location: admin.php?selectadmin=5');
    exit();
}

?>
