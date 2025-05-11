
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
    <?php if (isset($_SESSION["email"])): ?>
        <span class="user_name"><?php echo htmlspecialchars($_SESSION["email"]); ?></span>
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
                        <ul class="details">
                            <li class="topic">Date Order</li>
                            <li><a href="#">01-10-2025</a></li>
                            <li><a href="#">01-15-2025</a></li>
                            <li><a href="#">01-20-2025</a></li>
                            <li><a href="#">01-15-2025</a></li>
                            <li><a href="#">01-300-2025</a></li>
                            <li><a href="#">02-04-2025</a></li>
                            <li><a href="#">02-10-2025</a></li>
                            <li><a href="#">03-05-2025</a></li>
                            <li><a href="#">03-10-2025</a></li>
                        </ul>
                        <ul class="details">
                            <li class="topic">Status</li>
                            <li><a href="#">Delivered</a></li>
                            <li><a href="#">Pending</a></li>
                            <li><a href="#">Returned</a></li>
                            <li><a href="#">Delivered</a></li>
                            <li><a href="#">Pending</a></li>
                            <li><a href="#">Returned</a></li>
                            <li><a href="#">Delivered</a></li>
                            <li><a href="#">Pending</a></li>
                            <li><a href="#">Delivered</a></li>
                        </ul>
                        <ul class="details">
                            <li class="topic">Total</li>
                            <li><a href="#">₱100</a></li>
                            <li><a href="#">₱90</a></li>
                            <li><a href="#">₱95</a></li>
                            <li><a href="#">₱100</a></li>
                            <li><a href="#">₱95</a></li>
                            <li><a href="#">₱100</a></li>
                            <li><a href="#">₱90</a></li>
                            <li><a href="#">₱100</a></li>
                            <li><a href="#">₱95</a></li>
                        </ul>
                    </div>
                    <div class="button">
                        <a href="#">See All</a>
                    </div>
                </div>
                <div class="top-sales box">
                    <div class="title">Top Seling Product</div>
                    <ul class="top-sales-details">
                        <li>
                            <a href="#">
                                <img src="images/chocolateBubbleTea.jpg" alt="" />
                                <span class="product">Chocolate Bubble Tea</span>
                            </a>
                            <span class="price">₱ 100</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/boba.jpg" alt="" />
                                <span class="product">Boba Milk Tea</span>
                            </a>
                            <span class="price">₱ 95</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/purple boba.jpg" alt="" />
                                <span class="product">Purple Boba Milk Tea</span>
                            </a>
                            <span class="price">₱ 90</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/taro.jpg" alt="" />
                                <span class="product">Taro Milk Tea</span>
                            </a>
                            <span class="price">₱ 95</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/strawberry.jpg" alt="" />
                                <span class="product">Stawberry Milk Tea</span>
                            </a>
                            <span class="price">₱ 100</span>
                        </li>
                        <li>
                            <a href="#">
                                <img src="images/matcha.jpg" alt="" />
                                <span class="product">Match Milk Tea</span>
                            </a>
                            <span class="price">₱ 95</span>
                        </li>
                    </ul>
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