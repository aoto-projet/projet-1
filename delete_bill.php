<?php
session_start();
require_once 'config/db.php';
?>
<?php
if(isset($_POST['delete'])){

$id = $_POST['id'];
$id_room = $_POST['id_room'];
$stmt = $conn->prepare("DELETE FROM bill WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$_SESSION['sucess'] = "ลบข้อมูลสำเร็จ";
header('Location: admin.php?selectadmin=14&main=1&id='.$id_room);
exit();
}
?>