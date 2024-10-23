<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_login']; 

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
    <title>user_pay</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script> 
    <script src="js/bootstrap.bundle.js"></script>
</head>
<body>
    <div class="container">

    <?php
    $pay = $_GET['pay'];
    if($pay ==""){
        ?>

    <?php
$stmt = $conn->prepare("SELECT * FROM chat WHERE active_user = :active_user");
$stmt->bindParam(':active_user', $user_id, PDO::PARAM_INT);
$stmt->execute();
$chat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="clearfix" style="margin-left: 200px;">
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
    <div class="float-start">
    <h3>เลือกบิลที่จะจ่าย</h3>

  
<table class="table">
<thead>
    <th>บิลเลขที่</th>
    <th>วันที่แจ้งเตือน</th>
    <th>ดูบิล</th>
</thead>
<tbody>
<?php foreach ($chat as $chatA) { ?>
    <?php
$date = $chatA['time'];
$formattedDate1 = date("H:i:s d-m-Y", strtotime($date));
?>
   
<tr>
    <td><?php echo htmlspecialchars($chatA['active_bill']); ?></td>
    <td><?php echo $formattedDate1;?></td>
    <td><a href="user.php?showuser=1&pay=1&id=<?php echo $chatA['active_bill'];?>" class="btn btn-warning btn-sm" >ดูบิล</a></td>
</tr>
<?php } ?>
</tbody>


</table>
       

</div>

 <div class="float-end" style="margin-right: 200px;">
        <?php foreach($chat as $chat1) { ?>
            <?php
$date = $chat1['time'];
$formattedDate = date("H:i:s d-m-Y", strtotime($date));
?>
            <div class="card mb-3 shadow-sm" style="max-width: 18rem;">
                <div class="card-body">
                    <p class="card-text"><?php echo htmlspecialchars($chat1['text']); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars($chat1['active_bill']); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars($formattedDate); ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php
    }
    ?>

<?php
    $pay = $_GET['pay']&&isset($_GET['id']);
    $idbill = $_GET['id'];
    if($pay =="1"){
        
    $stmt = $conn->prepare("SELECT * FROM bill WHERE id = :id");
    $stmt->bindParam(':id', $idbill, PDO::PARAM_INT);
    $stmt->execute();
    $billshow = $stmt->fetch(PDO::FETCH_ASSOC);
    if($billshow){
      
        ?>

<?php
$date = $billshow['day_process'];
$formattedDate = date("d-m-Y", strtotime($date));
?>
<form action="" method="post">
    <div class="invoice" style="margin-left: 300px; width: 750px;">
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
        <div class="invoice-footers clearfix">
            <button type="button" class="btn btn-primary float-start float-start" onclick="printTable()">พิมพ์ใบเสร็จ</button>
            <a href="#" class="float-end btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#settingsModal">จ่าย
        </a>

        

</div>
    </div>
</form>

<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="settingsModalLabel">จ่ายค่าห้องพัก</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    
                    <form action="pay_bill.php" method="post" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo htmlspecialchars($billshow['id']); ?>" name="id">

    <div class="mb-3" style="margin-left: 140px;">
        <h4>สแกนจ่าย</h4>
        <img src="img/download.jpg" alt="" width="150" height="150">
        <p>ธนาคาร.....</p>
    </div>

    <div class="mb-3">
        <label for="pic" class="form-label">แนบสลีป</label>
        <input type="file" class="form-control" id="pic" name="pic" required>
    </div>
  
    <button type="submit" class="btn btn-primary" name="pay">จ่าย</button>
</form>

                    </div>
                    <div class="modal-footer">
                     
                    </div>
                </div>
            </div>
        </div>

        <script src="script.js"></script> 
















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
<br><br>

    </div>
</body>
</html>