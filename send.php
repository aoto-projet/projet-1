<?php
session_start();
require_once 'config/db.php';
?>

<?php
if(isset($_POST['send'])){
    $send_admin = $_POST['send_admin'];
    $active_user = $_POST['active_user'];
    $active_bill = $_POST['active_bill'];
    $text = $_POST['text'];

    try { 
        $stmt = $conn->prepare("INSERT INTO chat (send_admin, active_user, active_bill, text, time) VALUES (:send_admin, :active_user, :active_bill, :text, NOW())");
        $stmt->bindParam(':send_admin', $send_admin);
        $stmt->bindParam(':active_user', $active_user);
        $stmt->bindParam(':active_bill', $active_bill);
        $stmt->bindParam(':text', $text);
    
        $stmt->execute();

        $_SESSION['success'] = "ส่งข้อความสำเร็จ";
        header("location: admin.php?selectadmin=13");
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("location: admin.php?selectadmin=13");
    }
}






?>
