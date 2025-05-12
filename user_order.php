<?php
require_once 'config.php'; // Database connection
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all products
$products = $conn->query("SELECT product_id, product_name, price FROM products");
if (!$products) {
    die("Failed to fetch products: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    echo "Product ID: " . $product_id; // Add this line to debug
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $order_date = date("Y-m-d H:i:s");

    if ($product_id > 0 && $quantity > 0) {
        // Fetch product price
        $product_query = "SELECT price FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($product_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($price);
        $stmt->fetch();
        $stmt->close();

        if ($price) {
            $total_amount = $price * $quantity;

            // Insert into orders table
            $query = "INSERT INTO orders (user_id, order_date, total_amount, product_id, quantity) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isdii", $user_id, $order_date, $total_amount, $product_id, $quantity);


            if ($stmt->execute()) {
                $order_id = $stmt->insert_id; // Get the inserted order_id
                $stmt->close();

                // Insert into order_details table
                $details_query = "INSERT INTO order_details (order_id, product_id, quantity, price, order_date) 
                                  VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($details_query);
                $stmt->bind_param("iiids", $order_id, $product_id, $quantity, $price, $order_date);

                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: user_order.php?success=1");
                    exit();
                } else {
                    echo "Error inserting order details: " . $stmt->error;
                    $stmt->close();
                }
            } else {
                echo "Error placing order: " . $stmt->error;
                $stmt->close();
            }
        } else {
            echo "Product not found.";
        }
    } else {
        echo "Please select a product and enter a valid quantity.";
    }
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
      <li><a href="user_order.php"><i class="bx bx-box"></i><span class="links_name">Order Milk Tea</span></a></li>
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
  <!-- Success Modal -->
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="successModal" class="modal" style="display: flex;">
      <div class="modal-content">
        <span class="close-btn" id="closeModalBtn">&times;</span>
        <h2>🎉 Order Successful!</h2>
        <p>Your milk tea order has been placed. We’re already working on it!</p>
        <a href="user_myorders.php">
          <button>View My Orders</button>
        </a>
      </div>
    </div>
  <?php endif; ?>

      <div class="center-btn">
        <button id="addOrderBtn" class="add-product-btn">Add User Order</button>
      </div>

      <div class="add-product" id="addOrderForm" style="display: none; position: relative;">
        <button class="close-btn" id="closeFormBtn" title="Close Form">✖</button>
        <h3 class="form-title">Place New Order</h3>
        <form action="user_order.php" method="POST" class="product-form">
            <!-- Automatically set user_id if logged in -->
            <input type="hidden" name="user_id" value="<?= $user_id ?>" />

            <div class="form-group">
                <label for="product_id">Select Product</label>
            <select id="product_id" name="product_id" required>
                <option value="">-- Choose a product --</option>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <option value="<?= $row['product_id'] ?>">
                        <?= htmlspecialchars($row['product_name']) ?> - ₱<?= number_format($row['price'], 2) ?>
                    </option>
                <?php endwhile; ?>
            </select>

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

    // Close modal
    document.getElementById('closeModalBtn').addEventListener('click', function () {
      document.getElementById('successModal').style.display = 'none';
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
        .modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #ffffff;
  padding: 30px 40px;
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  text-align: center;
  max-width: 500px;
  width: 90%;
  position: relative;
  animation: fadeIn 0.3s ease-in-out;
}

.modal-content h2 {
  color: #2d3436;
  margin-bottom: 10px;
  font-size: 28px;
}

.modal-content p {
  color: #636e72;
  font-size: 16px;
  margin-bottom: 25px;
}

.modal-content button {
  background-color: #6c5ce7;
  color: white;
  border: none;
  padding: 12px 25px;
  font-size: 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.modal-content button:hover {
  background-color: #4e38c1;
}

.close-btn {
  color: #d63031;
  font-size: 24px;
  font-weight: bold;
  position: absolute;
  top: 15px;
  right: 20px;
  cursor: pointer;
}

@keyframes fadeIn {
  from {
    transform: scale(0.95);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

  </style>
</body>
</html>
