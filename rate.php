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
$rate = isset($_GET['rate']) ? $_GET['rate'] : '';
$searchDate = isset($_GET['search_date']) ? $_GET['search_date'] : '';

if ($rate == "") {
    
    $perPage = 10; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $perPage; 

    try {
        
        $totalStmt = $conn->prepare("SELECT COUNT(*) FROM rate WHERE effective_date LIKE :searchDate");
        $searchDateParam = "%$searchDate%";
        $totalStmt->bindParam(':searchDate', $searchDateParam, PDO::PARAM_STR);
        $totalStmt->execute();
        $totalRecords = $totalStmt->fetchColumn();
        $totalPages = ceil($totalRecords / $perPage); 

      
        $stmt = $conn->prepare("SELECT * FROM rate WHERE effective_date LIKE :searchDate LIMIT :start, :perPage");
        $stmt->bindParam(':searchDate', $searchDateParam, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $rates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
?>
    <div class="h3" style="margin-bottom: 20px;">รายการเรทค่าน้ำ/ค่าไฟ
        <a href="admin.php?selectadmin=5&rate=1" class="btn btn-primary">เพิ่มข้อมูล</a>
    </div><br>

    <div class="clearfix">
        <div class="float-start">แสดง 
            <select name="select" id="recordsPerPage" onchange="location = this.value;">
                <option value="admin.php?page=1&search_date=<?php echo htmlspecialchars($searchDate); ?>">10 เรคคอร์ด</option>
                <option value="admin.php?page=1&perPage=20&search_date=<?php echo htmlspecialchars($searchDate); ?>" <?php if ($perPage == 20) echo 'selected'; ?>>20 เรคคอร์ด</option>
                <option value="admin.php?page=1&perPage=50&search_date=<?php echo htmlspecialchars($searchDate); ?>" <?php if ($perPage == 50) echo 'selected'; ?>>50 เรคคอร์ด</option>
            </select> ต่อหน้า
        </div>
        <div class="float-end">
            ค้นหาโดยวันที่:
            <form method="get" action="admin.php?selectadmin=5" class="d-inline">
                <input type="hidden" name="selectadmin" value="5">
                <input type="date" name="search_date" value="<?php echo htmlspecialchars($searchDate); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </form>
        </div>
    </div>

    <table class="table border border-1 table-bordered">
        <thead class="table-info">
            <th class="text-bold">ค่าน้ำ</th>
            <th class="text-bold">ค่าไฟ</th>
            <th class="text-bold" style="width: 40px;">แก้ไข</th>
            <th class="text-bold" style="width: 40px;">ลบ</th>
        </thead>
        <tbody class="table-secondary">
            <?php if (count($rates) > 0): ?>
                <?php foreach ($rates as $rate): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rate['water_rate']); ?></td>
                    <td><?php echo htmlspecialchars($rate['electricity_rate']); ?></td>
                    <td>
                        <a href="admin.php?selectadmin=5&rate=2&id=<?php echo htmlspecialchars($rate['id']); ?>" class="btn btn-warning">แก้ไข</a>
                    </td>
                    <td>

                        <form method="post" action="process_delete_rate.php" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?');">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($rate['id']); ?>">
                            <button type="submit" class="btn btn-danger">ลบ</button>
                        </form>
                    
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">ไม่มีข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    

    <div class="clearfix">
        <div class="float-start">แสดง <?php echo $start + 1; ?> ถึง <?php echo min($start + $perPage, $totalRecords); ?> ของ <?php echo $totalRecords; ?> เร็คคอร์ด</div>
        <div class="float-end">
            <?php if ($page > 1): ?>
                <a href="admin.php?selectadmin=5&page=<?php echo $page - 1; ?>&search_date=<?php echo htmlspecialchars($searchDate); ?>" class="btn btn-secondary">ก่อนหน้า</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="admin.php?selectadmin=5&page=<?php echo $page + 1; ?>&search_date=<?php echo htmlspecialchars($searchDate); ?>" class="btn btn-secondary">ถัดไป</a>
            <?php endif; ?>
        </div>
    </div>




<?php
} elseif ($rate == "1") {
?>

<form method="post" action="process_add_rate.php">
    <h3>ฟอร์มเพิ่มค่าน้ำ/ค่าไฟ</h3>
    <h5>ค่าน้ำ&nbsp;<input type="number" name="w_rate" required>&nbsp;ต่อหน่วย</h5><br>
    <h5>ค่าไฟ&nbsp;<input type="number" name="e_rate" required>&nbsp;ต่อหน่วย</h5><br>
    <h5>วันที่:&nbsp;<input type="date" name="effectivedate" required>&nbsp;</h5><br>
    <input class="btn btn-primary" name="addrate" type="submit" value="บันทึก">
    <a class="btn btn-danger" href="admin.php?selectadmin=5">ยกเลิก</a>
</form>

<?php
} elseif ($rate == "2" && isset($_GET['id'])) {
    $id = $_GET['id'];
  
    $stmt = $conn->prepare("SELECT * FROM rate WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rate_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rate_data) { 
?>
    <form method="post" action="process_update_rate.php">
        <h3>ฟอร์มแก้ไขค่าน้ำ/ค่าไฟ</h3>
        <h5>ไอดี<input type="number" style="width: 50px;" name="id" value="<?php echo htmlspecialchars($rate_data['id']); ?>" readonly></h5>
        <h5>ค่าน้ำ:<input type="number" name="w_rate" value="<?php echo htmlspecialchars($rate_data['water_rate']); ?>" required>&nbsp;ต่อหน่วย</h5><br>
        <h5>ค่าไฟ:<input type="number" name="e_rate" value="<?php echo htmlspecialchars($rate_data['electricity_rate']); ?>" required>&nbsp;ต่อหน่วย</h5><br>
        <h5>วันที่มีผล:<input type="date" name="effectivedate" value="<?php echo htmlspecialchars($rate_data['effective_date']); ?>" required>&nbsp;</h5><br>
        <input class="btn btn-primary" name="update" type="submit" value="บันทึก">
        <a class="btn btn-danger" href="admin.php?selectadmin=5">ยกเลิก</a>
    </form>

<?php
    } else {
        echo "<h3 class='text-danger'>ไม่พบข้อมูลที่ต้องการแก้ไข</h3>";
    }
} else {
    echo "<h3 class='text-danger'>ไม่พบข้อมูลที่ต้องการแก้ไข</h3>";
}
?>
</div>
<br>
<br>
</body>
</html>
