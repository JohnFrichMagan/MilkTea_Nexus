<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $milk_tea_cup = intval($_POST['milk_tea_cup']);
    $milk_tea_powder = intval($_POST['milk_tea_powder']);
    $pearl = intval($_POST['pearl']);
    $milk = intval($_POST['milk']);

    $sql = "INSERT INTO ingredients_stock (product_name, milk_tea_cup, milk_tea_powder, pearl, milk) 
            VALUES ('$product_name', $milk_tea_cup, $milk_tea_powder, $pearl, $milk)";

    if (mysqli_query($conn, $sql)) {
        header("Location: ingredients_inventory.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
