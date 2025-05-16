<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM ingredients_stock WHERE id = $id";
    mysqli_query($conn, $sql);
}
header("Location: ingredients_inventory.php");
exit();
