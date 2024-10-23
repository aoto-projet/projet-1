<?php
session_start();
require_once 'config/db.php'; 

if (isset($_GET['id']) && isset($_GET['room_type_id'])) {
    $image = $_GET['id'];
    $room_type_id = $_GET['room_type_id'];

    try {
     
        if (empty($image) || empty($room_type_id)) {
            throw new Exception("Invalid request.");
        }

        
        $stmt = $conn->prepare("SELECT gallery_images FROM room_types WHERE room_type_id = :room_type_id");
        $stmt->bindParam(':room_type_id', $room_type_id, PDO::PARAM_INT);
        $stmt->execute();
        $roomtype_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($roomtype_data) {
            $gallery_images = explode(',', $roomtype_data['gallery_images']);

          
            $gallery_images = array_filter($gallery_images, function($img) use ($image) {
                return $img !== $image;
            });

          
            $new_gallery_images = implode(',', $gallery_images);
            $stmt = $conn->prepare("UPDATE room_types SET gallery_images = :gallery_images WHERE room_type_id = :room_type_id");
            $stmt->bindParam(':gallery_images', $new_gallery_images, PDO::PARAM_STR);
            $stmt->bindParam(':room_type_id', $room_type_id, PDO::PARAM_INT);
            $stmt->execute();

         
            $file_path = 'uploads/' . $image;
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $_SESSION['success'] = "ลบภาพเรียบร้อยแล้ว";
            header('Location: admin.php?selectadmin=7&roomtype=3&id=' . urlencode($room_type_id));
            exit();
        } else {
            throw new Exception("Room type not found.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header('Location: admin.php?selectadmin=3&id=' . urlencode($room_type_id));
    exit;
} else {
    $_SESSION['error'] = "ข้อมูลไม่ครบถ้วน";
    header('Location: admin.php?selectadmin=3');
    exit;
}
?>
