<?php
session_start();
require_once 'config/db.php';  

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

if (isset($_FILES['gallery_images']) && isset($_POST['room_type_id'])) {
    $gallery_images = [];
    $room_type_id = $_POST['room_type_id']; 
    

    $stmt = $conn->prepare("SELECT gallery_images FROM room_types WHERE room_type_id = :id");
    $stmt->bindParam(':id', $room_type_id, PDO::PARAM_INT);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    $existing_images = explode(',', $room['gallery_images']);

    foreach ($_FILES['gallery_images']['name'] as $key => $name) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($name);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

       
        if(move_uploaded_file($_FILES['gallery_images']['tmp_name'][$key], $target_file)) {
            $gallery_images[] = $name;  
        }
    }

    
    $all_images = array_merge($existing_images, $gallery_images);
    $gallery_images_str = implode(',', $all_images);

    
    $stmt = $conn->prepare("UPDATE room_types SET gallery_images = :gallery_images WHERE room_type_id = :id");
    $stmt->bindParam(':gallery_images', $gallery_images_str, PDO::PARAM_STR);
    $stmt->bindParam(':id', $room_type_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success'] = "เพิ่มรูปภาพเรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=7&roomtype=3&id=' . $room_type_id);
        exit();
    } else {
        $_SESSION['error'] = "มีข้อผิดพลาด";
        header('Location: admin.php?selectadmin=7&roomtype=3&id=' . $room_type_id);
        exit();
    }
}


?>