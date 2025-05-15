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

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters
$status = isset($_GET['status']) ? trim($conn->real_escape_string($_GET['status'])) : '';
$product = isset($_GET['product']) ? trim($conn->real_escape_string($_GET['product'])) : '';
$start_date = isset($_GET['start_date']) ? trim($conn->real_escape_string($_GET['start_date'])) : '';
$end_date = isset($_GET['end_date']) ? trim($conn->real_escape_string($_GET['end_date'])) : '';

// WHERE clause
$where = "WHERE 1=1";
if (!empty($status)) $where .= " AND o.status = '$status'";
if (!empty($product)) $where .= " AND p.product_name LIKE '%$product%'";
if (!empty($start_date)) $where .= " AND o.order_date >= '$start_date'";
if (!empty($end_date)) $where .= " AND o.order_date <= '$end_date 23:59:59'";

// Total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM order_details od
              JOIN orders o ON od.order_id = o.order_id
              JOIN products p ON od.product_id = p.product_id
              $where";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch orders
$sql = "SELECT o.order_date, p.image_url, p.product_name, od.quantity, od.price, o.status
        FROM order_details od
        JOIN orders o ON od.order_id = o.order_id
        JOIN products p ON od.product_id = p.product_id
        $where
        ORDER BY o.order_date DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders | MILKTEA NEXUS</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f0f2f5;
        padding: 40px;
        color: #333;
    }

    .back-btn {
        display: inline-block;
        margin-bottom: 20px;
        background-color: #e0e0e0;
        color: #333;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s background;
    }

    .back-btn:hover {
        background-color: #d5d5d5;
    }

    h2 {
        margin-bottom: 25px;
        font-size: 28px;
        color: #2c3e50;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 30px;
    }

    .filter-form input,
    .filter-form select {
        padding: 10px 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: white;
        font-size: 14px;
        transition: 0.2s border ease;
    }

    .filter-form input:focus,
    .filter-form select:focus {
        border-color: #2196F3;
        outline: none;
        box-shadow: 0 0 5px rgba(33, 150, 243, 0.3);
    }

    .filter-form button {
        background-color: #2196F3;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        cursor: pointer;
        transition: 0.3s background;
    }

    .filter-form button:hover {
        background-color: #1976D2;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    thead {
        background-color: #f8f9fa;
    }

    th, td {
        padding: 16px;
        text-align: center;
        font-size: 14px;
    }

    tbody tr:nth-child(even) {
        background-color: #f9fbfd;
    }

    tbody tr:hover {
        background-color: #eef3f9;
    }

    th {
        font-weight: 600;
        color: #34495e;
    }

    td img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }

    .pagination {
        margin-top: 30px;
        text-align: center;
    }

    .pagination a {
        margin: 0 5px;
        padding: 10px 16px;
        background-color: #eee;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        transition: 0.2s;
    }

    .pagination a.active {
        background-color: #2196F3;
        color: white;
    }

    .pagination a:hover:not(.active) {
        background-color: #ddd;
    }

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
        }

        th, td {
            padding: 12px 10px;
        }

        .filter-form input,
        .filter-form select,
        .filter-form button {
            width: 100%;
        }
    }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
<h2>All Orders</h2>

<form method="GET" class="filter-form">
    <input type="text" name="product" placeholder="Product Name" value="<?= htmlspecialchars($product) ?>">
    <select name="status">
        <option value="">All Status</option>
        <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Delivered" <?= $status === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
        <option value="Completed" <?= $status === 'Completed' ? 'selected' : '' ?>>Completed</option>
        <option value="Cancelled" <?= $status === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>
    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
    <button type="submit">Filter</button>
</form>

<table>
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
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image"></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= (int)$row['quantity'] ?></td>
                    <td>₱<?= number_format($row['quantity'] * $row['price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No orders found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="<?= $page == $i ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
