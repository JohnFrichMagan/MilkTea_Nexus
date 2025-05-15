<?php
// update_order_status.php
session_start();
require_once 'config.php';

if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Failed to update status";
    }
    $stmt->close();
} else {
    echo "Invalid request";
}
?>
