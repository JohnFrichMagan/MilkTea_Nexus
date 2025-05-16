<?php
session_start();
require_once 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch order statuses from the database
$query = "SELECT status, COUNT(*) AS count FROM orders GROUP BY status";
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

// Store the counts in an associative array
$status_counts = [
    'Pending' => 0,
    'Delivered' => 0,
    'Completed' => 0,
    'Canceled' => 0
];

while ($row = $result->fetch_assoc()) {
    $status_counts[$row['status']] = $row['count'];
}

// Query for sales data for weekly, monthly, and yearly totals
$time_frames = ['week', 'month', 'year'];
$sales_data = [];
$product_sales = []; // Will hold product-wise sales per timeframe

foreach ($time_frames as $time_frame) {
    // Total sales summary
    $query = "SELECT SUM(od.quantity * p.price) AS total_sales
              FROM orders o
              JOIN order_details od ON o.order_id = od.order_id
              JOIN products p ON od.product_id = p.product_id
              WHERE o.status = 'Completed' AND DATE(o.order_date) >= CURDATE() - INTERVAL 1 $time_frame";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $sales_data[$time_frame] = $row['total_sales'] ?: 0;
    } else {
        $sales_data[$time_frame] = 0;
    }

    // Product-wise sales detail for this timeframe
    $query_products = "SELECT p.product_id, p.product_name, SUM(od.quantity) AS total_quantity, SUM(od.quantity * p.price) AS total_sales
                       FROM orders o
                       JOIN order_details od ON o.order_id = od.order_id
                       JOIN products p ON od.product_id = p.product_id
                       WHERE o.status = 'Completed' AND DATE(o.order_date) >= CURDATE() - INTERVAL 1 $time_frame
                       GROUP BY p.product_id, p.product_name
                       ORDER BY total_sales DESC";
    $result_products = $conn->query($query_products);

    $product_sales[$time_frame] = [];
    if ($result_products) {
        while ($row = $result_products->fetch_assoc()) {
            $product_sales[$time_frame][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Financial Reports | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Your existing styles... */
        .section-container {
            background-color: #fff;
            margin: 30px 20px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .section-header {
            margin-bottom: 15px;
            border-bottom: 2px solid #C1834C;
            padding-bottom: 10px;
        }
        .section-header h2 {
            font-size: 1.4rem;
            color: #4B3A2F;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
        }
        .sales-table-container {
            margin: 30px auto 0;
            background-color: #fff;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px;
            height: auto;
            overflow-x: auto;
            overflow-y: auto;
        }
        .sales-table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .sales-table-container th, .sales-table-container td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .sales-table-container th {
            background-color: #C1834C;
            color: #fff;
        }
        .sales-table-container tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .sales-table-container tr:hover {
            background-color: #f1f1f1;
        }
        .btn-group button {
            padding: 10px 20px;
            border: none;
            background-color: #C1834C;
            color: white;
            cursor: pointer;
            border-radius: 8px;
            margin-right: 8px;
        }
        .btn-group button.active {
            background-color: #4B3A2F;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-details">
            <i class="bx bx-coffee"></i>
            <span class="logo_name">MILKTEA NEXUS</span>
        </div>
        <ul class="nav-links">
            <li><a href="staff_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
            <li><a href="staff_financial_reports.php" class="active"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Financial Reports</span></a></li>
            <li><a href="staff_customer_orders.php"><i class="bx bx-cart"></i><span class="links_name">Customer Orders</span></a></li>
            <li><a href="staff_settings.php"><i class="bx bx-cog"></i><span class="links_name">Settings</span></a></li>
            <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">Financial Reports</span>
            </div>
            <div class="profile-details">
                <img src="images/admin.jpg" alt="Staff" />
                <span class="admin_name">Staff</span>
                <i class="bx bx-chevron-down"></i>
            </div>
        </nav>

        <div class="home-content">
            <!-- Order Status Chart -->
            <div class="section-container">
                <div class="section-header">
                    <h2><i class="bx bx-pie-chart-alt-2"></i> Order Status Breakdown</h2>
                </div>
                <div class="chart-container">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>

            <!-- Sales Data Summary -->
            <div class="sales-table-container">
                <div class="section-header">
                    <h2><i class="bx bx-bar-chart"></i> Sales Data Summary</h2>
                </div>
                <div class="btn-group">
                    <button id="weeklyBtn" class="active">Weekly</button>
                    <button id="monthlyBtn">Monthly</button>
                    <button id="yearlyBtn">Yearly</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Time Period</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="weeklyRow">
                            <td>Weekly</td>
                            <td>₱<?php echo number_format($sales_data['week'], 2); ?></td>
                        </tr>
                        <tr id="monthlyRow" style="display:none;">
                            <td>Monthly</td>
                            <td>₱<?php echo number_format($sales_data['month'], 2); ?></td>
                        </tr>
                        <tr id="yearlyRow" style="display:none;">
                            <td>Yearly</td>
                            <td>₱<?php echo number_format($sales_data['year'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Product Sales Details -->
            <div class="sales-table-container">
                <div class="section-header">
                    <h2><i class="bx bx-package"></i> Product Sales Details</h2>
                </div>

                <!-- Weekly Product Sales Table -->
                <table id="weeklyProductTable">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                            <th>Total Sales (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($product_sales['week'] as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo number_format($product['total_quantity']); ?></td>
                            <td>₱<?php echo number_format($product['total_sales'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($product_sales['week'])): ?>
                        <tr><td colspan="4">No product sales data for this period.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Monthly Product Sales Table -->
                <table id="monthlyProductTable" style="display:none;">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                            <th>Total Sales (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($product_sales['month'] as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo number_format($product['total_quantity']); ?></td>
                            <td>₱<?php echo number_format($product['total_sales'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($product_sales['month'])): ?>
                        <tr><td colspan="4">No product sales data for this period.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Yearly Product Sales Table -->
                <table id="yearlyProductTable" style="display:none;">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                            <th>Total Sales (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($product_sales['year'] as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo number_format($product['total_quantity']); ?></td>
                            <td>₱<?php echo number_format($product['total_sales'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($product_sales['year'])): ?>
                        <tr><td colspan="4">No product sales data for this period.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        // Sidebar toggle script from your code
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        };

        // Chart.js pie chart for order statuses
        const ctx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Delivered', 'Completed', 'Canceled'],
                datasets: [{
                    label: 'Order Status',
                    data: [
                        <?php echo $status_counts['Pending']; ?>,
                        <?php echo $status_counts['Delivered']; ?>,
                        <?php echo $status_counts['Completed']; ?>,
                        <?php echo $status_counts['Canceled']; ?>
                    ],
                    backgroundColor: ['#F4A261', '#2A9D8F', '#E9C46A', '#E76F51'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                }
            }
        });

        // Button toggle for sales summary and product details tables
        const weeklyBtn = document.getElementById('weeklyBtn');
        const monthlyBtn = document.getElementById('monthlyBtn');
        const yearlyBtn = document.getElementById('yearlyBtn');

        // Rows in summary table
        const weeklyRow = document.getElementById('weeklyRow');
        const monthlyRow = document.getElementById('monthlyRow');
        const yearlyRow = document.getElementById('yearlyRow');

        // Product tables
        const weeklyProductTable = document.getElementById('weeklyProductTable');
        const monthlyProductTable = document.getElementById('monthlyProductTable');
        const yearlyProductTable = document.getElementById('yearlyProductTable');

        function clearActive() {
            weeklyBtn.classList.remove('active');
            monthlyBtn.classList.remove('active');
            yearlyBtn.classList.remove('active');
        }

        function showTimeframe(timeframe) {
            // Summary rows visibility
            weeklyRow.style.display = (timeframe === 'week') ? '' : 'none';
            monthlyRow.style.display = (timeframe === 'month') ? '' : 'none';
            yearlyRow.style.display = (timeframe === 'year') ? '' : 'none';

            // Product tables visibility
            weeklyProductTable.style.display = (timeframe === 'week') ? '' : 'none';
            monthlyProductTable.style.display = (timeframe === 'month') ? '' : 'none';
            yearlyProductTable.style.display = (timeframe === 'year') ? '' : 'none';
        }

        weeklyBtn.addEventListener('click', () => {
            clearActive();
            weeklyBtn.classList.add('active');
            showTimeframe('week');
        });

        monthlyBtn.addEventListener('click', () => {
            clearActive();
            monthlyBtn.classList.add('active');
            showTimeframe('month');
        });

        yearlyBtn.addEventListener('click', () => {
            clearActive();
            yearlyBtn.classList.add('active');
            showTimeframe('year');
        });
    </script>
</body>
</html>
