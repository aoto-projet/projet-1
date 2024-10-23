<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}


if (isset($_POST["addroom"])) {
    
  
    $room_number = $_POST['room_number'];
    $type_zone = $_POST['type_zone'];
    $room_status = $_POST['room_status'];

    
    if (!empty($room_number) && !empty($type_zone) && !empty($room_status)) {
        try {
            
            $stmt = $conn->prepare("INSERT INTO room (room_number, room_zone, room_status) VALUES (:room_number, :room_zone, :room_status)");
           
            $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
            $stmt->bindParam(':room_zone', $type_zone, PDO::PARAM_STR);
            $stmt->bindParam(':room_status', $room_status, PDO::PARAM_STR);

           
            if ($stmt->execute()) {
        
                $_SESSION['success'] = "เพิ่มข้อมูลห้องพักเรียบร้อยแล้ว";
                header("Location: admin.php?selectadmin=6");
            } else {
                $_SESSION['error'] = "เกิดข้อผิดพลาดในการเพิ่มข้อมูล";
                header("Location: admin.php?selectadmin=6&room=1");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
            header("Location: admin.php?selectadmin=6&room=1");
        }
    } else {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("Location: admin.php?selectadmin=6&room=1");
    }

    
    header('Location: admin.php?selectadmin=6');
    exit();
} else {
    $_SESSION['error'] = "ข้อมูลไม่ถูกต้อง";
    header('Location: admin.php?selectadmin=6&room=1');
    exit();
}


// หลังจากบันทึกข้อมูลห้องใหม่
if ($stmt->execute()) {
    // เปลี่ยนสถานะห้องเป็นไม่ว่าง
    $updateStatus = $conn->prepare("UPDATE room SET room_status = 'occupied' WHERE room_number = :room_number");
    $updateStatus->bindParam(':room_number', $newRoomNumber); // $newRoomNumber คือหมายเลขห้องที่เพิ่ม
    $updateStatus->execute();
    
    $_SESSION['success'] = "เพิ่มข้อมูลห้องพักเรียบร้อยแล้ว";
    header('Location: admin.php?selectadmin=6');
} else {
    $_SESSION['error'] = "เกิดข้อผิดพลาดในการเพิ่มข้อมูล";
}

?>
