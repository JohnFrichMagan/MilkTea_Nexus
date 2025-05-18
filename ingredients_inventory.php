<?php
include 'config.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch products for dropdowns
$product_sql = "SELECT product_id, product_name FROM products ORDER BY product_name ASC";
$product_result = mysqli_query($conn, $product_sql);

if (!$product_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch ingredient stocks joined with products
$stock_sql = "SELECT s.*, p.product_name 
              FROM ingredients_stock s 
              LEFT JOIN products p ON s.product_id = p.product_id
              ORDER BY s.id DESC";
$stock_result = mysqli_query($conn, $stock_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ingredients Inventory | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Styles */
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            padding: 8px 14px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #e74c3c;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }

        /* Modal Styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 100; 
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto; 
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px 30px;
            border: 1px solid #888;
            width: 400px;
            border-radius: 8px;
            position: relative;
        }
        .close {
            color: #aaa;
            position: absolute;
            right: 15px; top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
        }
        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: 600;
        }
        input[type=number], select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .modal-button {
            margin-top: 15px;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo-details">
        <i class="bx bx-coffee"></i>
        <span class="logo_name">MILKTEA NEXUS</span>
    </div>
    <ul class="nav-links">
        <li><a href="admin_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="product.php"><i class="bx bx-box"></i><span class="links_name">Product</span></a></li>
        <li><a href="ingredients_inventory.php" class="active"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Ingredients Inventory</span></a></li>
        <li><a href="financial_reports.php"><i class="bx bx-line-chart"></i><span class="links_name">Financial Reports</span></a></li>
        <li><a href="menu.php"><i class="bx bx-menu"></i><span class="links_name">Menu</span></a></li>
        <li><a href="settings.php"><i class="bx bx-cog"></i><span class="links_name">Settings</span></a></li>
        <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
</div>

<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Ingredients Inventory</span>
        </div>
        <div class="profile-details">
            <img src="images/profile.jpg" alt="" />
            <span class="admin_name">Admin</span>
            <i class="bx bx-chevron-down"></i>
        </div>
    </nav>

    <div class="home-content">
        <div class="sales-boxes">
            <div class="recent-sales box">
                <div class="title" style="display:flex; justify-content:space-between; align-items:center;">
                    <span>Ingredient Stock Levels</span>
                    <button id="addBtn">Add New Stock</button>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Milk Tea Cup</th>
                                <th>Milk Tea Powder</th>
                                <th>Pearl</th>
                                <th>Milk</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($stock_result && mysqli_num_rows($stock_result) > 0) {
                                while ($row = mysqli_fetch_assoc($stock_result)) {
                                    // Prepare data for modal in JSON format safely
                                    $dataJson = htmlspecialchars(json_encode([
                                        'id' => $row['id'],
                                        'product_id' => $row['product_id'],
                                        'milk_tea_cup' => $row['milk_tea_cup'],
                                        'milk_tea_powder' => $row['milk_tea_powder'],
                                        'pearl' => $row['pearl'],
                                        'milk' => $row['milk'],
                                    ]), ENT_QUOTES, 'UTF-8');

                                    echo "<tr>
                                        <td>" . htmlspecialchars($row['product_name'] ?? 'N/A') . "</td>
                                        <td>" . intval($row['milk_tea_cup']) . "</td>
                                        <td>" . intval($row['milk_tea_powder']) . "</td>
                                        <td>" . intval($row['pearl']) . "</td>
                                        <td>" . intval($row['milk']) . "</td>
                                        <td>
                                            <button class='editBtn' data-stock='$dataJson'>Edit</button>
                                            <form method='POST' action='delete_ingredient_stock.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");' style='display:inline-block; margin-left:5px;'>
                                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                                <button type='submit' class='delete-btn'>Delete</button>
                                            </form>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No stock records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" id="addClose">&times;</span>
        <h3>Add Ingredient Stock</h3>
        <form action="add_ingredient_stock.php" method="POST" id="addForm">
            <label for="add_product_id">Product:</label>
            <select name="product_id" id="add_product_id" required>
                <option value="" disabled selected>Select product</option>
                <?php
                // Fetch products again to ensure fresh data
                $product_result->data_seek(0); // reset pointer
                while ($prod = mysqli_fetch_assoc($product_result)) {
                    echo "<option value='" . $prod['product_id'] . "'>" . htmlspecialchars($prod['product_name']) . "</option>";
                }
                ?>
            </select>
            <label for="add_milk_tea_cup">Milk Tea Cup:</label>
            <input type="number" name="milk_tea_cup" id="add_milk_tea_cup" min="0" required>

            <label for="add_milk_tea_powder">Milk Tea Powder:</label>
            <input type="number" name="milk_tea_powder" id="add_milk_tea_powder" min="0" required>

            <label for="add_pearl">Pearl:</label>
            <input type="number" name="pearl" id="add_pearl" min="0" required>

            <label for="add_milk">Milk:</label>
            <input type="number" name="milk" id="add_milk" min="0" required>

            <button type="submit" class="modal-button">Add Stock</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" id="editClose">&times;</span>
        <h3>Edit Ingredient Stock</h3>
        <form action="edit_ingredient_stock.php" method="POST" id="editForm">
            <input type="hidden" name="id" id="edit_id" />
            <label for="edit_product_id">Product:</label>
            <select name="product_id" id="edit_product_id" required>
                <option value="" disabled>Select product</option>
                <?php
                // Reset pointer again to reuse product list for edit modal
                $product_result->data_seek(0);
                while ($prod = mysqli_fetch_assoc($product_result)) {
                    echo "<option value='" . $prod['product_id'] . "'>" . htmlspecialchars($prod['product_name']) . "</option>";
                }
                ?>
            </select>
            <label for="edit_milk_tea_cup">Milk Tea Cup:</label>
            <input type="number" name="milk_tea_cup" id="edit_milk_tea_cup" min="0" required>

            <label for="edit_milk_tea_powder">Milk Tea Powder:</label>
            <input type="number" name="milk_tea_powder" id="edit_milk_tea_powder" min="0" required>

            <label for="edit_pearl">Pearl:</label>
            <input type="number" name="pearl" id="edit_pearl" min="0" required>

            <label for="edit_milk">Milk:</label>
            <input type="number" name="milk" id="edit_milk" min="0" required>

            <button type="submit" class="modal-button">Update Stock</button>
        </form>
    </div>
</div>

<script>
    // Modal handling
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');

    const addBtn = document.getElementById('addBtn');
    const addClose = document.getElementById('addClose');
    const editClose = document.getElementById('editClose');

    // Open Add modal, clear inputs
    addBtn.onclick = () => {
        document.getElementById('addForm').reset();
        addModal.style.display = 'block';
    };

    addClose.onclick = () => {
        addModal.style.display = 'none';
    };

    editClose.onclick = () => {
        editModal.style.display = 'none';
    };

    window.onclick = (event) => {
        if (event.target === addModal) {
            addModal.style.display = 'none';
        }
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    };

    // Edit buttons click event
    const editButtons = document.querySelectorAll('.editBtn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const stockData = JSON.parse(btn.getAttribute('data-stock'));
            document.getElementById('edit_id').value = stockData.id;
            document.getElementById('edit_product_id').value = stockData.product_id;
            document.getElementById('edit_milk_tea_cup').value = stockData.milk_tea_cup;
            document.getElementById('edit_milk_tea_powder').value = stockData.milk_tea_powder;
            document.getElementById('edit_pearl').value = stockData.pearl;
            document.getElementById('edit_milk').value = stockData.milk;

            editModal.style.display = 'block';
        });
    });
</script>
<script src="script.js"></script>
</body>
</html>
