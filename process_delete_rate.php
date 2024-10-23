<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
 
        $stmt = $conn->prepare("DELETE FROM rate WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
       
        $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=5');
        exit();
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
} else {
    echo "ข้อมูลไม่ครบถ้วนหรือไม่ถูกต้อง";
}
?>

