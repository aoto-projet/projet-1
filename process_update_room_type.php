<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_type_id = $_POST['room_type_id'];
    $type_zone = $_POST['type_zone'];
    $room_description = $_POST['room_description'];
    $monthly_rent = $_POST['monthly_rent'];
    $advance_rent = $_POST['advance_rent'];
    $credit_rent = $_POST['credit_rent'];
    $room_image = $_FILES['room_image']['name'] ?? '';

    try {
        if (!empty($room_image)) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($room_image);

            
            if (move_uploaded_file($_FILES['room_image']['tmp_name'], $target_file)) {
               
                $stmt = $conn->prepare("SELECT room_image FROM room_types WHERE room_type_id = :room_type_id");
                $stmt->bindParam(':room_type_id', $room_type_id, PDO::PARAM_INT);
                $stmt->execute();
                $old_image = $stmt->fetchColumn();

                if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                    unlink($target_dir . $old_image);
                }

                $stmt = $conn->prepare("UPDATE room_types SET room_image = :room_image WHERE room_type_id = :room_type_id");
                $stmt->bindParam(':room_image', $room_image, PDO::PARAM_STR);
                $stmt->bindParam(':room_type_id', $room_type_id, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                throw new Exception("การอัปโหลดรูปภาพล้มเหลว");
            }
        }

        
        $stmt = $conn->prepare("UPDATE room_types SET type_zone = :type_zone, room_description = :room_description, monthly_rent = :monthly_rent, advance_rent = :advance_rent, credit_rent = :credit_rent WHERE room_type_id = :room_type_id");
        $stmt->bindParam(':type_zone', $type_zone, PDO::PARAM_STR);
        $stmt->bindParam(':room_description', $room_description, PDO::PARAM_STR);
        $stmt->bindParam(':monthly_rent', $monthly_rent, PDO::PARAM_INT);
        $stmt->bindParam(':advance_rent', $advance_rent, PDO::PARAM_INT);
        $stmt->bindParam(':credit_rent', $credit_rent, PDO::PARAM_INT);
        $stmt->bindParam(':room_type_id', $room_type_id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success'] = "แก้ไขข้อมูลเรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=7');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: admin.php?selectadmin=7&roomtype=2&id=' . urlencode($room_type_id));
        exit();
    }
} else {
    $_SESSION['error'] = "ไม่สามารถแก้ไขข้อมูลได้";
    header('Location: admin.php?selectadmin=7');
    exit();
}
