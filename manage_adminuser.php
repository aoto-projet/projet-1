<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$start = ($page > 1) ? ($page - 1) * $perPage : 0;

try {
    $search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';
    $query = "SELECT * FROM users";
    $params = [];

    if (!empty($search_id)) {
        $query .= " WHERE id = :search_id";
        $params[':search_id'] = $search_id;
    }
    
    $query .= " LIMIT :start, :perPage";
    
    $stmt = $conn->prepare($query);
    if (!empty($search_id)) {
        $stmt->bindParam(':search_id', $params[':search_id'], PDO::PARAM_INT);
    }
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count total records
    $countQuery = "SELECT COUNT(*) FROM users";
    if (!empty($search_id)) {
        $countQuery .= " WHERE id = :search_id";
    }
    
    $countStmt = $conn->prepare($countQuery);
    if (!empty($search_id)) {
        $countStmt->bindParam(':search_id', $params[':search_id'], PDO::PARAM_INT);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

    $totalPages = ceil($totalRecords / $perPage);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

$manageuser = isset($_GET["manageuser"]) ? $_GET["manageuser"] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลสมาชิก</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <?php if ($manageuser == ""): ?>
        <h3 class="mb-4">รายการแอดมิน</h3>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <a href="admin.php?selectadmin=8&manageuser=1" class="btn btn-primary mb-3">เพิ่มข้อมูล</a>

        <div class="clearfix mb-3">
            <div class="float-start">
                ค้นหาโดยไอดี:
                <form method="get" action="" class="d-inline">
                    <input type="hidden" name="selectadmin" value="8">
                    <input type="text" name="search_id" placeholder="ไอดีผู้ใช้" class="form-control d-inline" style="width: auto;">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-info">
                <tr>
                    <th>ไอดี</th>
                    <th>ชื่อ</th>
                    <th>รูปโปรไฟล์</th>
                    <th>Username</th>
                    <th>แก้ไขรหัสผ่าน</th>
                    <th>แก้ไข</th>
                    <th class="text-center">ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                        <td>
                            <img src="<?php echo $user['profile_picture'] ? './uploads/' . htmlspecialchars($user['profile_picture']) : 'default-profile.png'; ?>" 
                                 alt="Profile Picture" class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                        </td>
                        <td><?php echo htmlspecialchars($user['urole']); ?></td>
                        <td>
                            <a href="admin.php?selectadmin=8&manageuser=3&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">แก้ไขรหัสผ่าน</a>
                        </td>
                        <td>
                            <a href="admin.php?selectadmin=8&manageuser=2&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                        </td>
                        <td>
                            <form method="post" action="delete_manage_user.php" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?');">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="clearfix">
            <div class="float-start">แสดง <?php echo $start + 1; ?> ถึง <?php echo min($start + $perPage, $totalRecords); ?> ของ <?php echo $totalRecords; ?> เร็คคอร์ด</div>
            <div class="float-end">
                <?php if ($page > 1): ?>
                    <a href="admin.php?selectadmin=8&page=<?php echo $page - 1; ?>&search_id=<?php echo htmlspecialchars($search_id); ?>" class="btn btn-secondary">ก่อนหน้า</a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="admin.php?selectadmin=8&page=<?php echo $page + 1; ?>&search_id=<?php echo htmlspecialchars($search_id); ?>" class="btn btn-secondary">ถัดไป</a>
                <?php endif; ?>
            </div>
        </div>
        <br>

    <?php elseif ($manageuser == "1"): ?>
        <form method="post" action="add_manage_adminuser.php" enctype="multipart/form-data">
            <h3>เพิ่มข้อมูลสมาชิก</h3>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="firstname" class="form-label">ชื่อ</label>
                <input type="text" id="firstname" name="firstname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">นามสกุล</label>
                <input type="text" id="lastname" name="lastname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัส</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="c_password" class="form-label">ยืนยันรหัส</label>
                <input type="password" id="c_password" name="c_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="">เลือกสถานะ</option>
                    <option value="admin">admin</option>
                    <option value="user">user</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">รูปโปรไฟล์</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a class="btn btn-danger" href="admin.php">ยกเลิก</a>
        </form>

    
			<?php elseif ($manageuser == "2" && isset($_GET['id'])): ?>
    <form method="post" action="update_manage_adminuser.php" enctype="multipart/form-data">
        <?php
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC); 

            if ($user):
        ?>
            <h3>แก้ไขข้อมูลสมาชิก</h3>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="id" class="form-label">ไอดี</label>
                <input type="text" id="id" name="id" class="form-control" value="<?php echo htmlspecialchars($user['id']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="firstname" class="form-label">ชื่อ</label>
                <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">นามสกุล</label>
                <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="urole" class="form-label">สถานะ</label>
                <select name="urole" id="urole" class="form-select">
                    <option value="">เลือกสถานะ</option>
                    <option value="admin" <?php echo $user['urole'] == 'admin' ? 'selected' : ''; ?>>admin</option>
                    <option value="user" <?php echo $user['urole'] == 'user' ? 'selected' : ''; ?>>user</option>
                </select>
            </div>
			 <div class="mb-3">
                <label for="profile_picture" class="form-label">อัปโหลดรูปโปรไฟล์</label>
                <input type="file" id="profile_picture" name="profile_picture" class="form-control" accept="image/*">
                <?php if ($user['profile_picture']): ?>
                    <img src="./uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail mt-2" style="width: 100px; height: 100px;">
                <?php else: ?>
                    <p>ไม่มีรูปโปรไฟล์</p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a class="btn btn-danger" href="admin.php?selectadmin=8">ยกเลิก</a>
        </form>

        <?php endif; ?>
	
	
	<?php elseif ($manageuser == "3" && isset($_GET['id'])): ?>
    <form method="post" action="update_password.php">
        <?php
            $id = $_GET['id'];
            // ดึงข้อมูลผู้ใช้ (optional: ใช้สำหรับตรวจสอบสิทธิ์)
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC); 

            if ($user):
        ?>
            <h3>แก้ไขรหัสผ่าน</h3>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่านใหม่</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="c_password" class="form-label">ยืนยันรหัสผ่าน</label>
                <input type="password" id="c_password" name="c_password" class="form-control" required>
            </div>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a class="btn btn-danger" href="admin.php?selectadmin=8">ยกเลิก</a>
        <?php endif; ?>
    </form>
<?php endif; ?>



	
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
