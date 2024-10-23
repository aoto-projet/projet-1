<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['signup'])) {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];

   
    if (empty($firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("Location: signup.php");
        exit();
    } else if (empty($lastname)) {
        $_SESSION['error'] = 'กรุณากรอกนามสกุล';
        header("Location: signup.php");
        exit();
    } else if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมล';
        header("Location: signup.php");
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        header("Location: signup.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        header("Location: signup.php");
        exit();
    } else if (strlen($password) > 18 || strlen($password) < 6) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 6 ถึง 18 ตัวอักษร';
        header("Location: signup.php");
        exit();
    } else if (empty($c_password)) {
        $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
        header("Location: signup.php");
        exit();
    } else if ($password != $c_password) {
        $_SESSION['error'] = 'ยืนยันรหัสผ่านไม่ถูกต้อง';
        header("Location: signup.php");
        exit();
    } else { 
        try {
            
            $check_email = $conn->prepare("SELECT email FROM users WHERE email = :email");
            $check_email->bindParam(":email", $email);
            $check_email->execute();
            $row = $check_email->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $_SESSION['error'] = "มีอีเมลในระบบแล้ว <a href='signin.php'>คลิ๊กที่นี่</a> เพื่อเข้าสู่ระบบ";
                header("Location: signup.php");
                exit();
            } else {
               
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->execute();
                $_SESSION['success'] = "สมัครสมาชิกเรียบร้อยแล้ว! <a href='signin.php' class='alert-link'>คลิ๊กที่นี่</a> เพื่อเข้าสู่ระบบ";
                header("Location: signup.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล';
            header("Location: signup.php");
            exit();
        }
    }
}
?>
