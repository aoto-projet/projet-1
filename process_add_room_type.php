<?php
session_start();
require_once 'config/db.php';  

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    try {
        $room_type_id = $_POST['room_type_id']; 
        $type_zone = $_POST['type_zone'];
        $room_description = $_POST['room_description']; 
        $monthly_rent = $_POST['monthly_rent'];
        $advance_rent = $_POST['advance_rent'];
        $credit_rent = $_POST['credit_rent'];

        
        $room_image = null;
        if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] == UPLOAD_ERR_OK) {
            $room_image = basename($_FILES['room_image']['name']);
            $target_dir = "uploads/";
            $target_file = $target_dir . $room_image;

            if (!move_uploaded_file($_FILES['room_image']['tmp_name'], $target_file)) {
                throw new Exception("ขออภัย, เกิดข้อผิดพลาดในการอัพโหลดไฟล์ของคุณ.");
            }
        }

       
        $stmt = $conn->prepare("INSERT INTO room_types (room_type_id, type_zone, room_description, monthly_rent, advance_rent,credit_rent, room_image) 
                                VALUES (:room_type_id, :type_zone, :room_description, :monthly_rent, :advance_rent,:credit_rent, :room_image)");
        $stmt->bindParam(':room_type_id', $room_type_id);
        $stmt->bindParam(':type_zone', $type_zone);
        $stmt->bindParam(':room_description', $room_description);
        $stmt->bindParam(':monthly_rent', $monthly_rent);
        $stmt->bindParam(':advance_rent', $advance_rent);
        $stmt->bindParam(':credit_rent', $credit_rent);
        $stmt->bindParam(':room_image', $room_image);

        if ($stmt->execute()) {

            $_SESSION['success'] = "เพิ่มข้อมูลเรียบร้อยแล้ว";
            header('Location: admin.php?selectadmin=7');
            exit();

        } else {
            $_SESSION['error'] = "มีข้อผิดพลาด";
            header('Location: admin.php?selectadmin=7');
            exit();

        }

    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    } catch (Exception $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}
?>
