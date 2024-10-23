<?php
session_start();
require_once 'config/db.php';
?>

<?php
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $status = "แก้ไขแล้ว";

    try {
     
        $stmt = $conn->prepare("UPDATE chat SET status = :status WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();

       $_SESSION['success'] = "แก้ไขสำเร็จแล้ว";
       header('Location: admin.php?selectadmin=15');
       exit();
    
    
    } catch (Exception $e){
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=15');
    exit();
}
}
?>
