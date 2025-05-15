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

foreach ($time_frames as $time_frame) {
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
    margin: 30px auto 0; /* top margin 30px, auto left/right to center */
    background-color: #fff;
    padding: 10px;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);

    /* Editable dimensions */
    width: 100%; /* or set fixed value like 800px */
    max-width: 1000px; /* control the maximum size */
    height: auto; /* or set fixed value like 400px */
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

    <!-- Main Content -->
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">Financial Reports</span>
            </div>
            <div class="profile-details">
                <img src="images/admin.jpg" alt="Admin" />
                <span class="admin_name">Staff</span>
                <i class="bx bx-chevron-down"></i>
            </div>
        </nav>

        <div class="home-content">
            <div class="section-container">
                <div class="section-header">
                    <h2><i class="bx bx-pie-chart-alt-2"></i> Order Status Breakdown</h2>
                </div>

                <!-- Chart.js Canvas -->
                <div class="chart-container">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>

            <!-- Sales Data Table -->
            <div class="sales-table-container">
                <div class="section-header">
                    <h2><i class="bx bx-bar-chart"></i> Sales Data</h2>
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
                        <tr id="monthlyRow" style="display: none;">
                            <td>Monthly</td>
                            <td>₱<?php echo number_format($sales_data['month'], 2); ?></td>
                        </tr>
                        <tr id="yearlyRow" style="display: none;">
                            <td>Yearly</td>
                            <td>₱<?php echo number_format($sales_data['year'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        if (sidebarBtn) {
            sidebarBtn.onclick = function () {
                sidebar.classList.toggle("active");
                sidebarBtn.classList.toggle("bx-menu-alt-right");
            };
        }

        // Data for the chart
        const orderStatusData = {
            labels: ['Pending', 'Delivered', 'Completed', 'Canceled'],
            datasets: [{
                label: 'Order Statuses',
                data: [<?php echo $status_counts['Pending']; ?>, <?php echo $status_counts['Delivered']; ?>, <?php echo $status_counts['Completed']; ?>, <?php echo $status_counts['Canceled']; ?>],
                backgroundColor: ['#fbe0c3', '#d4edda', '#c3e6cb', '#f8d7da'],
                borderColor: ['#b25b00', '#155724', '#155724', '#721c24'],
                borderWidth: 1
            }]
        };

        // Config for Chart.js
        const config = {
            type: 'pie',
            data: orderStatusData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' Orders';
                            }
                        }
                    }
                }
            }
        };

        // Create the chart
        const ctx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(ctx, config);

        // Handle button click to toggle sales data visibility
        document.getElementById('weeklyBtn').addEventListener('click', function() {
            document.getElementById('weeklyRow').style.display = 'table-row';
            document.getElementById('monthlyRow').style.display = 'none';
            document.getElementById('yearlyRow').style.display = 'none';
            toggleActiveButton('weeklyBtn');
        });
        document.getElementById('monthlyBtn').addEventListener('click', function() {
            document.getElementById('weeklyRow').style.display = 'none';
            document.getElementById('monthlyRow').style.display = 'table-row';
            document.getElementById('yearlyRow').style.display = 'none';
            toggleActiveButton('monthlyBtn');
        });
        document.getElementById('yearlyBtn').addEventListener('click', function() {
            document.getElementById('weeklyRow').style.display = 'none';
            document.getElementById('monthlyRow').style.display = 'none';
            document.getElementById('yearlyRow').style.display = 'table-row';
            toggleActiveButton('yearlyBtn');
        });

        // Function to toggle active button
        function toggleActiveButton(buttonId) {
            document.querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
            document.getElementById(buttonId).classList.add('active');
        }
    </script>
</body>
</html>
