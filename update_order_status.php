<?php
require_once 'config.php';

if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();

    // Deduct ingredients if status is 'Completed'
    if ($new_status === 'Completed') {

        // Function to deduct ingredients
        function deductIngredients($conn, $productId, $quantityOrdered) {
            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();

            $ingredients = [
                'Milk Tea Cup' => $product['milk_tea_cup'] * $quantityOrdered,
                'Powder' => $product['powder'] * $quantityOrdered,
                'Milk Tea Powder' => $product['milk_tea_powder'] * $quantityOrdered,
                'Pearl' => $product['pearl'] * $quantityOrdered,
                'Milk' => $product['milk'] * $quantityOrdered,
            ];

            foreach ($ingredients as $ingredient => $amountToDeduct) {
                $stmt = $conn->prepare("UPDATE ingredients_inventory SET quantity = quantity - ? WHERE ingredient_name = ?");
                $stmt->bind_param("is", $amountToDeduct, $ingredient);
                $stmt->execute();
            }
        }

        // Fetch products in the order
        $stmt = $conn->prepare("SELECT product_id, quantity FROM order_details WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            deductIngredients($conn, $row['product_id'], $row['quantity']);
        }

        $stmt->close();
    }

    echo "Order updated successfully.";
} else {
    echo "Missing order ID or status.";
}
?>
