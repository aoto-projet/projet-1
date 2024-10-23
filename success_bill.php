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
    <title>รายการที่จ่ายแล้ว</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">

<?php
$bill = $_GET['bill'];
if($bill == ""){
    ?>

<?php

$searchname = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

try {
    
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM bill WHERE status = 'จ่ายแล้ว' AND name LIKE :searchname");
    $countStmt->bindValue(':searchname', '%' . $searchname . '%', PDO::PARAM_STR);
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

  
    $stmt = $conn->prepare("SELECT * FROM bill WHERE status = 'จ่ายแล้ว' AND name LIKE :searchname LIMIT :offset, :perPage");
    $stmt->bindValue(':searchname', '%' . $searchname . '%', PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $bill = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<div style="margin-bottom: 20px;"><h3>รายการชำระเงินแล้ว</h3></div>

<div class="clearfix">
    <div class="float-start">แสดง 
        <select name="select" id="recordsPerPage" onchange="location = this.value;">
            <option value="admin.php?selectadmin=12&selectadmin=12&page=1&perPage=10&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 10) echo 'selected'; ?>>10 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=12&page=1&perPage=20&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 20) echo 'selected'; ?>>20 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=12&page=1&perPage=50&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 50) echo 'selected'; ?>>50 เรคคอร์ด</option>
        </select> ต่อหน้า
    </div>

    <div class="float-end">  
        ค้นหาโดยชื่อ:
        <form method="get" action="admin.php?selectadmin=12" class="d-inline">
            <input type="hidden" name="selectadmin" value="12">
            <input type="text" name="search_name" placeholder="ชื่อ" value="<?php echo htmlspecialchars($searchname); ?>">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </form>
    </div>
</div>

<?php if(!empty($bill)): ?>
<table class="table border border-1 table-bordered">
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
            <td><a href="admin.php?selectadmin=12&bill=1&id=<?php echo $showbill['id']; ?>" class="btn btn-info btn-sm">เปิด</a></td>
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
            <a href="admin.php?selectadmin=12&page=<?php echo $page - 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ก่อนหน้า</a>
        <?php endif; ?>
        <?php if ($page < ceil($totalRecords / $perPage)): ?>
            <a href="admin.php?selectadmin=12&page=<?php echo $page + 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ถัดไป</a>
        <?php endif; ?>
    </div>
</div>


<?php
}
?>

<?php
$bill = $_GET['bill']&&isset($_GET['id']);
$id = $_GET['id'];
if($bill == "1"){

    $stmt = $conn->prepare("SELECT * FROM bill WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $billshow = $stmt->fetch(PDO::FETCH_ASSOC);
if($billshow){
 
    ?>

<?php
$date = $billshow['day_process'];
$formattedDate = date("d-m-Y", strtotime($date));
?>
<form action="" method="post">
    <div class="invoice" style="width: 750px;">
        <div class="invoice-header text-center"></div>
        <div class="invoice-content" id="table-section">
            <div class="clearfix d-flex justify-content-between">
                <h5>บิล เลขที่ <?php echo $billshow['id'];?></h5>
                <h3 class="text-center">บิลเก็บค่าเช่า หอพักหลิงหลิง</h3>
            </div>

            <div class="text-center">
                       
            </div>

            <p>ห้อง&nbsp;<?php echo $billshow['room_number'];?> &nbsp;&nbsp;เดือน&nbsp;<?php echo $billshow['loop_bill'];?> &nbsp;&nbsp;วันที่ออกบิล&nbsp;<?php echo $formattedDate; ?></p>

            <p>ผู้เช่า&nbsp;<?php echo $billshow['name'];?>&nbsp;&nbsp;&nbsp;&nbsp;
               วันที่ชำระเงิน&nbsp;<?php echo date("d-m-Y");?>
            </p>

            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th class="text-center" style="width: 100px;">รายการ</th>
                        <th class="text-center" style="width: 110px;">เลขครั้งนี้</th>
                        <th class="text-center" style="width: 110px;">เลขครั้งก่อน</th>
                        <th class="text-center" style="width: 110px;">หน่วย</th>
                        <th class="text-center" style="width: 110px;">หน่วยล่ะ</th>
                        <th class="text-center">รวม(บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                     
                        <td align="center">1.ค่าน้ำ</td>
                        <td align="center"><?php echo $billshow['water_meter'];?></td>
                        <td align="center"><?php echo $billshow['last_water'];?></td>
                        <td align="center"><?php echo $billshow['unit_water'];?></td>
                        <td align="center"><?php echo $billshow['price_unit'];?></td>
                        <td align="center"><?php echo $billshow['sum_water'];?></td>
                    </tr>
                    <tr>
                    
                        <td align="center">2.ค่าไฟ</td>
                        <td align="center"><?php echo $billshow['electricity_meter'];?></td>
                        <td align="center"><?php echo $billshow['last_meter'];?></td>
                        <td align="center"><?php echo $billshow['unit_electricity'];?></td>
                        <td align="center"><?php echo $billshow['price_electricity'];?></td>
                        <td align="center"><?php echo $billshow['sum_electricity'];?></td>
                    </tr>
                    
                    <tr>
                      
                        <td align="center">3.ค่าห้อง</td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"><?php echo $billshow['rent'];?></td>
                    </tr>
                    
                    
                    
                    
                    
                    
                    <tr>

                        <td style="height: 100px;" colspan="10">
                            <div class="clearfix">
                                <p class="float-start fw-bold">
                                    หมายเหตุ<br>
                                    <span class="fw-light">[1] กรุณาชำระเงินก่อนวันที่ 5 ของทุกเดือน</span><br>
                                    <span class="fw-light">[2] หากเกินกำหนดจะต้องชำระค่าปรับ 50 บาท</span><br>
                                    <span class="fw-light">[3] โอนเงินผ่านธนาคารกสิกร</span>
                                </p>

                                <p class="float-end fw-bold">รวมเป็นเงินทั้งสิ้น&nbsp;&nbsp;<?php echo $billshow['sum'];?> <br>
                                    <br><br>
                                    <span class="fw-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับเงิน</span>
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-footers claerfix">
            <button type="button" class="btn btn-primary float-start" onclick="printTable()">พิมพ์ใบเสร็จ</button>
            <div class="float-end">
    <img src="uploads/sleep/<?php echo $billshow['sleep']; ?>" alt=""width="150" height="150">
</div>

        </div>
    </div>
</form>

<?php
}
?>


<script>

function printTable() {
            var originalContents = document.body.innerHTML;
            var printContents = document.getElementById('table-section').innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            copyInputValuesToSpans(); 
        }
</script>





<?php
}
?>


</div>
</body>
</html>
