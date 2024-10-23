<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

   
    $stmt = $conn->prepare("SELECT room_image FROM room_types WHERE room_type_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $roomtype_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($roomtype_data) {
       
        if (!empty($roomtype_data['room_image'])) {
            $image_path = 'uploads/' . $roomtype_data['room_image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

       
        $stmt = $conn->prepare("DELETE FROM room_types WHERE room_type_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
            header('Location: admin.php?selectadmin=7');
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลบข้อมูล";
        }
    } else {
        echo "ไม่พบข้อมูลประเภทห้องที่ต้องการลบ";
    }
} else {
    echo "ไม่พบ ID ที่ต้องการลบ";
}
?>
