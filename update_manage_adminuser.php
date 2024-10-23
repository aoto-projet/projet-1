<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateuser'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $urole = isset($_POST['urole']) ? $_POST['urole'] : '';

    if (empty($id)) {
        $_SESSION['error'] = "ID ไม่ถูกส่งมา";
        header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
        exit();
    } else if (empty($firstname)) {
        $_SESSION['error'] = "กรุณากรอกชื่อ";
        header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
        exit();
    } else if (empty($lastname)) {
        $_SESSION['error'] = "กรุณากรอกนามสกุล";
        header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
        exit();
    } else if (empty($email)) {
        $_SESSION['error'] = "กรุณากรอกอีเมล";
        header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "รูปแบบอีเมลไม่ถูกต้อง";
        header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
        exit();
    } else if (empty($urole)) {
        $_SESSION['error'] = "โปรดเลือกสถานะ";
        header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
        exit();
    } else {
        try {
           
            $check_email = $conn->prepare("SELECT email FROM users WHERE email = :email AND id != :id");
            $check_email->bindParam(":email", $email);
            $check_email->bindParam(":id", $id, PDO::PARAM_INT);
            $check_email->execute();
            $row = $check_email->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $_SESSION['error'] = "มีอีเมลในระบบแล้ว";
                header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
                exit();
            } else {
                
                $stmt = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, urole = :urole WHERE id = :id");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":urole", $urole);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ";
                header("Location: admin.php?selectadmin=8");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
            header("Location: admin.php?selectadmin=8&manageuser=2&id=" . $id);
            exit();
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $urole = $_POST['urole'];

    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture'];

        // เช็คชนิดไฟล์และขนาด
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($profile_picture['type'], $allowed_types) && $profile_picture['size'] <= 2000000) {
            // สร้างชื่อไฟล์ใหม่
            $file_name = uniqid() . '_' . basename($profile_picture['name']);
            $upload_path = 'uploads/' . $file_name;

            // อัปโหลดไฟล์
            if (move_uploaded_file($profile_picture['tmp_name'], $upload_path)) {
                // อัปเดตข้อมูลในฐานข้อมูล
                $stmt = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, urole = :urole, profile_picture = :profile_picture WHERE id = :id");
                
                // กำหนดค่าพารามิเตอร์
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':urole', $urole);
                $stmt->bindParam(':profile_picture', $file_name);
                $stmt->bindParam(':id', $id);
                
                // ทำการอัปเดต
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'อัปเดตข้อมูลสมาชิกสำเร็จ';
                } else {
                    $_SESSION['error'] = 'ไม่สามารถอัปเดตข้อมูลได้';
                }
            } else {
                $_SESSION['error'] = 'ไม่สามารถอัปโหลดไฟล์ได้';
            }
        } else {
            $_SESSION['error'] = 'ชนิดไฟล์หรือขนาดไฟล์ไม่ถูกต้อง';
        }
    } else {
        // ถ้าไม่อัปโหลดไฟล์ ก็อัปเดตข้อมูลโดยไม่เปลี่ยนโปรไฟล์ภาพ
        $stmt = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, urole = :urole WHERE id = :id");
        
        // กำหนดค่าพารามิเตอร์
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':urole', $urole);
        $stmt->bindParam(':id', $id);
        
        // ทำการอัปเดต
        if ($stmt->execute()) {
            $_SESSION['success'] = 'อัปเดตข้อมูลสมาชิกสำเร็จ';
        } else {
            $_SESSION['error'] = 'ไม่สามารถอัปเดตข้อมูลได้';
        }
    }
    
    header('Location: admin.php?selectadmin=8');
    exit;
}

if ($_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = $_FILES['profile_picture']['name'];
    $fileType = $_FILES['profile_picture']['type'];

    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($fileType, $allowedFileTypes)) {
        $newFileName = uniqid() . '_' . $fileName;
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // บันทึกชื่อไฟล์ลงฐานข้อมูล
            $stmt = $conn->prepare("UPDATE users SET profile_picture = :profile_picture WHERE id = :id");
            $stmt->bindParam(':profile_picture', $newFileName);
            // ... บันทึกข้อมูลอื่น ๆ ตามปกติ
        }
    } else {
        echo "ประเภทไฟล์ไม่ถูกต้อง";
    }
}




?>
