<?php
require_once 'config.php';

// Fetch all products
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stocks Management | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .home-content {
            margin: 40px;
        }

        .center-btn {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 30px;
        }

        .add-product-btn {
            background-color: #6c5ce7;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-product-btn:hover {
            background-color: #4e38c1;
        }

        .add-product {
            display: none;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            max-width: 600px;
            margin: 20px auto;
            position: relative;
        }

        .add-product .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            background-color: transparent;
            border: none;
            color: #6c5ce7;
            cursor: pointer;
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .product-form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .btn-save {
            background-color: #6c5ce7;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #4e38c1;
        }

        /* Table Styling */
        .table-container {
            margin-top: 30px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
            color: #333;
        }

        td {
            color: #555;
        }
    </style>
</head>
<body>

<div class="home-content">
    <!-- Centered Add Product Button -->
    <div class="center-btn">
        <button id="addProductBtn" class="add-product-btn">Add Product</button>
    </div>

    <!-- Add Product Form -->
    <div class="add-product" id="addProductForm">
        <!-- X Close Button -->
        <button class="close-btn" id="closeFormBtn" title="Close Form">✖</button>

        <h3 class="form-title">Add New Menu Item</h3>
        <form action="" method="POST" class="product-form">
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="productName" required placeholder="Enter product name" />
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="Milk Tea">Milk Tea</option>
                    <option value="Latte">Latte</option>
                    <option value="Smoothie">Smoothie</option>
                    <option value="Coffee">Coffee</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price (₱)</label>
                <input type="number" id="price" name="price" required placeholder="Enter price" />
            </div>
            <div class="form-group">
                <label for="stock">Stock Quantity</label>
                <input type="number" id="stock" name="stock" required placeholder="Enter stock quantity" />
            </div>
            <button type="submit" class="btn-save">Add Menu Item</button>
        </form>
    </div>

    <!-- Display Products -->
    <div class="table-container">
        <h3>Product Inventory</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price (₱)</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['stock'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Show the form when Add Product button is clicked
    document.getElementById('addProductBtn').addEventListener('click', function () {
        document.getElementById('addProductForm').style.display = 'block';
    });

    // Close the form when the Close button is clicked
    document.getElementById('closeFormBtn').addEventListener('click', function () {
        document.getElementById('addProductForm').style.display = 'none';
    });
</script>

</body>
</html>
