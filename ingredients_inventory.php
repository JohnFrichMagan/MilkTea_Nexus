<?php
include 'config.php';

if (!$conn) {
    die("Database connection failed.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ingredients Inventory | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        input[type=number] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 14px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
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
        <li><a href="admin_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="product.php"><i class="bx bx-box"></i><span class="links_name">Product</span></a></li>
        <li><a href="ingredients_inventory.php" class="active"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Ingredients Inventory</span></a></li>
        <li><a href="financial_reports.php"><i class="bx bx-line-chart"></i><span class="links_name">Financial Reports</span></a></li>
        <li><a href="menu.php"><i class="bx bx-menu"></i><span class="links_name">Menu</span></a></li>
        <li><a href="settings.php"><i class="bx bx-cog"></i><span class="links_name">Settings</span></a></li>
        <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
</div>

<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Ingredients Inventory</span>
        </div>
        <div class="profile-details">
            <img src="images/profile.jpg" alt="" />
            <span class="admin_name">Admin</span>
            <i class="bx bx-chevron-down"></i>
        </div>
    </nav>

    <div class="home-content">
        <div class="sales-boxes">
            <div class="recent-sales box">
                <div class="title">Ingredient Stock Levels</div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                
                                <th>Milk Tea Cup</th>
                                <th>Milk Tea Powder</th>
                                <th>Pearl</th>
                                <th>Milk</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="add_ingredient_stock.php" method="POST">
                                    <td><input type="number" name="milk_tea_cup" min="0" required></td>
                                    <td><input type="number" name="milk_tea_powder" min="0" required></td>
                                    <td><input type="number" name="pearl" min="0" required></td>
                                    <td><input type="number" name="milk" min="0" required></td>
                                    <td><button type="submit">Add</button></td>
                                </form>
                            </tr>

                            <?php
                            $sql = "SELECT * FROM ingredients_stock ORDER BY id DESC";
                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                    
                                        <td>" . intval($row['milk_tea_cup']) . "</td>
                                        <td>" . intval($row['milk_tea_powder']) . "</td>
                                        <td>" . intval($row['pearl']) . "</td>
                                        <td>" . intval($row['milk']) . "</td>
                                        <td>
                                            <form method='POST' action='delete_ingredient_stock.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>
                                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                                <button type='submit' class='delete-btn'>Delete</button>
                                            </form>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No stock records found.</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
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