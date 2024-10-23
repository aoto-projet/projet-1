<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['admin_login'];

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rate</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
<?php
$guests = $_GET['guests'];
if($guests == "") {
  ?>


<?php

$searchroom_number = isset($_GET['room_number']) ? $_GET['room_number'] : '';
$perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

try {
   
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM guests WHERE room_number LIKE :room_number");
    $countStmt->bindValue(':room_number', '%' . $searchroom_number . '%', PDO::PARAM_STR);
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

    
    $stmt = $conn->prepare("SELECT * FROM guests WHERE room_number LIKE :searchname LIMIT :offset, :perPage");
    $stmt->bindValue(':searchname', '%' . $searchroom_number . '%', PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $room = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<div style="margin-bottom: 20px;"><h3>รายการรอย้ายออก</h3></div>

<div class="clearfix">
    <div class="float-start">แสดง 
        <select name="select" id="recordsPerPage" onchange="location = this.value;">
            <option value="admin.php?selectadmin=11&selectadmin=12&page=1&perPage=10&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 10) echo 'selected'; ?>>10 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=11&page=1&perPage=20&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 20) echo 'selected'; ?>>20 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=11&page=1&perPage=50&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 50) echo 'selected'; ?>>50 เรคคอร์ด</option>
        </select> ต่อหน้า
    </div>


    
            <div class="float-end">
                ค้นหาโดยหมายเลขห้อง:
                <form method="get" action="admin.php?selectadmin=11" class="d-inline">
                    <input type="hidden" name="selectadmin" value="11">
                    <input type="text" name="room_number" value="<?php echo htmlspecialchars($room_number); ?>" placeholder="หมายเลขห้อง">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </form>
            </div>
        </div>
        <?php if(!empty($room)): ?>

        <table class="table border border-1 table-bordered">
            <thead class="table-info">
                <th style="width: 150px;" class="text-bold">เลขห้อง</th>
                <th class="text-bold">ชื่อ-สกุล</th>
                <th class="text-bold">เบอร์โทร</th>
                <th class="text-bold">ค้างชำระ</th>
                <th style="width: 150px;" class="text-bold">ย้ายออก</th>
            </thead>
            <tbody>
            <?php foreach($room as $guest): ?>
                <tr>
                    <td><?php echo htmlspecialchars($guest['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($guest['name']); ?></td>
                    <td><?php echo htmlspecialchars($guest['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($guest['outstanding_balance']); ?></td>
                    <td><a href="admin.php?selectadmin=11&guests=1&id=<?php echo $guest['guests_id']; ?>" class="btn btn-success btn-sm">ย้ายออก</a></td>
                    </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p class= "bg-danger text-center pt-2 h3" style="height: 50px;">ไม่มีข้อมูล</p>
<?php endif; ?>

<div class="clearfix">
    <div class="float-start">แสดง <?php echo $offset + 1; ?> ถึง <?php echo min($offset + $perPage, $totalRecords); ?> ของ <?php echo $totalRecords; ?> เร็คคอร์ด</div>
    <div class="float-end">
        <?php if ($page > 1): ?>
            <a href="admin.php?selectadmin=11&page=<?php echo $page - 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ก่อนหน้า</a>
        <?php endif; ?>
        <?php if ($page < ceil($totalRecords / $perPage)): ?>
            <a href="admin.php?selectadmin=11&page=<?php echo $page + 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ถัดไป</a>
        <?php endif; ?>
    </div>
</div>

<?php
}
?>

<?php
if($guests == "1" && isset($_GET['id'])){
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM guests WHERE guests_id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$edit_gu = $stmt->fetch(PDO::FETCH_ASSOC);
if ($edit_gu) { 
?>
<h3 class="mt-3">ลงทะเบียนย้ายออก</h3><br>

<?php if(isset($_SESSION['error'])) { ?>
<div class="alert alert-danger" role="alert">
<?php
echo $_SESSION['error'];
unset($_SESSION['error']);
?>
</div>
<?php } ?>
<?php if(isset($_SESSION['success'])) { ?>
<div class="alert alert-success" role="alert">
<?php
echo $_SESSION['success'];
unset($_SESSION['success']);
?>
</div>
<?php } ?>


<form action="add_getout.php" method="post">

<div class="row mb-3">
      <label for="id" class="col-form-label col-md-2">ไอดี</label>
      <div class="col-md-9">
        <input type="text"style="width: 50px;" class="form-control" id="id" name="id" value="<?php echo $edit_gu['guests_id'];?>"readonly>
      </div>
    </div>


<div class="row mb-3">
<label for="room_number" class="col-form-label col-md-2">ห้อง</label>
<div class="col-md-9">
<input type="text" name="room_number" value="<?php echo $edit_gu['room_number'];?>">
      </div>
    </div>

 <div class="row mb-3">
      <label for="national_id" class="col-form-label col-md-2">เลขบัตรประชาชน</label>
      <div class="col-md-9">
        <input type="number"style="width: 250px;" class="form-control" id="national_id" name="national_id" value="<?php echo $edit_gu['national_id'];?>"required>
      </div>
    </div>

    <div class="row mb-3">
      <label for="name" class="col-form-label col-md-2">ชื่อสกุล</label>
      <div class="col-md-9">
        <input type="text" class="form-control" id="name" name="name"style="width: 300px;" value="<?php echo $edit_gu['name'];?>"required>
      </div>
    </div>
    
    <div class="row mb-3">
      <label for="phone_number" class="col-form-label col-md-2">เบอร์โทรศัพท์</label>
      <div class="col-md-9">
        <input type="text" class="form-control" id="phone_number" name="phone_number"style="width: 250px;" value="<?php echo $edit_gu['phone_number'];?>"required>
      </div>
    </div>
    
    <div class="row mb-3">
      <label for="contact" class="col-form-label col-md-2">กรอกผู้ติดต่อกรณีฉุกเฉิน</label>
      <div class="col-md-9">
      <textarea class="form-control" id="contact" name="contact" style="width: 300px;" required><?php echo $edit_gu['emergency_contact'];?></textarea>
</div>
    </div>
 <div class="row mb-3">
      <label for="day" class="col-form-label col-md-2">วันที่ย้ายออก</label>
      <div class="col-md-9">
      <input type="date" id="day" name="day" value="" required>

</div>
    </div>
    
    <div class="row">
      <div class="col-md-9 ">
        <button type="submit" class="btn btn-primary" name="getout">บันทึก</button>
        <a href="admin.php?selectadmin=11" class="btn btn-danger">ยกเลิก</a>
      </div>
    </div>
  </form>

<?php
}
}
?>

</div>
</body>
</html>
