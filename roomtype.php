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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการห้องพัก</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<?php
$perPage = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page - 1) * $perPage : 0;
$searchZone = isset($_GET['search_zone']) ? $_GET['search_zone'] : '';

if (!isset($_GET['roomtype']) || $_GET['roomtype'] == "") {
?>

<div class="h3" style="margin-bottom: 20px;">รายการประเภทห้อง
    <a href="admin.php?selectadmin=7&roomtype=1" class="btn btn-primary">เพิ่มข้อมูล</a>
</div><br>
<div class="clearfix">
    <div class="float-start">แสดง 
        <select name="select" id="recordsPerPage" onchange="location = this.value;">
            <option value="admin.php?selectadmin=5&perPage=10">10 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=5&perPage=20">20 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=5&perPage=50">50 เรคคอร์ด</option>
        </select> ต่อหน้า
    </div>
    <div class="float-end">
        ค้นหาโดยโซน:
        <form method="get" action="admin.php" class="d-inline">
            <input type="hidden" name="selectadmin" value="7">
            <input type="text" name="search_zone" value="<?php echo htmlspecialchars($searchZone); ?>">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </form>
    </div>
</div>

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

<table class="table border border-1 table-bordered">
    <thead class="table-info">
        <th class="text-bold text-center" style="width: 50px;">ไอดี</th>
        <th class="text-bold text-center">img</th>
        <th class="text-bold text-center">โซน</th>
        <th class="text-bold text-center">ค่าเช่า/ต่อเดือน</th>
        <th class="text-bold text-center">ร่วงหน้า</th>
        <th class="text-bold text-center">มัดจำ</th>
        <th class="text-bold text-center">แกลฯ</th>
        <th class="text-bold text-center" style="width: 60px;">แก้ไข</th>
        <th class="text-bold text-center" style="width: 60px;">ลบ</th>
    </thead>
    <tbody class="table-secondary">
<?php
try {
    $query = "SELECT * FROM room_types WHERE type_zone LIKE :search_zone LIMIT :start, :perPage";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search_zone', "%$searchZone%", PDO::PARAM_STR);
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM room_types WHERE type_zone LIKE :search_zone");
    $countStmt->bindValue(':search_zone', "%$searchZone%", PDO::PARAM_STR);
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $perPage);

    if (count($rooms) > 0) {
        foreach ($rooms as $room) {
            echo "<tr>";
            echo "<td align=\"center\">{$room['room_type_id']}</td>";
            $target_dir = "uploads/";
            echo "<td><img src='{$target_dir}{$room['room_image']}' alt='Room Image' width='40' height='40'></td>";
            echo "<td>{$room['type_zone']}</td>";
            echo "<td>{$room['monthly_rent']}</td>";
            echo "<td>{$room['advance_rent']}</td>";
            echo "<td>{$room['credit_rent']}</td>";
            echo "<td><a href='admin.php?selectadmin=7&roomtype=3&id={$room['room_type_id']}' class='btn btn-info'>+ภาพ</a></td>";
            echo "<td><a href='admin.php?selectadmin=7&roomtype=2&id={$room['room_type_id']}' class='btn btn-warning'>แก้ไข</a></td>";
            echo "<td><a href='delete_roomtype.php?id={$room['room_type_id']}' class='btn btn-danger' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?\");'>ลบ</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9' class='text-center'>ไม่มีข้อมูลประเภทห้องพัก</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='9' class='text-center'>เกิดข้อผิดพลาด: " . $e->getMessage() . "</td></tr>";
}
?>
    </tbody>
</table>

<div class="clearfix">
    <div class="float-start">แสดง <?php echo $start + 1; ?> ถึง <?php echo min($start + $perPage, $totalRecords); ?> ของ <?php echo $totalRecords; ?> เรคคอร์ด</div>
    <div class="float-end">
        <?php if ($page > 1): ?>
            <a href="admin.php?selectadmin=5&page=<?php echo $page - 1; ?>&search_zone=<?php echo htmlspecialchars($searchZone); ?>" class="btn btn-secondary">ก่อนหน้า</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="admin.php?selectadmin=5&page=<?php echo $page + 1; ?>&search_zone=<?php echo htmlspecialchars($searchZone); ?>" class="btn btn-secondary">ถัดไป</a>
        <?php endif; ?>
    </div>
</div>


</div>
</body>
</html>
<?php
}
?>


<?php 
$roomtype = $_GET['roomtype'];
if ($roomtype == "1") {
?>


<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

<form method="post" action="process_add_room_type.php" enctype="multipart/form-data">
    <h3>ฟอร์มเพิ่มข้อมูลประเภทห้อง</h3>
   
    <div class="mb-3 d-flex align-items-center">
        <label for="type_zone" class="form-label me-2" style="min-width: 100px;">โซน</label>
        <select name="type_zone" class="form-control" style="width: auto;" required>
            <option value="">เลือกโซน</option>
            <option value="โซนA">โซนA</option>
            <option value="โซนB">โซนB</option>
        </select>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <label for="room_description" class="form-label me-2" style="min-width: 100px;">รายละเอียด</label>
        <textarea class="form-control" id="room_description" name="room_description" required style="width: auto;"></textarea>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <label for="monthly_rent" class="form-label me-2" style="min-width: 100px;">ค่าเช่าต่อเดือน</label>
        <input type="number" class="form-control" id="monthly_rent" name="monthly_rent" required style="width: auto;">
        <span class="ms-2">กรอกตัวเลขเท่านั้น</span>
    </div>

    

    <div class="mb-3 d-flex align-items-center">
        <label for="advance_rent" class="form-label me-2" style="min-width: 100px;">ล่วงหน้า</label>
        <input type="number" class="form-control" id="advance_rent" name="advance_rent" required style="width: auto;">
        <span class="ms-2">กรอกตัวเลขเท่านั้น</span>
    </div>
    <div class="mb-3 d-flex align-items-center">
        <label for="advance_rent" class="form-label me-2" style="min-width: 100px;">มัดจำ</label>
        <input type="number" class="form-control" id="advance_rent" name="credit_rent" required style="width: auto;">
        <span class="ms-2">กรอกตัวเลขเท่านั้น</span>
    </div>
    
    
    
    
    <div class="mb-3 d-flex align-items-center">
        <label for="room_image" class="form-label me-2" style="min-width: 100px;">รูปภาพปก</label>
        <input type="file" class="form-control" id="room_image" name="room_image" style="width: auto;">
    </div>

    <button type="submit" class="btn btn-primary" name="save">บันทึก</button>
    <a href="admin.php?selectadmin=7" class="btn btn-danger">ยกเลิก</a>
</form>



<script>
    CKEDITOR.replace('room_description');
</script>
<?php
}
?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($roomtype == "2") {
        
        $stmt = $conn->prepare("SELECT * FROM room_types WHERE room_type_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $roomtype_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($roomtype_data) {
?>

<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

<form method="post" action="process_update_room_type.php" enctype="multipart/form-data">
    <h3>ฟอร์มแก้ไขข้อมูลประเภทห้อง</h3>

    <div class="mb-3 d-flex align-items-center">
        <label for="room_type_id" class="form-label me-2" style="min-width: 100px;">ไอดี</label>
        <input type="text" style="width: 50px;" id="room_type_id" name="room_type_id" value="<?php echo htmlspecialchars($roomtype_data['room_type_id']); ?>" class="form-control" readonly>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <label for="type_zone" class="form-label me-2" style="min-width: 100px;">โซน</label>
        <select name="type_zone" class="form-control" style="width: auto;" required>
            <option value="">เลือกโซน</option>
            <option value="โซนA" <?php echo ($roomtype_data['type_zone'] == 'โซนA') ? 'selected' : ''; ?>>โซนA</option>
            <option value="โซนB" <?php echo ($roomtype_data['type_zone'] == 'โซนB') ? 'selected' : ''; ?>>โซนB</option>
        </select>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <label for="room_description" class="form-label me-2" style="min-width: 100px;">รายละเอียด</label>
        <textarea class="form-control" id="room_description" name="room_description" required style="width: auto;"><?php echo htmlspecialchars($roomtype_data['room_description']); ?></textarea>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <label for="monthly_rent" class="form-label me-2" style="min-width: 100px;">ค่าเช่าต่อเดือน</label>
        <input type="number" class="form-control" id="monthly_rent" name="monthly_rent" value="<?php echo htmlspecialchars($roomtype_data['monthly_rent']); ?>" required style="width: auto;">
        <span class="ms-2">กรอกตัวเลขเท่านั้น</span>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <label for="advance_rent" class="form-label me-2" style="min-width: 100px;">ล่วงหน้า</label>
        <input type="number" class="form-control" id="advance_rent" name="advance_rent" value="<?php echo htmlspecialchars($roomtype_data['advance_rent']); ?>" required style="width: auto;">
        <span class="ms-2">กรอกตัวเลขเท่านั้น</span>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <label for="credit_rent" class="form-label me-2" style="min-width: 100px;">มัดจำ</label>
        <input type="number" class="form-control" id="credit_rent" name="credit_rent" value="<?php echo htmlspecialchars($roomtype_data['credit_rent']); ?>" required style="width: auto;">
        <span class="ms-2">กรอกตัวเลขเท่านั้น</span>
    </div>

    <p>ภาพเก่า</p>
    <div class="mb-3 d-flex align-items-center"> ภาพปก &nbsp;&nbsp;
        <?php if (!empty($roomtype_data['room_image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($roomtype_data['room_image']); ?>" alt="Room Image" width="200" height="200" class="me-2">
        <?php endif; ?>
        <label for="room_image" class="form-label me-2" style="min-width: 100px;">เลือกรูป</label>
        <input type="file" class="form-control" id="room_image" name="room_image" style="width: auto;">
    </div>
    <p class="text-danger">*กรุณาลดขนาดไฟล์ภาพก่อนอัพโหลด (500x281 px)</p>

    <button type="submit" class="btn btn-primary" name="save">บันทึก</button>
    <a href="admin.php?selectadmin=7" class="btn btn-danger">ยกเลิก</a>
</form>

<script>
    CKEDITOR.replace('room_description');
</script>

<?php
        }
    } elseif ($roomtype == "3") {
        
        $stmt = $conn->prepare("SELECT gallery_images FROM room_types WHERE room_type_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $roomtype_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($roomtype_data) {
?>

<h3>ฟอร์มอัพโหลดรูปภาพ</h3>
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

<form action="process_gall_roomtype.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
    <input type="hidden" name="room_type_id" value="<?php echo htmlspecialchars($id); ?>">
    <p class="text-danger">*กรุณาลดขนาดไฟล์ภาพก่อนอัพโหลด (500x281 px)</p>
    <div class="mb-3 d-flex align-items-center">
        <label for="gallery_images" class="form-label me-2" style="min-width: 100px;">ภาพห้อง</label>
        <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple style="width: auto;" required>
    </div>
    <button class="btn btn-primary" type="submit" name="uploads">อัปโหลด</button>
</form>

<script>
    function validateForm() {
        var fileInput = document.getElementById('gallery_images');
        if (fileInput.files.length === 0) {
            alert('กรุณาเลือกไฟล์ภาพก่อนอัปโหลด');
            return false; 
        }
        return true;
    }
</script>


<br>

<table class="table border table-bordered" style="width: 500px;">
    <thead class="table-info">
        <tr>
            <th style="width: 450px;">รูป</th>
            <th>ลบ</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($roomtype_data['gallery_images'])) {
            $gallery_images = explode(',', $roomtype_data['gallery_images']);
            foreach ($gallery_images as $image) {
                if (!empty($image)) {
                    echo "<tr>
                            <td><img src='uploads/" . htmlspecialchars($image) . "' alt='Gallery Image' width='100' height='70'></td>
                            <td><a href='delete_gall_roomtype.php?id=" . urlencode($image) . "&room_type_id=" . urlencode($id) . "' class='btn btn-danger'>ลบ</a></td>
                          </tr>";
                }
            }
        } else {
            echo "<tr><td colspan='2' class='text-center'>ไม่มีภาพในแกลเลอรี่</td></tr>";
        }
        ?>
    </tbody>
</table>
<br>
<div class="text-end">
<a href="admin.php?selectadmin=7" class="btn btn-success"><<กลับ</a>
    </div>
<?php
        }
    }
}
?>



</div>
</body>
<br>
</html>
