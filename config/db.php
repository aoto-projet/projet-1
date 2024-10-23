<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$database = "room_lingling";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // ตั้งค่า PDO error mode เป็น exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "เชื่อมต่อฐานข้อมูลสำเร็จ";
} catch(PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
}
?>
