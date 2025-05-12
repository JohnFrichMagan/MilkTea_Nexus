<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milktea_nexus_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .sales-details {
        overflow-x: auto;
    }

    .sales-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .sales-table th, .sales-table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }

    .sales-table th {
        background-color: #f5f5f5;
        font-weight: bold;
        color: #333;
    }

    .sales-table tr:hover {
        background-color: #f0f0f0;
    }

    .sales-table img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        background-color: #ffcc00;
        color: #333;
        font-size: 12px;
        font-weight: 600;
    }

    @media screen and (max-width: 768px) {
        .sales-table th, .sales-table td {
            padding: 8px;
            font-size: 12px;
        }
    }

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
        <li><a href="admin_dashboard.php" class="active"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="product.php"><i class="bx bx-box"></i><span class="links_name">Product</span></a></li>
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
            <span class="dashboard">Dashboard</span>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search..." />
            <i class="bx bx-search"></i>
        </div>
        <div class="profile-details">
            <span class="admin_name"><?= isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"]) : "Guest" ?></span>
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
                    <div class="indicator"><i class="bx bx-up-arrow-alt"></i><span class="text">Up from yesterday</span></div>
                </div>
                <i class="bx bx-cart-alt cart"></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Total Sales</div>
                    <div class="number">38,876</div>
                    <div class="indicator"><i class="bx bx-up-arrow-alt"></i><span class="text">Up from yesterday</span></div>
                </div>
                <i class="bx bxs-cart-add cart two"></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Total Profit</div>
                    <div class="number">₱12,876</div>
                    <div class="indicator"><i class="bx bx-up-arrow-alt"></i><span class="text">Up from yesterday</span></div>
                </div>
                <i class="bx bx-cart cart three"></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Total Return</div>
                    <div class="number">11,086</div>
                    <div class="indicator"><i class="bx bx-down-arrow-alt down"></i><span class="text">Down From Today</span></div>
                </div>
                <i class="bx bxs-cart-download cart four"></i>
            </div>
        </div>

        <div class="sales-boxes">
            <div class="recent-sales box">
                <div class="title">Recent Sales</div>
                <div class="sales-details">
                    <table class="sales-table">
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Order Date</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "
                                SELECT 
                                    o.order_date, 
                                    p.image_url, 
                                    p.product_name, 
                                    od.quantity, 
                                    od.price
                                FROM order_details od
                                JOIN orders o ON od.order_id = o.order_id
                                JOIN products p ON od.product_id = p.product_id
                                ORDER BY o.order_date DESC
                                LIMIT 5
                            ";
                            $result = $conn->query($query);
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $total_price = $row['price'] * $row['quantity'];
                                    echo "<tr>";
                                    echo "<td><img src='" . htmlspecialchars($row['image_url']) . "' style='width: 40px; height: 40px;'></td>";
                                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                                    echo "<td>" . (int)$row['quantity'] . "</td>";
                                    echo "<td>₱" . number_format($total_price, 2) . "</td>";
                                    echo "<td>Pending</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No recent sales</td></tr>";
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
                    $result = $conn->query("SELECT * FROM products ORDER BY stock_quantity DESC LIMIT 5");
                    while ($product = $result->fetch_assoc()) {
                        echo "<li>";
                        echo "<a href='#'>";
                        echo "<img src='" . htmlspecialchars($product['image_url']) . "' alt='' />";
                        echo "<span class='product'>" . htmlspecialchars($product['product_name']) . "</span>";
                        echo "</a>";
                        echo "<span class='price'>₱" . number_format($product['price'], 2) . "</span>";
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
        sidebarBtn.classList.toggle("bx-menu-alt-right");
    };
</script>
</body>
</html>

<?php $conn->close(); ?>
