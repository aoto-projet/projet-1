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

$searchname = isset($_GET['name']) ? $_GET['name'] : '';
$perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

try {
 
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM getout WHERE name LIKE :name");
    $countStmt->bindValue(':name', '%' . $searchname . '%', PDO::PARAM_STR);
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

   
    $stmt = $conn->prepare("SELECT * FROM getout WHERE name LIKE :name LIMIT :offset, :perPage");
    $stmt->bindValue(':name', '%' . $searchname . '%', PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $name = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<div style="margin-bottom: 20px;"><h3>รายการย้ายออกแล้ว</h3></div>

<div class="clearfix">
    <div class="float-start">แสดง 
        <select name="select" id="recordsPerPage" onchange="location = this.value;">
            <option value="admin.php?selectadmin=10&selectadmin=12&page=1&perPage=10&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 10) echo 'selected'; ?>>10 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=10&page=1&perPage=20&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 20) echo 'selected'; ?>>20 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=10&page=1&perPage=50&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 50) echo 'selected'; ?>>50 เรคคอร์ด</option>
        </select> ต่อหน้า
    </div>


    
            <div class="float-end">
                ค้นหาโดยชื่อ:
                <form method="get" action="admin.php?selectadmin=10" class="d-inline">
                    <input type="hidden" name="selectadmin" value="10">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="ชื่อ">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </form>
            </div>
        </div>
        <?php if(!empty($name)): ?>


<table class="table border border-1 table-bordered">
    <thead class="table-info">
        <tr>
            <th>หมายเลขห้อง</th>
            <th>ชื่อ-สกุล</th>
            <th>เบอร์โทร</th>
            <th>วันที่ย้ายออก</th>
        </tr>
    </thead>
    <tbody>
   
            <?php foreach ($name as $getout): ?>
                <tr>
                    <td><?php echo htmlspecialchars($getout['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($getout['name']); ?></td>
                    <td><?php echo htmlspecialchars($getout['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($getout['day']); ?></td>
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
            <a href="admin.php?selectadmin=10&page=<?php echo $page - 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ก่อนหน้า</a>
        <?php endif; ?>
        <?php if ($page < ceil($totalRecords / $perPage)): ?>
            <a href="admin.php?selectadmin=10&page=<?php echo $page + 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ถัดไป</a>
        <?php endif; ?>
    </div>
</div>


</div>
</body>
</html>
