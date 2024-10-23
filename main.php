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


try {
    $stmtRooms = $conn->prepare("SELECT * FROM room");

    $stmtRooms->execute();
    $rooms = $stmtRooms->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูลห้อง: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rate</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .room-box {
    width: 120px; /* ความกว้างของช่อง */
    height: 150px; /* ความสูงของช่อง */
    border-radius: 5px; /* มุมโค้งมน */
    display: flex; /* ใช้ Flexbox */
    flex-direction: column; /* จัดเรียงในแนวตั้ง */
    justify-content: center; /* จัดเนื้อหาให้อยู่กลาง */
    align-items: center; /* จัดเนื้อหาให้อยู่กลาง */
    margin: 10px; /* ระยะห่างระหว่างช่อง */
    font-weight: bold; /* น้ำหนักตัวอักษรหนา */
    position: relative; /* ใช้สำหรับจัดการตำแหน่ง */
}

.available {
    background-color: #28a745; /* สีพื้นหลังสำหรับห้องว่าง */
    color: white; /* สีข้อความ */
}

.occupied {
    background-color: #dc3545; /* สีพื้นหลังสำหรับห้องไม่ว่าง */
    color: white; /* สีข้อความ */
}

/* เพิ่มกรอบรอบ ๆ ห้อง */
.room-box:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    transform: translateY(-10px); /* เอฟเฟกต์เลื่อนขึ้นเมื่อเมาส์อยู่บน */
}

.text-center {
    text-align: center; /* จัดข้อความให้อยู่กลาง */
}

		
    </style>
</head>
<body>
    <div class="container">


<?php 
$main = $_GET['main'];
if($main == "")
{
    ?>
     <?php

$stmt = $conn->prepare("SELECT COUNT(id) AS count_unpaid FROM bill WHERE status = :status");
$status = 'ยังไม่จ่าย';
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$count_unpaid = $result['count_unpaid'];
?>

        <h3 class="mt-2">รายการห้องพักสำหรับคิดค่าเช่า/ออกบิล</h3>
        <p class="text-danger">ค้างค่าห้อง&nbsp;<?php echo $count_unpaid;?> </p>
        

		<?php
// 1. ดึงข้อมูลห้องจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM room");
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. เตรียมข้อมูลสำหรับโซน A และ B
$rooms_a = array_slice($rooms, 0, 10); // 10 ห้องแรกสำหรับโซน A
$rooms_b = array_slice($rooms, 10, 5); // 5 ห้องถัดไปสำหรับโซน B

// 3. แสดงข้อมูลใน HTML
?>
<div class="container">
    <h3 class="text-center">ข้อมูลห้องพัก</h3>

    <h4 class="text-center">โซน A</h4>
    <div class="row mb-4">
        <?php if (!empty($rooms_a)): ?>
            <?php foreach ($rooms_a as $room): ?>
                <div class="col-2">
                    <div class="room-box <?= $room['room_status'] == 'available' ? 'available' : 'occupied' ?>">
                        <div class="text-center">
                            <a href="admin.php?selectadmin=14&main=1&id=<?php echo $room['room_id'];?>" class="text-light text-decoration-none">
                                <?php if ($room['room_status'] == 'available'): ?>
                                    <i class="fas fa-door-open fa-2x"></i> <!-- รูปห้องเปิด -->
                                <?php else: ?>
                                    <i class="fas fa-door-closed fa-2x"></i> <!-- รูปห้องปิด -->
                                <?php endif; ?>
                                <p>ห้อง <?= htmlspecialchars($room['room_number']) ?><br>
                                <?= htmlspecialchars($room['room_zone']) ?><br> <?= $room['room_status'] == 'available' ? 'ว่าง' : 'ไม่ว่าง' ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>ไม่มีข้อมูลห้องพักในโซน A</p>
        <?php endif; ?>
    </div>

    <h4 class="text-center">โซน B</h4>
    <div class="row">
        <?php if (!empty($rooms_b)): ?>
            <?php foreach ($rooms_b as $room): ?>
                <div class="col-2">
                    <div class="room-box <?= $room['room_status'] == 'available' ? 'available' : 'occupied' ?>">
                        <div class="text-center">
                            <a href="admin.php?selectadmin=14&main=1&id=<?php echo $room['room_id'];?>" class="text-light text-decoration-none">
                                <?php if ($room['room_status'] == 'available'): ?>
                                    <i class="fas fa-door-open fa-2x"></i> <!-- รูปห้องเปิด -->
                                <?php else: ?>
                                    <i class="fas fa-door-closed fa-2x"></i> <!-- รูปห้องปิด -->
                                <?php endif; ?>
                                <p>ห้อง <?= htmlspecialchars($room['room_number']) ?><br>
                                <?= htmlspecialchars($room['room_zone']) ?><br> <?= $room['room_status'] == 'available' ? 'ว่าง' : 'ไม่ว่าง' ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>ไม่มีข้อมูลห้องพักในโซน B</p>
        <?php endif; ?>
    </div>
</div>


		
            <?php
       
        $totalStmt = $conn->prepare("SELECT COUNT(*) AS total_count FROM bill");
        $totalStmt->execute();
        $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $totalCount = $totalResult['total_count'];

        $paidStmt = $conn->prepare("SELECT COUNT(*) AS paid_count FROM bill WHERE status = 'จ่ายแล้ว'");
        $paidStmt->execute();
        $paidResult = $paidStmt->fetch(PDO::FETCH_ASSOC);
        $paidCount = $paidResult['paid_count'];

       
        $paidPercentage = $totalCount > 0 ? ($paidCount / $totalCount) * 100 : 0;
        ?>

        <div class="container mt-5" style="width: 500px;">
            <h4>แสดงเปอร์เซนต์ของบิลที่จ่ายแล้ว</h4>
            <canvas id="incomePieChart"></canvas>
        </div>

        <script>
            const paidData = {
                labels: ['จ่ายแล้ว', 'ยังไม่ได้จ่าย'],
                datasets: [{
                    label: 'รายได้',
                    data: [<?php echo $paidPercentage; ?>, <?php echo 100 - $paidPercentage; ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)', 
                        'rgba(255, 99, 132, 0.6)'  
                    ],
                }]
            };

            const ctxPie = document.getElementById('incomePieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: paidData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        title: {
                            display: true,
                            text: ''
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw.toFixed(2);
                                    return `${label}: ${value}%`;
                                }
                            }
                        }
                    }
                }
            });
        </script>








<h4 class="text-center mt-5">กราฟแสดงรายได้แต่ละเดือน</h4>
<div class="mt-5" style="width: 800px; margin: auto;">
    <canvas id="monthlyIncomeChart"></canvas>
</div>

<script>
    const monthlyIncomeData = {
        labels: [
            'มกราคม 2024', 'กุมภาพันธ์ 2024', 'มีนาคม 2024', 'เมษายน 2024', 
            'พฤษภาคม 2024', 'มิถุนายน 2024', 'กรกฎาคม 2024', 'สิงหาคม 2024', 
            'กันยายน 2024', 'ตุลาคม 2024', 'พฤศจิกายน 2024', 'ธันวาคม 2024'
        ],
        datasets: [{
            label: 'รายได้ (บาท)',
            data: [<?php
                $incomeData = [];
                $stmt = $conn->prepare("SELECT loop_bill, SUM(rent) AS total_income FROM bill WHERE status = 'จ่ายแล้ว' GROUP BY loop_bill");
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                
                for ($i = 0; $i < 12; $i++) {
                    $incomeData[$i] = 0; 
                }

               
                foreach ($results as $row) {
                    $monthIndex = array_search($row['loop_bill'], ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม']);
                    if ($monthIndex !== false) {
                        $incomeData[$monthIndex] = $row['total_income'];
                    }
                }

                
                echo implode(',', $incomeData);
            ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };

    const ctxLine = document.getElementById('monthlyIncomeChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: monthlyIncomeData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'จำนวนเงิน (บาท)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'เดือน'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: 'กราฟแสดงรายได้แต่ละเดือน (เฉพาะที่จ่ายแล้ว)'
                }
            }
        }
    });
</script>
<script src="bootstrap.bundle.min.js"></script>
<br><br>

<h4>รายงานรายได้</h4>
<table class="table" style="width: 550px;">
    <thead class="table-info">
        <tr class="h4 fw-bold">
            <td class="text-start">ว/ด/ป</td>
            <td class="text-end">รายได้</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $totalIncome = 0; 
       
        $stmt = $conn->prepare("SELECT loop_bill, SUM(rent) AS total_income FROM bill WHERE status = 'จ่ายแล้ว' GROUP BY loop_bill ORDER BY FIELD(loop_bill, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม')");
        $stmt->execute();
        $incomeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($incomeResults as $income) {
            echo "<tr>";
            echo "<td class='text-start'>" . $income['loop_bill'] . "<br>ปี 2024</td>"; // เพิ่มปีที่นี่
            echo "<td class='text-end'>" . number_format($income['total_income'], 2) . " บาท</td>";
            echo "</tr>";

           
            $totalIncome += $income['total_income'];
        }

       
        echo "<tr class='table-danger'>";
        echo "<td class='text-start font-weight-bold'>รวม</td>";
        echo "<td class='text-end font-weight-bold'>" . number_format($totalIncome, 2) . " บาท</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>




<?php
}
?>








<?php
   if (isset($_GET['main']) && $_GET['main'] == "1" && isset($_GET['id'])) {
    $id = $_GET['id'];
       

     
        $stmt = $conn->prepare("
        SELECT r.*, rt.room_description, rt.monthly_rent, rt.advance_rent, rt.credit_rent, rt.room_image 
        FROM room AS r
        JOIN room_types AS rt ON r.room_zone = rt.type_zone
        WHERE r.room_id = :id
    ");

  
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $roomshow = $stmt->fetch(PDO::FETCH_ASSOC);

    if($roomshow) {
        ?>





    <h3>ฟอร์มคิดค่าเช่า</h3>
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


    <form action="bill.php" method = "post">
    <input type="hidden" name="id" value="<?php echo $id;?>">

    <div class="row mb-3">
        <label for="room_number" class="col-form-label col-md-2">เลขห้อง</label>
        <div class="col-md-9">
            <input type="text" name="room_number" value="<?php echo $roomshow['room_number'];?>" readonly required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="loop_bill" class="col-form-label col-md-2">รอบบิล</label>
        <div class="col-md-9">
        <select name="loop_bill" id="loop_bill" required>
    <option value="">เลือกรอบบิล</option>
    <option value="มกราคม">มกราคม</option>
    <option value="กุมภาพันธ์">กุมภาพันธ์</option>
    <option value="มีนาคม">มีนาคม</option>
    <option value="เมษายน">เมษายน</option>
    <option value="พฤษภาคม">พฤษภาคม</option>
    <option value="มิถุนายน">มิถุนายน</option>
    <option value="กรกฎาคม">กรกฎาคม</option>
    <option value="สิงหาคม">สิงหาคม</option>
    <option value="กันยายน">กันยายน</option>
    <option value="ตุลาคม">ตุลาคม</option>
    <option value="พฤศจิกายน">พฤศจิกายน</option>
    <option value="ธันวาคม">ธันวาคม</option>
</select>

        </div>
    </div>

    <div class="row mb-3">
        <label for="rent" class="col-form-label col-md-2">ค่าเช่า</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="rent" name="rent" style="width: 150px;" value="<?php echo $roomshow['monthly_rent'];?>" required>
        </div>
    </div>
<?php
$id_room =  $roomshow['room_number'];
$stmt = $conn->prepare("SELECT * FROM guests WHERE room_number = :room_number");
$stmt->bindParam('room_number', $id_room, PDO::PARAM_STR);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);
?>






    <div class="row mb-3">
        <label for="name" class="col-form-label col-md-2">ชื่อผู้เช่า</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name['name'];?>" style="width: 200px;"required>
        </div>
    </div>






    <?php
$stmt = $conn->prepare("SELECT * FROM rate ORDER BY effective_date DESC LIMIT 1");
$stmt->execute();
$rate = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
  
  <?php
            
            $stmt = $conn->prepare("SELECT * FROM bill WHERE active_id = :id ORDER BY day_process DESC LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $lastbill = $stmt->fetch(PDO::FETCH_ASSOC);
            

            if ($lastbill) {
            
               
                ?>
  





    <div class="row mb-3">
        <label for="water" class="col-form-label col-md-2">ค่าน้ำ</label>
        <div class="col-md-9 d-inline-flex">
            <p>เลขมิเตอร์ครั้งนี้
            <input type="text" class="form-control me-2" id="water_meter" name="water_meter" style="width: 100px;" oninput="calculateWater()" required>
            </p>
            <p>ครั้งก่อน
            <input type="text" class="form-control me-2" id="last_water" name="last_water" value="<?php echo $lastbill['water_meter'];?>" style="width: 100px;" oninput="calculateWater()" required readonly>
            </p>
            <p>หน่วยที่ใช้
            <input type="text" class="form-control me-2" id="unit_water" name="unit_water" style="width: 100px;" readonly required>
            </p>
            <p>ราคา/ต่อหน่วย
            <input type="text" class="form-control me-2" id="price_unit"value="<?php echo $rate['water_rate'];?>" name="price_unit" style="width: 100px;" oninput="calculateWater()" readonly required>
            </p>
            <p>รวม(บาท)
            <input type="text" class="form-control me-2" id="sum_water" name="sum_water" style="width: 100px;" readonly required>
            </p>
        </div>
    </div>







    <div class="row mb-3">
        <label for="electricity" class="col-form-label col-md-2">ค่าไฟ</label>
        <div class="col-md-9 d-inline-flex">
            <p>เลขมิเตอร์ครั้งนี้
            <input type="text" class="form-control me-2" id="electricity_meter" name="electricity_meter" style="width: 100px;" oninput="calculateElectricity()" required>
            </p>
            <p>ครั้งก่อน
            <input type="text" class="form-control me-2" id="last_meter" name="last_meter" value="<?php echo $lastbill['electricity_meter'];?>" style="width: 100px;" oninput="calculateElectricity()" required readonly>
            </p>
            <p>หน่วยที่ใช้
            <input type="text" class="form-control me-2" id="unit_electricity" name="unit_electricity" style="width: 100px;" readonly required>
            </p>
            <p>ราคา/ต่อหน่วย
            <input type="text" class="form-control me-2" id="price_electricity" value="<?php echo $rate['electricity_rate'];?>"name="price_electricity" style="width: 100px;" oninput="calculateElectricity()"readonly required>
            </p>
            <p>รวม(บาท)
            <input type="text" class="form-control me-2" id="sum_electricity" name="sum_electricity" style="width: 100px;" readonly required>
            </p>
        </div>
    </div>

    <div class="row mb-3">
        <label for="sum" class="col-form-label col-md-2">รวมทั้งสิ้น</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="sum" name="sum" style="width: 150px;" readonly required>
        </div>
    </div>
   
    <div class="row">
        <div class="col-md-9 ">
            <button type="submit" name="save" class="btn btn-primary">บันทึก</button>
            <a href="admin.php?selectadmin=14" class="btn btn-danger">ยกเลิก</a>
        </div>
    </div>
</form>
		
		
<?php
            }
            ?>
<script>
function calculateWater() {
    const waterMeter = parseFloat(document.getElementById('water_meter').value) || 0;
    const lastWater = parseFloat(document.getElementById('last_water').value) || 0;  
    const priceUnit = parseFloat(document.getElementById('price_unit').value) || 0;

    const usedUnits = waterMeter - lastWater;
    document.getElementById('unit_water').value = usedUnits > 0 ? usedUnits : 0;

    const sumWater = usedUnits * priceUnit;
    document.getElementById('sum_water').value = sumWater > 0 ? sumWater.toFixed(2) : 0;

    calculateTotal();
}

function calculateElectricity() {
    const electricityMeter = parseFloat(document.getElementById('electricity_meter').value) || 0;
    const lastMeter = parseFloat(document.getElementById('last_meter').value) || 0;
    const priceElectricity = parseFloat(document.getElementById('price_electricity').value) || 0;

    const usedUnits = electricityMeter - lastMeter;
    document.getElementById('unit_electricity').value = usedUnits > 0 ? usedUnits : 0;

    const sumElectricity = usedUnits * priceElectricity;
    document.getElementById('sum_electricity').value = sumElectricity > 0 ? sumElectricity.toFixed(2) : 0;

    calculateTotal();
}

function calculateTotal() {
    const rent = parseFloat(document.getElementById('rent').value) || 0;
    const sumWater = parseFloat(document.getElementById('sum_water').value) || 0;
    const sumElectricity = parseFloat(document.getElementById('sum_electricity').value) || 0;

    const total = rent + sumWater + sumElectricity;
    document.getElementById('sum').value = total.toFixed(2);
}
</script>
<br>
<br>
<?php 
$stmt = $conn->prepare("SELECT * FROM bill WHERE active_id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$showbill = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($showbill){

    ?>
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
<p>สวัสดีคุณ <?php echo $name['name'];?> </p>	
        <thead class="table-info">

        <th class="text-bold">เลขบิล</th>
            <th class="text-bold">รอบบิล</th>
            <th class="text-bold">ค่าเช่า</th>
            <th class="text-bold">รวมน้ำ</th>
            <th class="text-bold">รวมไฟ</th>
            <th class="text-bold">รวม</th>
            <th class="text-bold">สถานะ</th>
    
            <th class="text-bold" style="width: 40px;">แก้ไข</th>
            <th class="text-bold" style="width: 40px;">ลบ</th>
        </thead>
        <tbody>
            <?php
            foreach ($showbill as $bill){


                ?>

<tr>
<td class="text-center"><?php echo $bill['id'];?></td>
<td><?php echo $bill['loop_bill'];?></td>
<td><?php echo $bill['rent'];?></td>
<td><?php echo $bill['sum_water'];?></td>
<td><?php echo $bill['sum_electricity'];?></td>
<td><?php echo $bill['sum'];?></td>
<td><?php echo $bill['status'];?></td>

<td><a href="admin.php?selectadmin=14&main=2&id=<?php echo $bill['id']?>" class="btn btn-warning btn-sm">แก้ไข</a></td>
<td><form action="delete_bill.php" method="post">
    <input type="hidden" name="id_room" value="<?php echo $id;?>">
    <input type="hidden" name = "id"value="<?php echo $bill['id'];?>">
    <button type ="submit" name="delete" class="btn btn-danger btn-sm">ลบ</button>
</form></td>
<?php 
            }
            ?>

</tr>
</tbody>

</table>
 
 <?php

}
    }
    }
    
  ?>




<?php


if(isset($_GET['main']) && isset($_GET['id'])){
    $main = $_GET['main'];
    $id = $_GET['id'];
if($main == "2"){

    $stmt = $conn->prepare("SELECT * FROM bill WHERE id =:id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$editbill = $stmt->fetch(PDO::FETCH_ASSOC);


?>


    <form action="update_bill.php" method="post">
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
        <input type="hidden" name="id" value="<?php echo $editbill['id'];?>">
    <div class="row mb-3">
      <label for="loop_bill" class="col-form-label col-md-2">รอบบิล</label>
      <div class="col-md-9">
        <select class="form-select" id="loop_bill" name="loop_bill" style="width: 120px;">
          <option value="<?php echo $editbill['loop_bill'];?>"><?php echo $editbill['loop_bill'];?></option>
          <option value="มกราคม">มกราคม</option>
    <option value="กุมภาพันธ์">กุมภาพันธ์</option>
    <option value="มีนาคม">มีนาคม</option>
    <option value="เมษายน">เมษายน</option>
    <option value="พฤษภาคม">พฤษภาคม</option>
    <option value="มิถุนายน">มิถุนายน</option>
    <option value="กรกฎาคม">กรกฎาคม</option>
    <option value="สิงหาคม">สิงหาคม</option>
    <option value="กันยายน">กันยายน</option>
    <option value="ตุลาคม">ตุลาคม</option>
    <option value="พฤศจิกายน">พฤศจิกายน</option>
    <option value="ธันวาคม">ธันวาคม</option>
    </select>
      </div>
    </div>
    
    <div class="row mb-3">
      <label for="status" class="col-form-label col-md-2">สถานะ</label>
      <div class="col-md-9">
        <select name="status" id="" required>
            <option value="<?php echo $editbill['status'];?>"><?php echo $editbill['status'];?></option>
            <option value="ยังไม่จ่าย">ยังไม่จ่าย</option>
            <option value="จ่ายแล้ว">จ่ายแล้ว</option>
        </select>
</div>
    </div>

    <div class="row mb-3">
        <label for="water" class="col-form-label col-md-2">ค่าน้ำ</label>
        <div class="col-md-9 d-inline-flex">
            <p>เลขมิเตอร์ครั้งนี้
            <input type="text" class="form-control me-2" id="water_meter" name="water_meter" style="width: 100px;" value="<?php echo $editbill['water_meter'];?> ">
            </p>
            <p>ครั้งก่อน
            <input type="text" class="form-control me-2" id="last_water" name="last_water" value="<?php echo $editbill['last_meter'];?>" style="width: 100px;">
            </p>
            <p>หน่วยที่ใช้
            <input type="text" class="form-control me-2" id="unit_water" name="unit_water" style="width: 100px;"  value="<?php echo $editbill['unit_water'];?>">
            </p>
            <p>ราคา/ต่อหน่วย
            <input type="text" class="form-control me-2" id="price_unit"value="<?php echo $editbill['price_unit'];?>" name="price_unit" style="width: 100px;">
            </p>
            <p>รวม(บาท)
            <input type="text" class="form-control me-2" id="sum_water" name="sum_water" style="width: 100px;" value="<?php echo $editbill['sum_water'];?>" >
            </p>
        </div>
    </div>







    <div class="row mb-3">
        <label for="electricity" class="col-form-label col-md-2">ค่าไฟ</label>
        <div class="col-md-9 d-inline-flex">
            <p>เลขมิเตอร์ครั้งนี้
            <input type="text" class="form-control me-2" id="electricity_meter" name="electricity_meter" style="width: 100px;" value="<?php echo $editbill['electricity_meter'];?>">
            </p>
            <p>ครั้งก่อน
            <input type="text" class="form-control me-2" id="last_meter" name="last_meter" value="<?php echo $editbill['last_meter'];?>" style="width: 100px;">
            </p>
            <p>หน่วยที่ใช้
            <input type="text" class="form-control me-2" id="unit_electricity" name="unit_electricity" style="width: 100px;"  value="<?php echo $editbill['unit_electricity'];?>">
            </p>
            <p>ราคา/ต่อหน่วย
            <input type="text" class="form-control me-2" id="price_electricity" value="<?php echo $editbill['price_electricity'];?>"name="price_electricity" style="width: 100px;">
            </p>
            <p>รวม(บาท)
            <input type="text" class="form-control me-2" id="sum_electricity" name="sum_electricity" style="width: 100px;" value="<?php echo $editbill['sum_electricity'];?>">
            </p>
        </div>
    </div>

    <div class="row mb-3">
        <label for="sum" class="col-form-label col-md-2">รวมทั้งสิ้น</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="sum" name="sum" style="width: 150px;" value="<?php echo $editbill['sum'];?>">
        </div>
    </div>


    
    
    
    
    <div class="row">
      <div class="col-md-9 ">
        <button type="submit" name ="update" class="btn btn-primary">บันทึก</button>
       
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
