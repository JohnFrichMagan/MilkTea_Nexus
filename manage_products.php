<?php
require_once 'config.php';

$editingProduct = null;

// Handle Delete
if (isset($_GET['delete'])) {
    $idToDelete = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE product_id = $idToDelete");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Edit (Fetch product)
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM products WHERE product_id = $id");
    $editingProduct = $result->fetch_assoc();
}

// Handle Add or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['productName'];
$lowerName = strtolower($productName);

// Auto-detect category
if (strpos($lowerName, 'latte') !== false) {
    $category = 'Latte';
} elseif (strpos($lowerName, 'milk tea') !== false || strpos($lowerName, 'milktea') !== false) {
    $category = 'Milk Tea';
} elseif (strpos($lowerName, 'smoothie') !== false) {
    $category = 'Smoothie';
} elseif (strpos($lowerName, 'coffee') !== false) {
    $category = 'Coffee';
} else {
    $category = $_POST['category'];
}

    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $imageUrl = $_POST['imageUrl']; // Get image URL

    if (!empty($_POST['productId'])) {
        $productId = intval($_POST['productId']);
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, category = ?, price = ?, stock_quantity = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("ssdiis", $productName, $category, $price, $stock, $imageUrl, $productId);
    } else {
        $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $productName, $category, $price, $stock, $imageUrl);
    }

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch all products
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stocks Management | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #E2AD7E;
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
            background-color: #FFDCC1;;
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
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #ffffff;;
            color: #333;
        }

        td {
            color: #555;
        }
        td a {
            text-decoration: none;
            color: #6c5ce7;
            font-size: 18px;
        }

        td a:hover {
            color: #d63031;
        }

        .back-btn {
    background-color: #dfe6e9;
    color: #2d3436;
    padding: 15px 30px;
    font-size: 16px;
    border-radius: 6px;
    text-decoration: none;
    margin-right: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color: #b2bec3;
}

    </style>
</head>
<body>

<div class="home-content">
    <div class="center-btn">
    <a href="product.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back to Products
    </a>

    <button id="addProductBtn" class="add-product-btn">
        <?= $editingProduct ? 'Edit Product' : 'Add Product' ?>
    </button>
</div>


    <!-- Add/Edit Product Form -->
    <div class="add-product" id="addProductForm">
        <button class="close-btn" id="closeFormBtn" title="Close Form">✖</button>
        <h3 class="form-title"><?= $editingProduct ? 'Edit Product' : 'Add New Menu Item' ?></h3>
        <form action="" method="POST" class="product-form">
            <input type="hidden" name="productId" value="<?= $editingProduct['id'] ?? '' ?>">
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="productName" required value="<?= $editingProduct['product_name'] ?? '' ?>" />
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <?php
                    $categories = ['Milk Tea', 'Latte', 'Smoothie', 'Coffee'];
                    foreach ($categories as $cat) {
                        $selected = ($editingProduct['category'] ?? '') === $cat ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>$cat</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price (₱)</label>
                <input type="number" id="price" name="price" step="0.01" required value="<?= $editingProduct['price'] ?? '' ?>" />
            </div>
            <div class="form-group">
                <label for="stock">Stock of Ingredients</label>
                <input type="number" id="stock" name="stock" required value="<?= $editingProduct['stock_quantity'] ?? '' ?>" />
            </div>
            <div class="form-group">
                <label for="imageUrl">Product Image (URL)</label>
                <input type="url" id="imageUrl" name="imageUrl" value="<?= $editingProduct['image_url'] ?? '' ?>" />
            </div>
            <button type="submit" class="btn-save"><?= $editingProduct ? 'Update Product' : 'Add Menu Item' ?></button>
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
                    <th>Stock Of Indredients</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_id'] ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['stock_quantity'] ?></td>
                        <td>
                            <?php if (!empty($row['image_url'])): ?>
                                <img src="<?= $row['image_url'] ?>" alt="Product Image" width="100" />
                            <?php else: ?>
                                No Image Available
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="?edit=<?= $row['product_id'] ?>" title="Edit" style="margin-right: 10px; font-size: 18px;">✏️</a>
                            <a href="?delete=<?= $row['product_id'] ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this product?');" style="font-size: 18px;">🗑️</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($editingProduct): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('addProductForm').style.display = 'block';
    });
</script>
<?php endif; ?>

<script>
    document.getElementById('addProductBtn').addEventListener('click', function () {
        document.getElementById('addProductForm').style.display = 'block';
    });
    document.getElementById('closeFormBtn').addEventListener('click', function () {
        document.getElementById('addProductForm').style.display = 'none';
    });

    document.getElementById('productName').addEventListener('input', function () {
        const name = this.value.toLowerCase();
        const categorySelect = document.getElementById('category');

        if (name.includes('latte')) {
            categorySelect.value = 'Latte';
        } else if (name.includes('milk tea') || name.includes('milktea')) {
            categorySelect.value = 'Milk Tea';
        } else if (name.includes('smoothie')) {
            categorySelect.value = 'Smoothie';
        } else if (name.includes('coffee')) {
            categorySelect.value = 'Coffee';
        }
    });
</script>

</body>
</html>

