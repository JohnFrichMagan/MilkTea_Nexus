<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST['product_id'];
    $milk_tea_cup = $_POST['milk_tea_cup'];
    $milk_tea_powder = $_POST['milk_tea_powder'];
    $pearl = $_POST['pearl'];
    $milk = $_POST['milk'];

    $stmt = $conn->prepare("INSERT INTO ingredients_stock 
        (product_id, milk_tea_cup, milk_tea_powder, pearl, milk) 
        VALUES (?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("iiiii", $product_id, $milk_tea_cup, $milk_tea_powder, $pearl, $milk);
        $stmt->execute();
        $stmt->close();
        header("Location: ingredients_inventory.php");
        exit();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
}
?>
