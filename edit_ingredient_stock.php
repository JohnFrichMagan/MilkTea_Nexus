<?php
include 'config.php';

$row = null;

// Fetch ingredient data if id is provided via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare and execute select statement safely
    $stmt = $conn->prepare("SELECT * FROM ingredients_stock WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission to update the stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $id = intval($_POST['id']);
    $milk_tea_cup = intval($_POST['milk_tea_cup']);
    $milk_tea_powder = intval($_POST['milk_tea_powder']);
    $pearl = intval($_POST['pearl']);
    $milk = intval($_POST['milk']);

    // Prepare update statement
    $stmt = $conn->prepare("UPDATE ingredients_stock SET milk_tea_cup = ?, milk_tea_powder = ?, pearl = ?, milk = ? WHERE id = ?");
    $stmt->bind_param("iiiii", $milk_tea_cup, $milk_tea_powder, $pearl, $milk, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ingredients_inventory.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Ingredient Stock</title>
</head>
<body>
    <h2>Edit Ingredient Stock</h2>

    <?php if ($row): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
        <label>Milk Tea Cup: 
            <input type="number" name="milk_tea_cup" value="<?= htmlspecialchars($row['milk_tea_cup']) ?>" required>
        </label><br><br>
        <label>Milk Tea Powder: 
            <input type="number" name="milk_tea_powder" value="<?= htmlspecialchars($row['milk_tea_powder']) ?>" required>
        </label><br><br>
        <label>Pearl: 
            <input type="number" name="pearl" value="<?= htmlspecialchars($row['pearl']) ?>" required>
        </label><br><br>
        <label>Milk: 
            <input type="number" name="milk" value="<?= htmlspecialchars($row['milk']) ?>" required>
        </label><br><br>
        <button type="submit">Update</button>
        <a href="ingredients_inventory.php">Cancel</a>
    </form>
    <?php else: ?>
        <p>Ingredient not found.</p>
        <a href="ingredients_inventory.php">Back to Inventory</a>
    <?php endif; ?>
</body>
</html>
