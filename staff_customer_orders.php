<?php
session_start();
require_once 'config.php';

// Ensure the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from the database
$query = "SELECT o.order_id, o.order_date, p.product_name, p.image_url, od.quantity, p.price, 
          (od.quantity * p.price) AS total_price, o.status
          FROM orders o
          JOIN order_details od ON o.order_id = od.order_id
          JOIN products p ON od.product_id = p.product_id";

$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

// Fetch the results and store them in an array
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    echo "No orders found.";
}
// Update order status
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status']; // e.g., 'Delivered', 'Cancelled'

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Customer Orders | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        
        .profile-details {
            display: flex;
            justify-content: flex-end;
            padding-right: 20px;
        }
        .profile-details img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }
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
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            border-radius: 8px;
            overflow: hidden;
        }
        .styled-table thead tr {
            background-color: #C1834C;
            color: #fff;
            text-align: left;
        }
        .styled-table th, .styled-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
        }
        .styled-table tbody tr:hover {
            background-color: #f6f6f6;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        .status {
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.85rem;
            display: inline-block;
        }
        .status.pending {
            background-color: #fbe0c3;
            color: #b25b00;
        }
        .no-orders-message {
            font-size: 1rem;
            color: #999;
            padding: 15px;
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
            <li><a href="staff_financial_report.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Financial Reports</span></a></li>
            <li><a href="staff_customer_orders.php" class="active"><i class="bx bx-cart"></i><span class="links_name">Customer Orders</span></a></li>
            <li><a href="staff_settings.php"><i class="bx bx-cog"></i><span class="links_name">Settings</span></a></li>
            <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">Customer Orders</span>
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
                    <h2><i class="bx bx-list-ul"></i> Customer Orders</h2>
                </div>

                <?php if (empty($orders)): ?>
                    <p class="no-orders-message">No orders found.</p>
                <?php else: ?>
                    <div class="table-wrapper">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                        <td><img src="<?php echo htmlspecialchars($order['image_url']); ?>" alt="Product Image" class="product-image" /></td>
                                        <td><?php echo $order['quantity']; ?></td>
                                        <td>₱<?php echo number_format($order['price'], 2); ?></td>
                                        <td>₱<?php echo number_format($order['total_price'], 2); ?></td>
                                        <td>
    <select class="status-dropdown" data-order-id="<?php echo $order['order_id']; ?>">
        <?php
        $statuses = ['Pending', 'Delivered', 'Completed', 'Canceled'];
        foreach ($statuses as $status) {
            $selected = $order['status'] === $status ? 'selected' : '';
            echo "<option value='$status' $selected>$status</option>";
        }
        ?>
    </select>
</td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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

        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
    dropdown.addEventListener('change', function () {
        const orderId = this.dataset.orderId;
        const newStatus = this.value;

        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${newStatus}`
        })
        .then(response => response.text())
        .then(data => {
            console.log('Status updated:', data);
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });
    });
});
    </script>
</body>
</html>
