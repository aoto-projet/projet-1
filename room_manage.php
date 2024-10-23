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

$room = isset($_GET['room']) ? $_GET['room'] : '';
$search_room = isset($_GET['search_room']) ? $_GET['search_room'] : '';
$perPage = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$start = ($page - 1) * $perPage; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการห้องพัก</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
	
	
	<style>
    .table th, .table td {
        padding: 1px; /* ปรับตามต้องการ */
    }
</style>

</head>
<body>
    <div class="container mt-3">

    <?php
    if (empty($room)) {
        try {
            if (!empty($search_room)) {
                $stmt = $conn->prepare("SELECT * FROM room WHERE room_number LIKE :search_room LIMIT :start, :perPage");
                $stmt->bindValue(':search_room', '%' . $search_room . '%', PDO::PARAM_STR);
            } else {
                $stmt = $conn->prepare("SELECT * FROM room LIMIT :start, :perPage");
            }

            $stmt->bindValue(':start', $start, PDO::PARAM_INT);
            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalStmt = $conn->prepare("SELECT COUNT(*) FROM room");
            $totalStmt->execute();
            $totalRecords = $totalStmt->fetchColumn();
            $totalPages = ceil($totalRecords / $perPage);

            if ($rooms) {
                ?>
                <div class="h3 mb-4">รายการห้อง
                    <a href="admin.php?selectadmin=6&room=1" class="btn btn-primary">เพิ่มข้อมูล</a>
                </div>
                
                <div class="clearfix mb-3">
                    <div class="float-start">แสดง 
                        <select name="select" id="recordsPerPage" class="form-select" onchange="location = this.value;">
                            <option value="?selectadmin=6&page=1&perPage=10">10 เรคคอร์ด</option>
                            <option value="?selectadmin=6&page=1&perPage=20">20 เรคคอร์ด</option>
                            <option value="?selectadmin=6&page=1&perPage=50">50 เรคคอร์ด</option>
                        </select> ต่อหน้า
                    </div>
                    <div class="float-end">
                        ค้นหาโดยหมายเลขห้อง:
                        <form method="get" action="admin.php?selectadmin=6" class="d-inline">
                            <input type="hidden" name="selectadmin" value="6">
                            <input type="text" name="search_room" class="form-control d-inline-block" placeholder="หมายเลขห้อง" value="<?php echo htmlspecialchars($search_room); ?>" style="width: 200px;">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </form>
                    </div>
                </div>

                <table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th width="18%" class="text-center">ห้อง</th>
            <th width="54%" class="text-center">โซน</th>
            <th width="14%" class="text-center">สถานะ</th>
            <th width="7%" class="text-center">แก้ไข</th>
            <th width="7%" class="text-center">ลบ</th>
        </tr>
    </thead>
    <tbody>
		
		
                    <?php
                    foreach ($rooms as $room) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($room['room_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($room['room_zone']) . "</td>";
                        echo "<td>" . (($room['room_status'] == 'available') ? 'ว่าง' : 'มีผู้พัก') . "</td>";
                        echo "<td><a href='admin.php?selectadmin=6&room=2&id=" . $room['room_id'] . "' class='btn btn-warning'>แก้ไข</a></td>";
                        echo "<td class='text-center'>
                                <form method='post' action='delete_room.php' onsubmit='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?\");'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($room['room_id']) . "'>
                                    <button type='submit' class='btn btn-danger'>ลบ</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>

                <div class="clearfix">
                    <div class="float-start">แสดง <?php echo $start + 1; ?> ถึง <?php echo min($start + $perPage, $totalRecords); ?> ของ <?php echo $totalRecords; ?> เร็คคอร์ด</div>
                    <div class="float-end">
                        <?php if ($page > 1): ?>
                            <a href="admin.php?selectadmin=6&page=<?php echo $page - 1; ?>&search_room=<?php echo htmlspecialchars($search_room); ?>" class="btn btn-secondary">ก่อนหน้า</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="admin.php?selectadmin=6&page=<?php echo $page + 1; ?>&search_room=<?php echo htmlspecialchars($search_room); ?>" class="btn btn-secondary">ถัดไป</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
            } else {
                echo "<p>ไม่มีข้อมูลห้องพัก</p>";
            }
        } catch (PDOException $e) {
            echo "เกิดข้อผิดพลาด: " . $e->getMessage();
        }
    }
    ?>
    </div>

    <?php
    if ($room == "1") {
        try {
            $stmt = $conn->prepare("SELECT * FROM room_types");
            $stmt->execute();
            $room_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($room_types) {
                ?>
                <div class="container mt-4">
                    <h3>ฟอร์มเพิ่มข้อมูลห้อง</h3>
                    <form method="post" action="process_add_room.php">
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="room_number" class="form-label">หมายเลขห้องพัก</label>
                            <input type="text" class="form-control" name="room_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="type_zone" class="form-label">ประเภท</label>
                            <select name="type_zone" class="form-select" required>
                                <option value="">เลือกโซน</option>
                                <?php
                                foreach ($room_types as $type) {
                                    echo "<option value='{$type['type_zone']}'>{$type['type_zone']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="room_status" class="form-label">สถานะห้องพัก</label>
                            <select name="room_status" class="form-select" required>
                                <option value="available">ว่าง</option>
                                <option value="occupied">มีผู้พัก</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="addroom">บันทึก</button>
                        <a href="admin.php?selectadmin=6" class="btn btn-danger">ยกเลิก</a>
                    </form>
                </div>
                <?php
            } else {
                echo "<p class='text-danger'>ไม่พบข้อมูลประเภทห้อง</p>";
            }
        } catch (PDOException $e) {
            echo "เกิดข้อผิดพลาด: " . $e->getMessage();
        }
    }
    ?>

    <?php
    if ($room == "2") {
        $room_id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($room_id) {
            try {
                $stmt = $conn->prepare("SELECT * FROM room WHERE room_id = :room_id");
                $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
                $stmt->execute();
                $room_data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($room_data) {
                    ?>
                    <div class="container mt-4">
                        <h3>ฟอร์มแก้ไขข้อมูลห้อง</h3>
                        <form method="post" action="update_room.php">
                            <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_data['room_id']); ?>">
                            <div class="mb-3">
                                <label for="room_number" class="form-label">หมายเลขห้อง</label>
                                <input type="text" class="form-control" name="room_number" value="<?php echo htmlspecialchars($room_data['room_number']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="room_zone" class="form-label">โซน</label>
                                <select name="room_zone" class="form-select" required>
                                    <option value="โซนA" <?php if ($room_data['room_zone'] == 'โซนA') echo 'selected'; ?>>โซน A</option>
                                    <option value="โซนB" <?php if ($room_data['room_zone'] == 'โซนB') echo 'selected'; ?>>โซน B</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="room_status" class="form-label">สถานะ</label>
                                <select name="room_status" class="form-select" required>
                                    <option value="available" <?php if ($room_data['room_status'] == 'available') echo 'selected'; ?>>ว่าง</option>
                                    <option value="occupied" <?php if ($room_data['room_status'] == 'occupied') echo 'selected'; ?>>มีผู้เช่า</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="update">บันทึก</button>
                            <a href="admin.php?selectadmin=6" class="btn btn-danger">ยกเลิก</a>
                        </form>
                    </div>
                    <?php
                } else {
                    echo "<p class='text-danger'>ไม่พบข้อมูลห้อง</p>";
                }
            } catch (PDOException $e) {
                echo "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        } else {
            echo "<p class='text-danger'>ไม่มีการระบุรหัสห้อง</p>";
        }
    }
    ?>
</div>
</body>
</html>
