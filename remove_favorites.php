<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['favorite_id'])) {
    header("Location: user_favorites.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$favorite_id = intval($_GET['favorite_id']);

$stmt = $conn->prepare("DELETE FROM favorites WHERE favorite_id = ? AND user_id = ?");
$stmt->bind_param("ii", $favorite_id, $user_id);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: user_favorites.php");
exit();
