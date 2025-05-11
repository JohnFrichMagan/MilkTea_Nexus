<?php
require_once 'config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form input
    $name = mysqli_real_escape_string($conn, $_POST['productName']); // fixed name
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO products (product_name, category, price, stock_quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $name, $category, $price, $stock);

    if ($stmt->execute()) {
        // Redirect to product.php after successful insertion
        header("Location: product.php?success=1");
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
  <title>Menu | MILKTEA NEXUS</title>
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
      <li><a href="admin_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
      <li><a href="product.php"><i class="bx bx-box"></i><span class="links_name">Product</span></a></li>
      <li><a href="analytics.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Analytics</span></a></li>
      <li><a href="financial_reports.php"><i class="bx bx-line-chart"></i><span class="links_name">Financial Reports</span></a></li>
      <li><a href="human_resource.php"><i class="bx bx-group"></i><span class="links_name">Human Resource</span></a></li>
      <li><a href="menu.php"><i class="bx bx-menu"></i><span class="links_name">Menu</span></a></li>
      <li><a href="settings.php"><i class="bx bx-cog"></i><span class="links_name">Setting</span></a></li>
      <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
  </div>

  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class="bx bx-menu sidebarBtn"></i>
        <span class="dashboard">Menu Management</span>
      </div>
      <div class="profile-details">
        <img src="images/profile.jpg" alt="" />
        <span class="admin_name">Admin</span>
        <i class="bx bx-chevron-down"></i>
      </div>
    </nav>

    <div class="home-content">
      <!-- Centered Add Product Button -->
      <div class="center-btn">
        <button id="addProductBtn" class="add-product-btn">Add Product</button>
      </div>

      <!-- Add Product Form -->
      <div class="add-product" id="addProductForm" style="display: none; position: relative;">
        
        <!-- X Close Button -->
        <button class="close-btn" id="closeFormBtn" title="Close Form">✖</button>

        <h3 class="form-title">Add New Menu Item</h3>
        <form action="" method="POST" class="product-form">
          <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" id="productName" name="productName" required placeholder="Enter product name" />
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category">
              <option value="Milk Tea">Milk Tea</option>
              <option value="Latte">Latte</option>
              <option value="Smoothie">Smoothie</option>
              <option value="Coffee">Coffee</option>
            </select>
          </div>
          <div class="form-group">
            <label for="price">Price (₱)</label>
            <input type="number" id="price" name="price" required placeholder="Enter price" />
          </div>
          <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" required placeholder="Enter stock quantity" />
          </div>
          <button type="submit" class="btn-save">Add Menu Item</button>
        </form>
      </div>
    </div>
  </section>

  <script>
    // Show the form
    document.getElementById('addProductBtn').addEventListener('click', function () {
      const form = document.getElementById('addProductForm');
      form.style.display = 'block';
      form.scrollIntoView({ behavior: 'smooth' });
    });

    // Hide the form when X button is clicked
    document.getElementById('closeFormBtn').addEventListener('click', function () {
      document.getElementById('addProductForm').style.display = 'none';
    });

    // Listen for input changes on the product name field to auto-fill the category
    document.getElementById('productName').addEventListener('input', function () {
      const name = this.value.toLowerCase();
      const categorySelect = document.getElementById('category');

      // Auto-detect the category based on keywords in the product name
      if (name.includes('smoothie')) {
        categorySelect.value = 'Smoothie';
      } else if (name.includes('latte')) {
        categorySelect.value = 'Latte';
      } else if (name.includes('milk tea') || name.includes('bubble')) {
        categorySelect.value = 'Milk Tea';
      } else if (name.includes('coffee')) {
        categorySelect.value = 'Coffee';
      }
    });
      // Sidebar toggle (ADDED THIS BLOCK)
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
    /* Centering the Add Product Button */
    .center-btn {
      display: flex;
      justify-content: left;
      align-items: center;
      margin-top: 3opx;
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
