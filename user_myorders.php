<?php
session_start();
require_once 'config.php';

// Ensure that the user is logged in before fetching their orders
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

try {
$sql = "SELECT od.detail_id, od.order_id, od.product_id, od.quantity, 
             p.product_name, p.price, p.image_url, o.order_date
     FROM order_details od
     JOIN orders o ON od.order_id = o.order_id
     JOIN products p ON od.product_id = p.product_id
     WHERE o.user_id = ?
     ORDER BY o.order_date DESC";


    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Show actual error
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    $error_message = "Error fetching orders: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>My Orders | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .order-list-container {
            margin: 20px auto;
            max-width: 800px;
            padding: 20px;
            background-color: #E2AD7E;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-item {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
        }

        .order-item-details {
            flex: 1;
            margin-right: 10px;
        }

        .order-item-details p {
            margin: 5px 0;
            font-size: 1rem;
            color: #0D0C0C;
        }

        .order-item-price {
            font-weight: bold;
            font-size: 1.2rem;
            color: #0D0C0C;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .order-header h2 {
            margin: 0;
            color: #0D0C0C;
            font-size: 1.5rem;
        }

        .no-orders-message {
            text-align: center;
            font-size: 1rem;
            color: #555;
        }

        .order-item-details strong {
            color: #0D0C0C;
        }

        .order-summary-card {
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .order-summary-card h3 {
            margin-bottom: 15px;
            font-size: 1.2rem;
            color: #0D0C0C;
        }

        .order-summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-summary-table th,
        .order-summary-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
            color: #0D0C0C;
        }

        .order-summary-table th {
            background-color: #E2AD7E;
            color: #fff;
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
            <a href="user_dashboard.php">
                <i class="bx bx-grid-alt"></i>
                <span class="links_name">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="user_order.php">
                <i class="bx bx-box"></i>
                <span class="links_name">Order Milk Tea</span>
            </a>
        </li>
        <li>
            <a href="user_myorders.php" class="active">
                <i class="bx bx-pie-chart-alt-2"></i>
                <span class="links_name">My Orders</span>
            </a>
        </li>
        <li>
            <a href="user_favorites.php">
                <i class="bx bx-line-chart"></i>
                <span class="links_name">Favorites</span>
            </a>
        </li>
        <li>
            <a href="user_settings.php">
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
            <span class="dashboard">My Orders</span>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search..." />
            <i class="bx bx-search"></i>
        </div>
        <div class="profile-details">
            <?php if (isset($_SESSION["user_email"])): ?>
                <span class="user_name"><?php echo htmlspecialchars($_SESSION["user_email"]); ?></span>
            <?php else: ?>
                <span class="user_name">Guest</span>
            <?php endif; ?>
            <div class="profile-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 4a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                </svg>
            </div>
        </div>
    </nav>

<div class="home-content">
    <div class="order-list-container">
        <div class="order-header">
            <h2>My Orders</h2>
        </div>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php elseif (empty($orders)): ?>
            <p class="no-orders-message">No orders found.</p>
        <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <div class="order-item-details">
                            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
                            <p><strong>Order Date:</strong> <?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($order['order_date']))); ?></p>
                            <p><strong>Product Name:</strong> <?php echo htmlspecialchars($order['product_name']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                            <p><strong>Price:</strong> ₱<?php echo number_format($order['price'], 2); ?></p>
                            
                            <!-- Display the product image below the product name -->
                            <p><strong>Product Image:</strong></p>
                            <img src="<?php echo isset($order['image_url']) ? htmlspecialchars($order['image_url']) : 'default_image.jpg'; ?>" alt="Product Image" width="80" height="80" />
                        </div>

                    </div>
                <?php endforeach; ?>


            <!-- Order Summary Table Card -->
            <div class="order-summary-card">
                <h3>Order Summary</h3>
                <table class="order-summary-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Date Ordered</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo htmlspecialchars(date('M d, Y', strtotime($order['order_date']))); ?></td>
                                <td>₱<?php echo number_format($order['price'], 2); ?></td>
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
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        };
    }
</script>
</body>
</html>
