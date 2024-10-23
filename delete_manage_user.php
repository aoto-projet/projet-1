<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
    } else {
        $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการลบ";
    }
} else {
    $_SESSION['error'] = "ID ไม่ถูกต้อง";
}
header('Location: admin.php?selectadmin=8');
exit();


?>


if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
    } else {
        $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการลบ";
    }
} else {
    $_SESSION['error'] = "ID ไม่ถูกต้อง";
}
header('Location: admin.php?selectadmin=8');
exit();

