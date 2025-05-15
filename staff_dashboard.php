<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>Responsive Staff Dashboard | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Style similar to the user dashboard */
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

        .profile-details {
            display: flex;
            justify-content: center;
            align-items: center;
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

        .sales-boxes {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .links_name {
            margin-left: 10px;
        }

        .log_out a {
            text-decoration: none;
            color: #f44336;
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
                <a href="staff_dashboard.php" class="active">
                    <i class="bx bx-grid-alt"></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="staff_financial_report.php">
                    <i class="bx bx-bar-chart-alt-2"></i>
                    <span class="links_name">Financial Report</span>
                </a>
            </li>
            <li>
                <a href="staff_customer_orders.php">
                    <i class="bx bx-cart-alt"></i>
                    <span class="links_name">Customer Orders</span>
                </a>
            </li>
            <li>
                <a href="staff_settings.php">
                    <i class="bx bx-cog"></i>
                    <span class="links_name">Settings</span>
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
                <img src="images/admin.jpg" alt="" />
                <span class="admin_name">Staff</span>
                <i class="bx bx-chevron-down"></i>
            </div>
        </nav>

        <div class="home-content">
            <div class="sales-boxes">
                <!-- Example of overview boxes for Financial Report and Customer Orders -->
                <div class="box">
                    <div class="title">Total Sales</div>
                    <div class="number">₱500,000</div>
                </div>
                <div class="box">
                    <div class="title">Pending Orders</div>
                    <div class="number">30</div>
                </div>
                <div class="box">
                    <div class="title">Total Customers</div>
                    <div class="number">150</div>
                </div>
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