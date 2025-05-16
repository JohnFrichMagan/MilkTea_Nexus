<?php
require_once 'config.php'; // Include your database connection

// Stock update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        $update_stock_query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
        $stmt = $conn->prepare($update_stock_query);

        if ($stmt) {
            $stmt->bind_param("ii", $quantity, $product_id);
            $stmt->execute();
            $stmt->close();
            echo "<p style='color:green; padding-left: 15px;'>Stock updated successfully.</p>";
        } else {
            echo "<p style='color:red; padding-left: 15px;'>Prepare failed: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red; padding-left: 15px;'>Invalid quantity.</p>";
    }
}

// Fetch top-selling products
$top_sales_query = "
    SELECT p.product_id, p.product_name, p.price, p.image_url, COUNT(o.product_id) AS sold_count
    FROM products p
    LEFT JOIN orders o ON p.product_id = o.product_id
    GROUP BY p.product_id
    ORDER BY sold_count DESC
    LIMIT 5"; // Adjust the LIMIT to show the top N products

// Execute the query for top-selling products
$top_sales_result = $conn->query($top_sales_query);

// Fetch products for the current product inventory
$product_query = "SELECT product_id, product_name, category, price, stock_quantity FROM products";
$product_result = $conn->query($product_query);

// Handle errors for both queries
if (!$top_sales_result || !$product_result) {
    die("Failed to fetch data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>Product | MILKTEA NEXUS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <style>
        /* Refined Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 12px 20px;
            text-align: center;
            border: 1px solid #e1e1e1;
            vertical-align: middle;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            text-decoration: none;
            color: #333;
            transition: color 0.3s ease;
        }

        td a:hover {
            color: #4CAF50;
        }

        .button {
            margin-top: 20px;
            text-align: right;
        }

        .button a {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .button a:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Table container to allow scrolling on mobile */
        .table-container {
            max-width: 100%;
            overflow-x: auto;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }

            .button a {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <body>
<div class="sidebar">
    <div class="logo-details">
        <i class="bx bx-coffee"></i>
        <span class="logo_name">MILKTEA NEXUS</span>
    </div>
    <ul class="nav-links">
        <li><a href="admin_dashboard.php" class="active"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="product.php"><i class="bx bx-box"></i><span class="links_name">Product</span></a></li>
        <li><a href="ingredients_inventory.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Ingredients Inventory</span></a></li>
        <li><a href="financial_reports.php"><i class="bx bx-line-chart"></i><span class="links_name">Financial Reports</span></a></li>
        <li><a href="menu.php"><i class="bx bx-menu"></i><span class="links_name">Menu</span></a></li>
        <li><a href="settings.php"><i class="bx bx-cog"></i><span class="links_name">Setting</span></a></li>
        <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
</div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">Product Management</span>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search..." />
                <i class="bx bx-search"></i>
            </div>
            <div class="profile-details">
                <span class="admin_name">Admin</span>
                <div class="profile-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 4a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                    </svg>
                </div>
            </div> 
        </nav>

        <div class="home-content">
            <div class="sales-boxes">
                <!-- Current Product -->
                <div class="recent-sales box">
                    <div class="title">Current Product</div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock of Ingredients</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($product_result->num_rows > 0) {
                                    while ($product = $product_result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($product['product_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($product['category']) . "</td>";
                                        echo "<td>₱" . number_format($product['price'], 2) . "</td>";
                                        echo "<td>" . intval($product['stock_quantity']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No products available in inventory.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="button">
                        <a href="manage_products.php">Add New Product</a>
                    </div>
                </div>

                <div class="top-sales box">
    <div class="title">Top Selling Products</div>
    <ul class="top-sales-details">
        <?php
        if ($top_sales_result->num_rows > 0) {
            while ($row = $top_sales_result->fetch_assoc()) {
                $product_name = htmlspecialchars($row['product_name']);
                $product_price = number_format($row['price'], 2);
                $product_image = htmlspecialchars($row['image_url']); // use image_url from DB

                echo '<li>
                        <a href="#">
                            <img src="' . $product_image . '" alt="' . $product_name . '" onerror="this.src=\'default.png\'" />
                            <span class="product">' . $product_name . '</span>
                        </a>
                        <span class="price">₱ ' . $product_price . '</span>
                      </li>';
            }
        } else {
            echo "<li>No products found.</li>";
        }
        ?>
    </ul>
</div>


    </section>

    <script>
        // Sidebar toggle script
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn && sidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else {
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
            }
        });
    </script>
</body>
</html>

<?php
// Free the result sets after using them
if ($top_sales_result instanceof mysqli_result) {
    $top_sales_result->free();
}
if ($product_result instanceof mysqli_result) {
    $product_result->free();
}
?>
