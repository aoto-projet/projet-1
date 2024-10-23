<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        
        $stmt = $conn->prepare("DELETE FROM room WHERE room_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();
        
       
        $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
        header('Location: admin.php?selectadmin=6');
        exit();
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
} else {
    echo "ข้อมูลไม่ครบถ้วนหรือไม่ถูกต้อง";
	
	
	
	// สมมติว่า $id เป็น ID ของห้องที่ย้ายออก
$stmt = $conn->prepare("SELECT room_number FROM room WHERE room_id = :room_id");
$stmt->bindParam(':room_id', $id, PDO::PARAM_INT);
$stmt->execute();
$roomData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($roomData) {
    // เปลี่ยนสถานะห้องเป็นว่าง
    $updateStatus = $conn->prepare("UPDATE room SET room_status = 'available' WHERE room_number = :room_number");
    $updateStatus->bindParam(':room_number', $roomData['room_number']);
    $updateStatus->execute();
    
    // ลบข้อมูลห้อง
    $deleteStmt = $conn->prepare("DELETE FROM getout WHERE id = :id");
    $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $deleteStmt->execute();

    $_SESSION['success'] = "ย้ายออกเรียบร้อยแล้ว";
    header('Location: admin.php?selectadmin=6');
} else {
    $_SESSION['error'] = "ไม่พบข้อมูลห้อง";
}

}
?>
