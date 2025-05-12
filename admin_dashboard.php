<?php
// Start the session to get the admin's details
session_start();

// Database connection (update with your connection details)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milktea_nexus_db"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch product and order details
$sql = "SELECT 
            p.product_id, 
            p.product_name, 
            p.category, 
            p.price AS product_price, 
            p.stock_quantity, 
            p.image_url, 
            od.quantity AS order_quantity, 
            od.price AS order_price, 
            o.order_date, 
            u.username, 
            u.email
        FROM 
            order_details od
        JOIN 
            orders o ON od.order_id = o.order_id
        JOIN 
            users u ON o.user_id = u.id
        JOIN 
            products p ON od.product_id = p.product_id
        ORDER BY 
            o.order_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>Responsive Admin Dashboard | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
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
            <li>
                <a href="admin_dashboard.php" class="active">
                    <i class="bx bx-grid-alt"></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="product.php">
                    <i class="bx bx-box"></i>
                    <span class="links_name">Product</span>
                </a>
            </li>
            <li>
                <a href="analytics.php">
                    <i class="bx bx-pie-chart-alt-2"></i>
                    <span class="links_name">Analytics</span>
                </a>
            </li>
            <li>
                <a href="financial_reports.php">
                    <i class="bx bx-line-chart"></i>
                    <span class="links_name">Financial Reports</span>
                </a>
            </li>
            <li>
                <a href="human_resource.php">
                    <i class="bx bx-group"></i>
                    <span class="links_name">Human Resource</span>
                </a>
            </li>
            <li>
                <a href="menu.php">
                    <i class="bx bx-menu"></i>
                    <span class="links_name">Menu</span>
                </a>
            </li>
            <li>
                <a href="settings.php">
                    <i class="bx bx-cog"></i>
                    <span class="links_name">Setting</span>
                </a>
            </li>
            <li class="log_out">
                <a href="index.php">
                    <i class="bx bx-log-out"></i>
                    <span class="links_name">Log out</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">Dashboard</span>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search..." />
                <i class="bx bx-search"></i>
            </div>
            <div class="profile-details">
                <?php if (isset($_SESSION["email"])): ?>
                    <span class="admin_name"><?php echo htmlspecialchars($_SESSION["email"]); ?></span>
                <?php else: ?>
                    <span class="admin_name">Guest</span>
                <?php endif; ?>
                <div class="profile-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 4a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                    </svg>
                </div>
            </div>
        </nav>

        <div class="home-content">
            <div class="overview-boxes">
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Total Order</div>
                        <div class="number">40,876</div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">Up from yesterday</span>
                        </div>
                    </div>
                    <i class="bx bx-cart-alt cart"></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Total Sales</div>
                        <div class="number">38,876</div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">Up from yesterday</span>
                        </div>
                    </div>
                    <i class="bx bxs-cart-add cart two"></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Total Profit</div>
                        <div class="number">$12,876</div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">Up from yesterday</span>
                        </div>
                    </div>
                    <i class="bx bx-cart cart three"></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Total Return</div>
                        <div class="number">11,086</div>
                        <div class="indicator">
                            <i class="bx bx-down-arrow-alt down"></i>
                            <span class="text">Down From Today</span>
                        </div>
                    </div>
                    <i class="bx bxs-cart-download cart four"></i>
                </div>
            </div>
<div class="sales-boxes">
    <div class="recent-sales box">
        <div class="title">Recent Sales</div>
        <div class="sales-details">
            <!-- Table to display the recent sales -->
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Order Date</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // SQL query to get recent sales with product details
                    $query = "
                        SELECT od.order_date, p.image_url, p.product_name, od.price
                        FROM order_details od
                        JOIN products p ON od.product_id = p.product_id
                        ORDER BY od.order_date DESC
                        LIMIT 5
                    ";

                    if ($result = $conn->query($query)) {
                        // Check if there are any rows
                        if ($result->num_rows > 0) {
                            // Loop through the rows and display the details
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><img src='" . $row['image_url'] . "' alt='Product Image' style='width: 40px; height: 40px;'></td>";
                                echo "<td>" . $row['product_name'] . "</td>";
                                echo "<td>" . $row['order_date'] . "</td>";
                                echo "<td>₱" . number_format($row['price'], 2) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No recent sales</td></tr>";
                        }
                    } else {
                        // If the query failed, display an error message
                        echo "<tr><td colspan='4'>Error in query: " . $conn->error . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


                <div class="top-sales box">
                    <div class="title">Top Selling Product</div>
                    <ul class="top-sales-details">
                        <?php
                        // Loop through products to display top-selling items (you may need to adjust this logic)
                        $result = $conn->query("SELECT * FROM products ORDER BY stock_quantity DESC LIMIT 5");
                        while ($product = $result->fetch_assoc()) {
                            echo "<li>";
                            echo "<a href='#'>";
                            echo "<img src='" . $product['image_url'] . "' alt='' />";
                            echo "<span class='product'>" . $product['product_name'] . "</span>";
                            echo "</a>";
                            echo "<span class='price'>₱" . $product['price'] . "</span>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else {
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
            }
        };
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
