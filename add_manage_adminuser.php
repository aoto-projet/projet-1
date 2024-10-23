<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $c_password = trim($_POST['c_password']);
    $status = trim($_POST['status']);

    try {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $check_email->bindParam(":email", $email);
        $check_email->execute();
        $row = $check_email->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $_SESSION['error'] = "มีอีเมลในระบบแล้ว";
            header("Location: admin.php?selectadmin=8&manageuser=1");
            exit();
        }

        // Validate password
        if ($password != $c_password) {
            $_SESSION['error'] = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
            header('Location: admin.php?selectadmin=8&manageuser=1');
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare to insert user data
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, urole) VALUES (:firstname, :lastname, :email, :password, :urole)");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':urole', $status);

        // Execute insert
        $stmt->execute();

        // Get the last inserted user ID
        $lastUserId = $conn->lastInsertId();

        // Check if a profile picture is uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $fileName = $_FILES['profile_picture']['name'];
            $fileType = $_FILES['profile_picture']['type'];

            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($fileType, $allowedFileTypes)) {
                $newFileName = uniqid() . '_' . $fileName;
                $uploadFileDir = './uploads/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Update the profile picture in the database
                    $stmt = $conn->prepare("UPDATE users SET profile_picture = :profile_picture WHERE id = :user_id");
                    $stmt->bindParam(':profile_picture', $newFileName);
                    $stmt->bindParam(':user_id', $lastUserId);
                    $stmt->execute();
                } else {
                    echo "ไม่สามารถอัปโหลดไฟล์ได้";
                }
            } else {
                echo "ประเภทไฟล์ไม่ถูกต้อง";
            }
        }

        $_SESSION['success'] = "เพิ่มข้อมูลสมาชิกสำเร็จ";
        header('Location: admin.php?selectadmin=8');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=8');
        exit();
    }
}
?>
