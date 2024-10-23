<?php 
session_start();
require_once "config/db.php";
?>
<?php 
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $loop_bill = $_POST['loop_bill'];
    $status = $_POST['status'];
    $water_meter = $_POST['water_meter'];
    $last_water = $_POST['last_water'];
    $unit_water = $_POST['unit_water'];
    $price_unit = $_POST['price_unit'];
    $sum_water = $_POST['sum_water'];
    $electricity_meter = $_POST['electricity_meter'];
    $last_meter = $_POST['last_meter'];
    $unit_electricity = $_POST['unit_electricity'];
    $price_electricity = $_POST['price_electricity'];
    $sum_electricity = $_POST['sum_electricity'];
    $sum = $_POST['sum'];

    try {
  
        $stmt = $conn->prepare("UPDATE bill SET 
            loop_bill = :loop_bill,
            status = :status, 
            water_meter = :water_meter,
            last_water = :last_water,
            unit_water = :unit_water,
            price_unit = :price_unit,
            sum_water = :sum_water,
            electricity_meter = :electricity_meter,
            last_meter = :last_meter,
            unit_electricity = :unit_electricity,
            price_electricity = :price_electricity,
            sum_electricity = :sum_electricity,
            sum = :sum
            WHERE id = :id");

    
        $stmt->bindParam(':loop_bill', $loop_bill, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':water_meter', $water_meter, PDO::PARAM_STR);
        $stmt->bindParam(':last_water', $last_water, PDO::PARAM_STR);
        $stmt->bindParam(':unit_water', $unit_water, PDO::PARAM_STR);
        $stmt->bindParam(':price_unit', $price_unit, PDO::PARAM_STR);
        $stmt->bindParam(':sum_water', $sum_water, PDO::PARAM_STR);
        $stmt->bindParam(':electricity_meter', $electricity_meter, PDO::PARAM_STR);
        $stmt->bindParam(':last_meter', $last_meter, PDO::PARAM_STR);
        $stmt->bindParam(':unit_electricity', $unit_electricity, PDO::PARAM_STR);
        $stmt->bindParam(':price_electricity', $price_electricity, PDO::PARAM_STR);
        $stmt->bindParam(':sum_electricity', $sum_electricity, PDO::PARAM_STR);
        $stmt->bindParam(':sum', $sum, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $_SESSION['success'] = "แก้ไขข้อมูลสำเร็จ";
        header("Location: admin.php?selectadmin=14&main=2&id=".$id);
        exit;

    } catch(PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: admin.php?selectadmin=14&main=2&id=".$id);
    }
}
?>
