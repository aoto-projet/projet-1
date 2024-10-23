<?php
session_start();
require_once 'config/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
        $id = $_POST['id']; 
        $file = $_FILES['pic'];

        
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/sleep/'; 
            $uploadFile = $uploadDir . basename($file['name']);
            
           
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $filename = $file['name'];

                
                $status = "จ่ายแล้ว";

                
                $stmt = $conn->prepare("UPDATE bill SET sleep = :sleep, status = :status WHERE id = :id");
                $stmt->bindValue(':sleep', $filename);
                $stmt->bindValue(':status', $status);
                $stmt->bindValue(':id', $id);
                
                
                if ($stmt->execute()) {
                   
                    $deleteStmt = $conn->prepare("DELETE FROM chat WHERE active_bill = :id");
                    $deleteStmt->bindValue(':id', $id);
                    $deleteStmt->execute();

                    $_SESSION['success'] = "คำร้องขอของคุณสำเร็จแล้ว";
                    header('Location: user.php?showuser=1');
                    exit();
                } else {
                    throw new Exception("ไม่สามารถอัปเดตข้อมูลได้");
                }
            } else {
                throw new Exception("ไม่สามารถย้ายไฟล์ที่อัปโหลดได้");
            }
        } else {
            throw new Exception("เกิดข้อผิดพลาดในการอัปโหลดไฟล์");
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    header('Location: user.php?showuser=1&pay=1&id=' . $id);
    exit();
}
?>
