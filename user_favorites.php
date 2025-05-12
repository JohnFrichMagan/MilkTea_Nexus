<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT f.favorite_id, f.date_added, p.product_name, p.price, p.image_url
            FROM favorites f
            JOIN products p ON f.product_id = p.product_id
            WHERE f.user_id = ?
            ORDER BY f.date_added DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $favorites = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    $error_message = "Error fetching favorites: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>My Favorites | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .section-container {
            background-color: #fff;
            margin: 30px 20px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .section-header h2 {
            font-size: 1.4rem;
            color: #4B3A2F;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .table-wrapper {
            overflow-x: auto;
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
        .no-favorites-message {
            font-size: 1rem;
            color: #999;
            padding: 15px;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
        a.remove-link {
    color: #C0392B;
    font-weight: bold;
    text-decoration: none;
}

a.remove-link:hover {
    text-decoration: underline;
}
   .profile-details {
    display: flex; /* Enable flexbox for centering */
    justify-content: center; /* Center content horizontally */
    align-items: center; /* Center content vertically */
    /* Add any other styling for the container if needed */
    padding: 10px; /* Example padding */
    background-color: #f0f0f0; /* Example background color */
    border-radius: 5px; /* Example border radius */
}

.user-info {
    display: flex; /* Enable flexbox for name and icon */
    align-items: center; /* Align name and icon vertically */
}

.user_name {
    margin-right: 8px; /* Add some space between the name and the icon */
}

.profile-icon svg {
    width: 24px; /* Adjust the size of the icon */
    height: 24px;
    fill: rgba(0, 0, 0, 0.7); /* Adjust the color of the icon */
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
        <li><a href="user_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="user_order.php"><i class="bx bx-box"></i><span class="links_name">Order Milk Tea</span></a></li>
        <li><a href="user_myorders.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">My Orders</span></a></li>
        <li><a href="user_favorites.php" class="active"><i class="bx bx-line-chart"></i><span class="links_name">Favorites</span></a></li>
        <li><a href="user_settings.php"><i class="bx bx-cog"></i><span class="links_name">Setting</span></a></li>
        <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
</div>

<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">My Favorites</span>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search..." />
            <i class="bx bx-search"></i>
        </div>
        <div class="profile-details">
            <img src="images/admin.jpg" alt="" />
            <span class="admin_name">User</span>
            <i class="bx bx-chevron-down"></i>
        </div>
    </nav>

    <div class="home-content">
        <div class="section-container">
            <div class="section-header">
                <h2><i class="bx bx-heart"></i> Favorite Milk Teas</h2>
            </div>

            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php elseif (empty($favorites)): ?>
                <p class="no-favorites-message">You haven't added any favorites yet.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($favorites as $favorite): ?>
                                <td>
    <a href="remove_favorite.php?favorite_id=<?php echo $favorite['favorite_id']; ?>" 
       onclick="return confirm('Are you sure you want to remove this from your favorites?')" 
       style="color: #C0392B; text-decoration: none; font-weight: bold;">
       Remove
    </a>
</td>

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
</script>
</body>
</html>
