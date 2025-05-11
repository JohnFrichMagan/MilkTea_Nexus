<?php
require_once 'config.php'; // Include your database connection

// Initialize an empty array (though not strictly needed now)
$products = [];

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
        /* Existing styles */
        .sales-details {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-top: 20px;
        }

        .sales-details .details {
            list-style: none;
            min-width: 150px; /* Adjust width as needed */
            padding: 0;
        }

        .sales-details .details .topic {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .sales-details .details li {
            margin-bottom: 6px;
        }

        /* Profile icon and navigation styles */
        .profile-details {
            display: flex;
            align-items: center;
        }

        .profile-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #333;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: 8px;
        }

        .profile-icon svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
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
            <li><a href="product.php" class="active"><i class="bx bx-box"></i><span class="links_name">Product</span></a></li>
            <li><a href="analytics.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Analytics</span></a></li>
            <li><a href="financial_reports.php"><i class="bx bx-line-chart"></i><span class="links_name">Financial Reports</span></a></li>
            <li><a href="human_resource.php"><i class="bx bx-group"></i><span class="links_name">Human Resource</span></a></li>
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
                <div class="recent-sales box">
                    <div class="title">Current Product</div>
                    <div class="sales-details" id="inventoryList">
                        <?php
                        try {
                            // Fetch products from the database
                            $query = "SELECT product_name, category, price, stock_quantity FROM products";
                            $result = $conn->query($query);

                            if (!$result) {
                                throw new Exception("Database error: " . $conn->error);
                            }

                            if ($result->num_rows > 0) {
                                ?>
                                <ul class="details">
                                    <li class="topic">Product Name</li>
                                    <?php while($product = $result->fetch_assoc()): ?>
                                        <li><a href="#"><?= htmlspecialchars($product['product_name']) ?></a></li>
                                    <?php endwhile; ?>
                                </ul>
                                <ul class="details">
                                    <li class="topic">Category</li>
                                    <?php
                                        $result->data_seek(0); // Reset the result set pointer
                                        while($product = $result->fetch_assoc()):
                                    ?>
                                        <li><a href="#"><?= htmlspecialchars($product['category']) ?></a></li>
                                    <?php endwhile; ?>
                                </ul>
                                <ul class="details">
                                    <li class="topic">Price</li>
                                    <?php
                                        $result->data_seek(0); // Reset the result set pointer
                                        while($product = $result->fetch_assoc()):
                                    ?>
                                        <li><a href="#">₱<?= number_format($product['price'], 2) ?></a></li>
                                    <?php endwhile; ?>
                                </ul>
                                <ul class="details">
                                    <li class="topic">Stock</li>
                                    <?php
                                        $result->data_seek(0); // Reset the result set pointer
                                        while($product = $result->fetch_assoc()):
                                    ?>
                                        <li><a href="#"><?= intval($product['stock_quantity']) ?></a></li>
                                    <?php endwhile; ?>
                                </ul>
                                <?php
                                // Free the result set
                                $result->free();
                            } else {
                                echo '<p style="padding-left: 15px;">No products available in inventory.</p>';
                            }
                        } catch (Exception $e) {
                            // Handle any errors that occur during the query
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </div>
                    <div class="button">
                        <a href="manage_products.php">Add New Product</a>
                    </div>
                </div>
                <div class="top-sales box">
                    <div class="title">Top Selling Product (Placeholder)</div>
                    <ul class="top-sales-details">
                        <li>
                            <a href="#">
                                <img src="images/chocolateBubbleTea.jpg" alt="" />
                                <span class="product">Chocolate Bubble Tea</span>
                            </a>
                            <span class="price">₱ 100</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/boba.jpg" alt="" />
                                <span class="product">Boba Milk Tea</span>
                            </a>
                            <span class="price">₱ 95</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/purple boba.jpg" alt="" />
                                <span class="product">Purple Boba Milk Tea</span>
                            </a>
                            <span class="price">₱ 90</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/taro.jpg" alt="" />
                                <span class="product">Taro Milk Tea</span>
                            </a>
                            <span class="price">₱ 95</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/strawberry.jpg" alt="" />
                                <span class="product">Stawberry Milk Tea</span>
                            </a>
                            <span class="price">₱ 100</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/matcha.jpg" alt="" />
                                <span class="product">Match Milk Tea</span>
                            </a>
                            <span class="price">₱ 95</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Sidebar toggle (RETAINED THIS BLOCK)
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