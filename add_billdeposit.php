<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $bill_number = $_POST['bill_number'];
    $room_number = $_POST['room_number'];
    $print_date = $_POST['print_date'];
    $name_tenant = $_POST['name_tenant'];
    $room_zone = $_POST['room_zone'];
    $phone_number = $_POST['phone_number'];
    $check_in_date = $_POST['check_in_date'];
    $credit_rent = $_POST['credit_rent'];
    $credit_unit = $_POST['credit_unit'];
    $sum_credit = $_POST['sum_credit'];
    $advance_rent = $_POST['advance_rent'];
    $advance_unit = $_POST['advance_unit'];
    $sum_advance = $_POST['sum_advance'];
    $grand_total = $_POST['grand_total'];
    
    try {
    
        $stmt = $conn->prepare("INSERT INTO add_billdeposit (active_id, bill_number, room_number, print_date, name_tenant, room_zone, phone_number, check_in_date, credit_rent, credit_unit, sum_credit, advance_rent, advance_unit, sum_advance, grand_total)
        VALUES (:active_id, :bill_number, :room_number, :print_date, :name_tenant, :room_zone, :phone_number, :check_in_date, :credit_rent, :credit_unit, :sum_credit, :advance_rent, :advance_unit, :sum_advance, :grand_total)");

         $stmt->bindParam(':active_id',   $id, PDO::PARAM_STR);
        $stmt->bindParam(':bill_number', $bill_number, PDO::PARAM_STR);
        $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        $stmt->bindParam(':print_date', $print_date, PDO::PARAM_STR);
        $stmt->bindParam(':name_tenant', $name_tenant, PDO::PARAM_STR);
        $stmt->bindParam(':room_zone', $room_zone, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':check_in_date', $check_in_date, PDO::PARAM_STR);
        $stmt->bindParam(':credit_rent', $credit_rent, PDO::PARAM_STR);
        $stmt->bindParam(':credit_unit', $credit_unit, PDO::PARAM_INT);
        $stmt->bindParam(':sum_credit', $sum_credit, PDO::PARAM_INT);
        $stmt->bindParam(':advance_rent', $advance_rent, PDO::PARAM_STR);
        $stmt->bindParam(':advance_unit', $advance_unit, PDO::PARAM_INT);
        $stmt->bindParam(':sum_advance', $sum_advance, PDO::PARAM_INT);
        $stmt->bindParam(':grand_total', $grand_total, PDO::PARAM_INT);
        $stmt->execute();

     
        $_SESSION['success'] = "บันทึกข้อมูลสำเร็จ";
        header("Location: admin.php?selectadmin=9&guests=4&id=".$id);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
