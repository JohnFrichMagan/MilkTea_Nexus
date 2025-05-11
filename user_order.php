<?php
require_once 'config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form input
    $user_id = intval($_POST['user_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $order_date = date("Y-m-d H:i:s");

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO orders (user_id, product_id, quantity, order_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $user_id, $product_id, $quantity, $order_date);

    if ($stmt->execute()) {
        // Redirect to confirmation or same page
        header("Location: user_orders.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <title>User Orders | MILKTEA NEXUS</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
  <div class="sidebar">
    <div class="logo-details">
      <i class="bx bx-coffee"></i>
      <span class="logo_name">MILKTEA NEXUS</span>
    </div>
    <ul class="nav-links">
      <li><a href="user_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
      <li><a href="user_orders.php"><i class="bx bx-box"></i><span class="links_name">  Order Milk Tea</span></a></li>
      <li><a href="user_myorders.php" class="active"><i class="bx bx-cart"></i><span class="links_name">My Orders</span></a></li>
      <li><a href="user_favorites.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Favorites</span></a></li>
      <li><a href="user_settings.php"><i class="bx bx-cog"></i><span class="links_name">Settings</span></a></li>
      <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
  </div>

  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class="bx bx-menu sidebarBtn"></i>
        <span class="dashboard">User Orders</span>
      </div>
      <div class="profile-details">
        <img src="images/profile.jpg" alt="" />
        <span class="admin_name">User</span>
        <i class="bx bx-chevron-down"></i>
      </div>
    </nav>

    <div class="home-content">
      <div class="center-btn">
        <button id="addOrderBtn" class="add-product-btn">Add User Order</button>
      </div>

      <div class="add-product" id="addOrderForm" style="display: none; position: relative;">
        <button class="close-btn" id="closeFormBtn" title="Close Form">✖</button>
        <h3 class="form-title">Place New Order</h3>
        <form action="" method="POST" class="product-form">
          <div class="form-group">
            <label for="user_id">User ID</label>
            <input type="number" id="user_id" name="user_id" required placeholder="Enter User ID" />
          </div>
          <div class="form-group">
            <label for="product_id">Product ID</label>
            <input type="number" id="product_id" name="product_id" required placeholder="Enter Product ID" />
          </div>
          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" required placeholder="Enter Quantity" />
          </div>
          <button type="submit" class="btn-save">Place Order</button>
        </form>
      </div>
    </div>
  </section>

  <script>
    // Show the form
    document.getElementById('addOrderBtn').addEventListener('click', function () {
      const form = document.getElementById('addOrderForm');
      form.style.display = 'block';
      form.scrollIntoView({ behavior: 'smooth' });
    });

    // Hide the form when X button is clicked
    document.getElementById('closeFormBtn').addEventListener('click', function () {
      document.getElementById('addOrderForm').style.display = 'none';
    });

    // Sidebar toggle
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".sidebarBtn");
    sidebarBtn && sidebarBtn.addEventListener('click', () => {
      sidebar.classList.toggle("active");
      if (sidebar.classList.contains("active")) {
        sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
      } else {
        sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      }
    });
  </script>

  <style>
    .center-btn {
      display: flex;
      justify-content: left;
      align-items: center;
      margin-top: 30px;
    }
    .add-product-btn {
      background-color: #6c5ce7;
      color: white;
      padding: 15px 30px;
      font-size: 18px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .add-product-btn:hover {
      background-color: #4e38c1;
    }
  </style>
</body>
</html>
