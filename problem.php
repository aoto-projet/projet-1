<?php
session_start();
require_once 'config/db.php';

?>

<?php
if(isset($_POST['send'])){

    $text = $_POST['problem'];
    $send_user = $_POST['user_id'];
    $active_admin = $_POST['admin_id'];

    try { 
        $stmt = $conn->prepare("INSERT INTO chat (active_admin, send_user, text, time) VALUES (:active_admin, :send_user, :text, NOW())");
        $stmt->bindParam(':active_admin', $active_admin);
        $stmt->bindParam(':send_user', $send_user);
        $stmt->bindParam(':text', $text);
        $stmt->execute();

        $_SESSION['success'] = "ส่งข้อความสำเร็จ";
        header("location: user.php?showuser=1");
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("location: user.php?showuser=1");
    }
}
?>




