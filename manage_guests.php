<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

try {
    if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {
       
        $search_id = $_GET['search_id'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :search_id");
        $stmt->bindParam(':search_id', $search_id, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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
    <script src="js/bootstrap.min.js"></script>

    <style>
    @media print {
        
        input {
            border: none;
            box-shadow: none;
        }
        
       
        .table-info {
            background-color: #d1ecf1 !important; 
            color: #0c5460 !important;
        }

        
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>


</head>
<body>
<div class="container">

<?php
$guests = $_GET['guests'];
if($guests == ""){
    ?>

    <?php

    $scanname  = isset($_GET['name']) ? $_GET['name'] : '';
    $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 10;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $perPage;
    
    try {
        
        $countStmt = $conn->prepare("SELECT COUNT(*) FROM guests WHERE name LIKE :name");
        $countStmt->bindValue(':name', '%' . $scanname . '%', PDO::PARAM_STR);
        $countStmt->execute();
        $totalRecords = $countStmt->fetchColumn();
    
        
        $stmt = $conn->prepare("SELECT * FROM guests WHERE name LIKE :name LIMIT :offset, :perPage");
        $stmt->bindValue(':name', '%' . $scanname . '%', PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $gu= $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    print_r( $scanname );
    ?>



<h3>รายการผู้เช่า&nbsp;&nbsp;<a href="admin.php?selectadmin=9&guests=1" class="btn btn-primary">เพิ่มข้อมูล</a></h3>
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
   <br>
    <div class="clearfix">
    <div class="float-start">แสดง 
        <select name="select" id="recordsPerPage" onchange="location = this.value;">
            <option value="admin.php?selectadmin=9&selectadmin=12&page=1&perPage=10&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 10) echo 'selected'; ?>>10 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=9&page=1&perPage=20&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 20) echo 'selected'; ?>>20 เรคคอร์ด</option>
            <option value="admin.php?selectadmin=9&page=1&perPage=50&search_name=<?php echo htmlspecialchars($searchname); ?>" <?php if ($perPage == 50) echo 'selected'; ?>>50 เรคคอร์ด</option>
        </select> ต่อหน้า
    </div>

        <div class="float-end">
    ค้นหาโดยชื่อ:
    <form method="get" action="admin.php?selectadmin=9" class="d-inline">
        <input type="hidden" name="selectadmin" value="9">
        <input type="text" name="name" placeholder="ชื่อ" value="<?php echo htmlspecialchars($scanname); ?>">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </form>
</div>
        </div>
<table class="table table-bordered">
    <thead class="table-info">
        <th>หมายเลขห้อง</th>
        <th>เลขบัตรประจำตัวประชาชน</th>
        <th>ชื่อและนามสกุล</th>
        <th>เบอร์โทร</th>
        <th>พิมพ์</th>
        <th>แก้ไข</th>
        <th>ลบ</th>
</thead>
<tbody class="table-secondary">

<?php foreach($gu as $gue){?>
    <td><?php echo $gue['room_number']; ?></td>
    <td><?php echo $gue['national_id']; ?></td>
    <td><?php echo $gue['name'];?></td>
    <td><?php echo $gue['phone_number'];?></td>
    <td><a href="admin.php?selectadmin=9&guests=3&id=<?php echo $gue['guests_id'];?>" class="btn btn-primary btn-sm">พิมพ์</a></td>
    <td><a href="admin.php?selectadmin=9&guests=2&id=<?php echo $gue['guests_id'];?>" class="btn btn-warning btn-sm">แก้ไข</a></td>

    <td><form action="delete_manage_guests.php" method="post" onsubmit="return confirm('คุณต้องการที่จะลบข้อมูลใช่ไหม?');">
    <input type="hidden" name="id" value="<?php echo $gue['guests_id'];?>">
    <button class="btn btn-danger btn-sm">ลบ</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>

<div class="clearfix">
    <div class="float-start">แสดง <?php echo $offset + 1; ?> ถึง <?php echo min($offset + $perPage, $totalRecords); ?> ของ <?php echo $totalRecords; ?> เร็คคอร์ด</div>
    <div class="float-end">
        <?php if ($page > 1): ?>
            <a href="admin.php?selectadmin=9&page=<?php echo $page - 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ก่อนหน้า</a>
        <?php endif; ?>
        <?php if ($page < ceil($totalRecords / $perPage)): ?>
            <a href="admin.php?selectadmin=9&page=<?php echo $page + 1; ?>&perPage=<?php echo $perPage; ?>&search_name=<?php echo htmlspecialchars($searchname); ?>" class="btn btn-secondary">ถัดไป</a>
        <?php endif; ?>
    </div>
</div>








<?php
        }
        

?>

<?php
if($guests == "1") { 
$stmt = $conn->prepare("SELECT * FROM room WHERE room_status = 'available'");
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
 <h3 class="mt-3">ฟอร์มเพิ่มข้อมูลผู้เช่า</h3><br>
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



<form action="add_guests.php" method="post">
    <div class="row mb-3">
        <label for="room_number" class="col-form-label col-md-2">เลือกห้อง</label>
        <div class="col-md-9">
            <select class="form-select" id="room_number" name="room_number" required>
                <option value="">เลือกห้อง</option>
                <?php foreach ($rooms as $roomM): ?>
                    <option value="<?php echo htmlspecialchars($roomM['room_number']); ?>"><?php echo htmlspecialchars($roomM['room_number']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label for="national_id" class="col-form-label col-md-2">กรอกเลขบัตรประชาชน</label>
        <div class="col-md-9">
            <input type="number" class="form-control" id="national_id" name="national_id" placeholder="กรอกเลขบัตรประชาชน 13 หลัก" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="name" class="col-form-label col-md-2">ชื่อสกุล</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name" name="name" placeholder="กรอกชื่อสกุล" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="phone_number" class="col-form-label col-md-2">เบอร์โทรศัพท์</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="กรอกเบอร์โทรผู้เช่า" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="contact" class="col-form-label col-md-2">กรอกผู้ติดต่อกรณีฉุกเฉิน</label>
        <div class="col-md-9">
            <textarea class="form-control" id="contact" name="contact" required placeholder="กรอกข้อมูลผู้ติดต่อกรณีฉุกเฉิน"></textarea>
        </div>
    </div>

    <div class="row mb-3">
        <label for="day" class="col-form-label col-md-2">วันที่เข้าพัก</label>
        <div class="col-md-9">
            <input type="date" id="day" name="day" required class="form-control">
        </div>
    </div>

    <div class="row mb-3">
        <label for="email" class="col-form-label col-md-2">อีเมล</label>
        <div class="col-md-9">
            <input type="email" class="form-control" id="email" name="email" placeholder="กรอกอีเมล" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="password" class="col-form-label col-md-2">รหัสผ่าน</label>
        <div class="col-md-9">
            <input type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่าน" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="confirm_password" class="col-form-label col-md-2">ยืนยันรหัสผ่าน</label>
        <div class="col-md-9">
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="status" class="col-form-label col-md-2">สถานะ</label>
        <div class="col-md-9">
            <select class="form-select" id="status" name="status" required>
                <option value="">เลือกสถานะ</option>
                <option value="active">ใช้งาน</option>
                <option value="inactive">ไม่ใช้งาน</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label for="profile_picture" class="col-form-label col-md-2">รูปโปรไฟล์</label>
        <div class="col-md-9">
            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
            <div class="form-text">ถ้ามีไฟล์ ให้เลือกไฟล์รูปโปรไฟล์</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <button type="submit" class="btn btn-primary" name="save">บันทึก</button>
            <a href="admin.php?selectadmin=9" class="btn btn-danger">ยกเลิก</a>
        </div>
    </div>
</form>

<?php
          }
          ?>

<?php
if($guests == "2" && isset($_GET['id'])){
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM guests WHERE guests_id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$edit_gu = $stmt->fetch(PDO::FETCH_ASSOC);
if ($edit_gu) { 
?>
<h3 class="mt-3">แก้ไขข้อมูลผู้เช่า</h3><br>

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


<form action="update_manage_guests.php" method="post">

<div class="row mb-3">
      <label for="id" class="col-form-label col-md-2">ไอดี</label>
      <div class="col-md-9">
        <input type="text"style="width: 50px;" class="form-control" id="id" name="id" value="<?php echo $edit_gu['guests_id'];?>"readonly>
      </div>
    </div>


<div class="row mb-3">
<label for="room_number" class="col-form-label col-md-2">เลือกห้อง</label>
<div class="col-md-9">
<?php
$stmt=$conn->prepare("SELECT * FROM room WHERE room_status = 'available'");
$stmt->execute();
$roomK = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($roomK){


  ?>


  <select class="form-select" id="room_number" name="room_number" style="width: 120px;" required>

    <option value="">เลือกห้อง</option>

    <?php 
 foreach($roomK as $roomA) { 
 
?>
      <option value="<?php echo $roomA['room_number']; ?>"><?php echo $roomA['room_number']; ?></option>
    <?php 
    } 
    ?>
 </select>
      </div>
    </div>
    <?php

}
?>
 <div class="row mb-3">
      <label for="national_id" class="col-form-label col-md-2">กรอกเลขบัตรประชาชน</label>
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
      <label for="day" class="col-form-label col-md-2">วันที่เข้าพัก</label>
      <div class="col-md-9">
      <input type="date"id="day" name="day" value="<?php echo $edit_gu['check_in_date'];?>" require>
</div>
    </div>
    
    <div class="row">
      <div class="col-md-9 ">
        <button type="submit" class="btn btn-primary" name="update">บันทึก</button>
        <a href="admin.php?selectadmin=9" class="btn btn-danger">ยกเลิก</a>
      </div>
    </div>
  </form>

<?php
}
}
?>

<?php
if ($guests == "3" && isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        
        $stmt = $conn->prepare("
            SELECT g.*, r.room_zone, rt.credit_rent, rt.advance_rent
            FROM guests g
            JOIN room r ON g.room_number = r.room_number
            JOIN room_types rt ON r.room_zone = rt.type_zone
            WHERE g.guests_id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $edit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($edit) {

      
?>


<form action="add_billdeposit.php" method="post">
    <div class="invoice" style="width: 750px;">
        <div class="invoice-header text-center"></div>
        <div class="invoice-content" id="table-section">
            <div class="clearfix d-flex justify-content-between">
            <h5>บิล เลขที่ <input type="text" name="bill_number" style="width:70px" required></h5>

                <h3 class="text-center">บิลเก็บค่ามัดจำ ค่าล่วงหน้า</h3>
            </div>

            <div class="text-center">
                <h4>หอพักหลิงหลิง</h4>
            </div>
<input type="hidden" name="id"value="<?php echo $edit['guests_id'];?>">
            <p>ห้อง&nbsp;<input type="text" name="room_number" value="<?php echo htmlspecialchars($edit['room_number']);?>" readonly style="width: 50px;">
            
            &nbsp;วันที่พิมพ์&nbsp;<input type="text" name="print_date" value="<?php echo date("d/m/Y"); ?> "readonly>
          </p>

            <p>ผู้เช่า&nbsp;<input style="width: 150px;" class="no-border-input" name="name_tenant" type="text" value="<?php echo htmlspecialchars($edit['name']);?>" readonly>

                <input class="no-border-input" style="width: 50px;" type="text" name="room_zone" value="<?php echo htmlspecialchars($edit['room_zone']);?>" readonly>

                โทร<input class="no-border-input" type="text" name="phone_number" value="<?php echo htmlspecialchars($edit['phone_number']);?>" readonly>

                วันที่เข้าพัก <input type="text" class="no-border-input" name="check_in_date" value="<?php echo date('d/m/Y', strtotime($edit['check_in_date'])); ?>" readonly>

            </p>

            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th class="text-center" style="width: 30px;">No.</th>
                        <th class="text-center">รายการ</th>
                        <th class="text-center" style="width: 150px;">หน่วยล่ะ</th>
                        <th class="text-center" style="width: 150px;">จำนวน</th>
                        <th class="text-center" style="width: 150px;">รวม</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
    <td>1.</td>
    <td>ค่ามัดจำ</td>
    <td>
        <input type="text" value="<?php echo htmlspecialchars($edit['credit_rent']); ?>" id="credit_rent" name="credit_rent" class="no-border-input" readonly>
    </td>
    <td><input type="number" id="credit" name="credit_unit" oninput="calculateTotal()"></td>
    <td><input type="number" name="sum_credit" id="credit_total" readonly></td> 
</tr>

<tr>
    <td>2.</td>
    <td>ร่วงหน้า</td>
    <td><input type="text" value="<?php echo htmlspecialchars($edit['advance_rent']); ?>" id="advance_rent" name="advance_rent" class="no-border-input" readonly></td>
    <td><input type="number" id="advance" name="advance_unit" oninput="calculateTotal()"></td>
    <td><input type="number" name="sum_advance" id="advance_total" readonly></td> 
</tr>


                        <td style="height: 100px;" colspan="5">
                            <div class="clearfix">
                                <p class="float-start fw-bold">
                                    หมายเหตุ<br>
                                    <span class="fw-light">[1] กรุณาชำระเงินก่อนวันที่ 5 ของทุกเดือน</span><br>
                                    <span class="fw-light">[2] หากเกินกำหนดจะต้องชำระค่าปรับ 50 บาท</span><br>
                                    <span class="fw-light">[3] โอนเงินผ่านธนาคารกสิกร</span>
                                </p>

                                <p class="float-end fw-bold">รวมเป็นเงินทั้งสิ้น 
                              <input type="text" name="grand_total" id="grand_total" readonly> 
            
                                

                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-footer clearfix">
            <button type="submit" name="save"class="btn btn-primary foalt-start">บันทึก</button>
            <a href="admin.php?selectadmin=9" class="float-end btn btn-success"><<กลับ</a>
        </div>
    </div>

</form>

<script>
function calculateTotal() {
    
    var creditRent = parseFloat(document.getElementById('credit_rent').value) || 0;
    var credit = parseFloat(document.getElementById('credit').value) || 0;
    var advanceRent = parseFloat(document.getElementById('advance_rent').value) || 0;
    var advance = parseFloat(document.getElementById('advance').value) || 0;

    
    var creditTotal = creditRent * credit;
    var advanceTotal = advanceRent * advance;

    
    document.getElementById('credit_total').value = creditTotal.toFixed(2);
    document.getElementById('advance_total').value = advanceTotal.toFixed(2);

   
    var grandTotal = creditTotal + advanceTotal;
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}

</script>

<?php
if (isset($_POST['save'])) {
    $credit_rent = $_POST['credit_rent'];
    $advance_rent = $_POST['advance_rent'];
    $credit = $_POST['credit'];
    $advance = $_POST['advance'];

    $re1 = $credit_rent * $credit;
    $re2 = $advance_rent * $advance;

   
    $re3 = $re1 + $re2;
}
?>


            <?php
        } else {
            echo "<p>ไม่พบข้อมูลสำหรับ ID นี้</p>";
        }
    } catch (PDOException $e) {
        echo "ข้อผิดพลาด: " . $e->getMessage();
    }
}
?>


<?php
if (isset($_GET['guests']) && $_GET['guests'] == "4" && isset($_GET['id'])) {
    $id = $_GET['id'];
   
    try {
        $stmt = $conn->prepare("SELECT * FROM add_billdeposit WHERE active_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $showbill = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
       
    
    
    if ($showbill) {
    
    ?>



    
  


<form action="" method="post">
    <div class="invoice" style="width: 750px;">
        <div class="invoice-header text-center"></div>
        <div class="invoice-content" id="table-section">
            <div class="clearfix d-flex justify-content-between">
                <h5>บิล เลขที่ <?php echo $showbill['bill_number'];?></h5>
                <h3 class="text-center">บิลเก็บค่ามัดจำ ค่าล่วงหน้า</h3>
            </div>

            <div class="text-center">
                <h4>หอพักหลิงหลิง</h4>
            </div>

            <p>ห้อง&nbsp;<?php echo $showbill['room_number'];?> &nbsp;&nbsp;&nbsp;&nbsp;วันที่พิมพ์&nbsp;<?php echo  $showbill['print_date']?></p>

            <p>ผู้เช่า&nbsp;<?php echo $showbill['name_tenant'];?>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo $showbill['room_zone'];?>&nbsp;&nbsp;&nbsp;&nbsp;
                โทร&nbsp;<?php echo $showbill['phone_number'];?>&nbsp;&nbsp;&nbsp;&nbsp;
                วันที่เข้าพัก&nbsp;<?php echo $showbill['check_in_date']; ?>
            </p>

            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th class="text-center" style="width: 30px;">No.</th>
                        <th class="text-center">รายการ</th>
                        <th class="text-center" style="width: 150px;">หน่วยล่ะ</th>
                        <th class="text-center" style="width: 150px;">จำนวน</th>
                        <th class="text-center" style="width: 150px;">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td>ค่ามัดจำ</td>
                        <td><?php echo $showbill['credit_rent'];?></td>
                        <td align="center"><?php echo $showbill['credit_unit'];?></td>
                        <td><?php echo $showbill['sum_credit'];?></td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>ค่าล่วงหน้า</td>
                        <td><?php echo $showbill['advance_rent'];?></td>
                        <td align="center"><?php echo $showbill['advance_unit'];?></td>
                        <td><?php echo $showbill['sum_advance'];?></td>
                    </tr>
                    <tr>
                        <td style="height: 100px;" colspan="5">
                            <div class="clearfix">
                                <p class="float-start fw-bold">
                                    หมายเหตุ<br>
                                    <span class="fw-light">[1] กรุณาชำระเงินก่อนวันที่ 5 ของทุกเดือน</span><br>
                                    <span class="fw-light">[2] หากเกินกำหนดจะต้องชำระค่าปรับ 50 บาท</span><br>
                                    <span class="fw-light">[3] โอนเงินผ่านธนาคารกสิกร</span>
                                </p>

                                <p class="float-end fw-bold">รวมเป็นเงินทั้งสิ้น<?php echo $showbill['grand_total'];?> <br>
                                    <br><br>
                                    <span class="fw-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับเงิน</span>
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-footers">
            <button type="button" class="btn btn-primary float-start" onclick="printTable()">พิมพ์ใบเสร็จ</button>
            
        </div>
    </div>
</form>

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
}
?>


</div>
</body>
</html>
<br>
<br>