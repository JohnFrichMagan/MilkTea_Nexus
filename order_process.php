<?php
include 'config.php';

$product_id = 1; // sample product id
$order_quantity = 1; // sample order quantity

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    $new_cup = $product['milk_tea_cup'] - ($product['cup_deduction'] * $order_quantity);
    $new_powder = $product['powder'] - ($product['powder_deduction'] * $order_quantity);
    $new_pearl = $product['pearl'] - ($product['pearl_deduction'] * $order_quantity);
    $new_milk = $product['milk'] - ($product['milk_deduction'] * $order_quantity);

    $update_sql = "UPDATE products SET milk_tea_cup=?, powder=?, pearl=?, milk=? WHERE product_id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iiiii", $new_cup, $new_powder, $new_pearl, $new_milk, $product_id);
    $update_stmt->execute();

    echo "Stock deducted successfully!";
} else {
    echo "Product not found.";
}
?>
