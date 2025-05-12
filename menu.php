<?php
require_once 'config.php'; // Include your database connection

// Handling the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['productName']);
    $image_url = mysqli_real_escape_string($conn, $_POST['imageUrl']);

    $sql = "INSERT INTO incoming_products (product_name, image_url) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $image_url);

    if ($stmt->execute()) {
        $message = "Product added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all products
$products_query = "SELECT * FROM incoming_products ORDER BY id DESC";
$products_result = mysqli_query($conn, $products_query);
$products = mysqli_fetch_all($products_result, MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
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
      <li><a href="menu.php" class="active"><i class="bx bx-menu"></i><span class="links_name">Add Incoming Product</span></a></li>
      <li><a href="settings.php"><i class="bx bx-cog"></i><span class="links_name">Setting</span></a></li>
      <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
  </div>

  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class="bx bx-menu sidebarBtn"></i>
        <span class="dashboard">Add Incoming Products</span>
      </div>
    </nav>

    <div class="home-content">
      <div class="center-btn">
        <button id="addProductBtn" class="add-product-btn">Add Product</button>
      </div>

      <!-- Modal Add Product Form -->
      <div class="add-product" id="addProductForm">
        <form action="" method="POST" class="product-form">
          <button class="close-btn" id="closeFormBtn" title="Close Form">✖</button>
          <h3 class="form-title">Add Incoming Product</h3>
          <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" id="productName" name="productName" required placeholder="Enter product name" />
          </div>
          <div class="form-group">
            <label for="imageUrl">Image URL</label>
            <input type="text" id="imageUrl" name="imageUrl" required placeholder="Enter image URL" />
          </div>
          <button type="submit" class="btn-save">Add Product</button>

          <?php if (isset($message)): ?>
            <p style="color: green;"><?php echo $message; ?></p>
          <?php endif; ?>
          <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
          <?php endif; ?>
        </form>
      </div>

      <!-- Product List -->
      <div class="product-list">
        <?php foreach ($products as $product): ?>
          <div class="product-item">
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image">
            <p><?= htmlspecialchars($product['product_name']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <script>
    const form = document.getElementById('addProductForm');

    document.getElementById('addProductBtn').addEventListener('click', () => {
      form.style.display = 'flex';
    });

    document.getElementById('closeFormBtn').addEventListener('click', () => {
      form.style.display = 'none';
    });

    // Sidebar toggle
    const sidebar = document.querySelector(".sidebar");
    const sidebarBtn = document.querySelector(".sidebarBtn");
    sidebarBtn.addEventListener('click', () => {
      sidebar.classList.toggle("active");
      sidebarBtn.classList.toggle("bx-menu-alt-right");
      sidebarBtn.classList.toggle("bx-menu");
    });
  </script>

  <style>
 .center-btn {
  display: flex;
  justify-content: left;
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

/* FIXED: Remove duplicate display: none */
.add-product {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.6);
  z-index: 999;
  display: none; /* Hide initially */
  justify-content: center;
  align-items: center;
}

/* When active, show the modal */
.add-product.show {
  display: flex;
}

.product-form {
  background-color: #fff;
  padding: 30px;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  position: relative;
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 20px;
  background: none;
  border: none;
  cursor: pointer;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-weight: bold;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.btn-save {
  background-color: #6c5ce7;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
}

.btn-save:hover {
  background-color: #4e38c1;
}

.product-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 30px;
  padding: 10px;
  border-top: 2px solid #ccc;
}

.product-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 150px;
  background-color: #f4f4f4;
  border-radius: 8px;
  padding: 10px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.product-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 6px;
}

.product-item p {
  margin-top: 8px;
  font-weight: bold;
  font-size: 14px;
  text-align: center;
}

  </style>
</body>
</html>
