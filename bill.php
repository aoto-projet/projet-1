<?php
session_start();
require_once 'config/db.php';
?>

<?php

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $room_number = $_POST['room_number'];
    $loop_bill = $_POST['loop_bill'];
    $rent = $_POST['rent'];
    $name = $_POST['name'];
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
        $stmt = $conn->prepare("INSERT INTO bill (active_id, room_number, loop_bill, rent, name, water_meter, last_water, unit_water, price_unit, sum_water, electricity_meter, last_meter, unit_electricity, price_electricity, sum_electricity, sum, day_process) VALUES(:active_id, :room_number, :loop_bill, :rent, :name, :water_meter, :last_water, :unit_water, :price_unit, :sum_water, :electricity_meter, :last_meter, :unit_electricity, :price_electricity, :sum_electricity, :sum, NOW());");
        
        $stmt->bindParam(':active_id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        $stmt->bindParam(':loop_bill', $loop_bill, PDO::PARAM_STR);
        $stmt->bindParam(':rent', $rent, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
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
        
        $stmt->execute();
        
        $_SESSION['success'] = "บันทึกข้อมูลสำเร็จ";
        header('Location: admin.php?selectadmin=14&main=1&id=' . $id);
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header('Location: admin.php?selectadmin=14&main=1&id=' . $id);
        exit();
    }
}
?>
