<?php
session_start();
require_once 'config/db.php';
?>
<?php
if (isset($_GET['manageuser']) && $_GET['manageuser'] == 3) {
    $user_id = $_GET['id'];
    // ดึงข้อมูลผู้ใช้จากฐานข้อมูลเพื่อแสดงในฟอร์ม
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // แสดงฟอร์มแก้ไขรหัสผ่าน
    include 'edit_password_form.php'; // ฟอร์มสำหรับแก้ไขรหัสผ่าน
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	
	<form method="post" action="update_password.php">
    <h3>แก้ไขรหัสผ่านสำหรับ <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h3>
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
    <h5>รหัสใหม่&nbsp;<input type="password" name="new_password" required>&nbsp;</h5>
    <h5>ยืนยันรหัสใหม่&nbsp;<input type="password" name="confirm_password" required>&nbsp;</h5>
    <input class="btn btn-primary" type="submit" value="บันทึก">
    <a class="btn btn-danger" href="admin.php">ยกเลิก</a>
</form>

</body>
</html>