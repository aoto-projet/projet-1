<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['admin_login'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการที่รอชำระเงิน</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">


<?php

$searchname = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

try {
   
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM bill WHERE status = 'ยังไม่จ่าย' AND name LIKE :searchname");
    $countStmt->bindValue(':searchname', '%' . $searchname . '%', PDO::PARAM_STR);
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

    
    $stmt = $conn->prepare("SELECT * FROM bill WHERE status = 'ยังไม่จ่าย' AND name LIKE :searchname LIMIT :offset, :perPage");
    $stmt->bindValue(':searchname', '%' . $searchname . '%', PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $bill = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<div style="margin-bottom: 20px;"><h3>รายการที่รอชำระเงิน</h3></div>

<div class="clearfix">
    <div class="float-start">แสดง 
        <select name="select" id="recordsPerPage" onchange="location = this.value;">
            <option value="admin.php?selectadmin=13&page=1&perPage=10&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 10) echo 'selected'; ?>>10 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=13&page=1&perPage=20&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 20) echo 'selected'; ?>>20 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=13&page=1&perPage=50&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 50) echo 'selected'; ?>>50 เรคคอร์ด</option>
        </select> ต่อหน้า
    </div>

    <div class="float-end">  
        ค้นหาโดยชื่อ:
        <form method="get" action="admin.php?selectadmin=13" class="d-inline">
            <input type="hidden" name="selectadmin" value="13">
            <input type="text" name="search_name" placeholder="ชื่อ" value="<?php echo htmlspecialchars($searchname); ?>">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </form>
    </div>
</div>

<?php if(!empty($bill)): ?>
<table class="table border border-1 table-bordered">
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
    <thead class="table-info">
        <tr>
            <th>เลขบิล</th>
            <th>ห้อง</th>
            <th>ผู้เช่า</th>
            <th>รอบบิล</th>
            <th>ค่าเช่า</th>
            <th>รวม(น้ำ)</th>
            <th>รวม(ไฟ)</th>
            <th>รวม</th>
            <th>จ่าย</th>
            <th>แจ้ง</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($bill as $showbill): ?>
        <tr>
            <td><?php echo $showbill['id']; ?></td>
            <td><?php echo $showbill['room_number']; ?> </td>
            <td><?php echo $showbill['name']; ?> </td>
            <td><?php echo $showbill['loop_bill']; ?></td>
            <td><?php echo $showbill['rent']; ?></td>
            <td><?php echo $showbill['sum_water']; ?></td>
            <td><?php echo $showbill['sum_electricity']; ?></td>
            <td><?php echo $showbill['sum']; ?></td>
            <td class="text-center">
                
            
            <form action="pay.php" method="post" onsubmit="return confirm('จ่าย');">
<input type="hidden" name="id" value = "<?php echo $showbill['id']; ?>"ี>
<input type="hidden" name="upstatus" value="จ่ายแล้ว"> 
<button type="submit" class="btn btn-primary btn-sm">จ่าย</button>
</form></td>
<td>
<a href="javascript:void(0);" class="btnb btn-info btn-sm text-decoration-none" data-bs-toggle="modal" data-bs-target="#settingsModal" data-id="<?php echo $showbill['id']; ?>">แจ้ง</a>



</td>
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
            <a href="admin.php?selectadmin=13&page=<?php echo $page - 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ก่อนหน้า</a>
        <?php endif; ?>
        <?php if ($page < ceil($totalRecords / $perPage)): ?>
            <a href="admin.php?selectadmin=13&page=<?php echo $page + 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ถัดไป</a>
        <?php endif; ?>
    </div>
</div>



<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="settingsModalLabel">ส่งการแจ้งเตือนบิล</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       

                        <form action="send.php" method="post">
<input type="hidden" name ="send_admin" value="<?php  echo $user_id;?>">

                        <?php
                        $stmt = $conn->prepare("SELECT * FROM users WHERE urole = 'user'");
                        $stmt->execute();
                        $showuser = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>


                            <div class="mb-3">
                                <label for="active_user" class="form-label">ชื่อผู้รับ</label>
                               <select name="active_user" id="active_user" class="form-control" >
                                <?php
                                foreach ($showuser as $showname)
                                {
                                    ?>
                                <option value="<?php echo $showname['id'];?>"><?php echo $showname['firstname'].' ' .$showname['lastname'];?></option>
                            <?php
                                }
                                ?>
                               </select>
                            </div>

                            <div class="mb-3">
    <label for="active_bill" class="form-label">แจ้งบิลเลขที่</label>
    <input type="text" class="form-control" name="active_bill" id="active_bill" value="">
</div>

                                
                           
                           

                            <div class="mb-3">
                                <label for="text" class="form-label">ข้อความที่แจ้ง</label>
                                <input type="text" class="form-control" id="text" value="กรุณาจ่ายบิล" name="text">
                            </div>
                       


                            <button type="submit" class="btn btn-primary" name="send">บันทึก</button>
                        </form>

                    </div>
                    <div class="modal-footer">
                       
                    </div>
                </div>
            </div>
        </div>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        var settingsModal = document.getElementById('settingsModal');
        settingsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var billId = button.getAttribute('data-id');

            
            var billNumberInput = settingsModal.querySelector('#active_bill');
            billNumberInput.value = billId; 
        });
    });
</script>


    <script src="script.js"></script> 

</div>

</body>
</html>
