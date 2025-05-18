<?php
require_once 'config.php';

// Fetch all products
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>Responsive User Dashboard | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .profile-details {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user_name {
            margin-right: 8px;
        }
        .profile-icon svg {
            width: 24px;
            height: 24px;
            fill: rgba(0, 0, 0, 0.7);
        }
        /* Product cards container */
        .products-container {
            padding: 20px;

        }
        .products-container h2 {
            margin-top: 70px;
            color: #000000; 
            font-size: 50px;
            font-weight: 600;
            text-align: center;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        .product-card {
            background: #E2AD7E;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            width: 220px;
            padding: 15px;
            text-align: center;
            margin-top: 30px;
            transition: box-shadow 0.3s ease;
        }
        .product-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 12px;
        }
        .product-card h3 {
            font-size: 1.1rem;
            margin: 10px 0 6px;
            color: #333;
        }
        .product-card p {
            margin: 4px 0;
            color: #555;
            font-size: 0.9rem;
        }
        /* Sidebar and nav styles */
        /* (Assuming your existing styles in style.css handle sidebar and nav) */
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
                <a href="" class="active">
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
                <a href="user_myorders.php">
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
                <span class="dashboard">Dashboard</span>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search..." />
                <i class="bx bx-search"></i>
            </div>
            <div class="profile-details">
                <img src="images/admin.jpg" alt="User Profile" />
                <span class="admin_name">User</span>
                <i class="bx bx-chevron-down"></i>
            </div>
        </nav>

        <div class="products-container">
            <h2>Available Products</h2>
            <div class="product-grid">
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" />
                        <?php else: ?>
                            <div style="width: 100%; height: 150px; background: #ddd; border-radius: 5px; display: flex; align-items: center; justify-content: center;">No Image</div>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                        <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
                        <p><strong>Price:</strong> ₱<?= number_format($product['price'], 2) ?></p>
                        <p><strong>Quantity:</strong> <?= intval($product['stock_quantity']) ?></p>
                    </div>
                <?php endwhile; ?>
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
