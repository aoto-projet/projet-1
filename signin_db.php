<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมล';
        header("Location: signin.php");
        exit();

    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        header("Location: signin.php");
        exit();

    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        header("Location: signin.php");
        exit();

    } else if (strlen($password) > 18 || strlen($password) < 6) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 6 ถึง 18 ตัวอักษร';
        header("Location: signin.php");
        exit();

    } else { 
        try {
            $check_data = $conn->prepare("SELECT id, email, password, urole FROM users WHERE email = :email");
            $check_data->bindParam(":email", $email);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if (password_verify($password, $row['password'])) {
                    if ($row['urole'] == 'admin') {
                        $_SESSION['admin_login'] = $row['id'];
                        header("Location: admin.php?selectadmin=14");
                    } else {
                        $_SESSION['user_login'] = $row['id'];
                        header("Location: user.php");
                    }
                    exit();
                } else {
                    $_SESSION['error'] = 'รหัสผ่านผิด';
                    header("Location: signin.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = 'ไม่มีข้อมูลในระบบ';
                header("Location: signin.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            header("Location: signin.php");
            exit();
        }
    }
}
?>
