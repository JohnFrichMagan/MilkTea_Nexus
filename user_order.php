<?php
require_once 'config.php';
session_start();

$error_message = '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all products
$products = $conn->query("SELECT product_id, product_name, price, stock_quantity FROM products");
if (!$products) {
    die("Failed to fetch products: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $order_date = date("Y-m-d H:i:s");
    $stmt = null;

    if ($product_id > 0 && $quantity > 0) {
        // Get product details
        $product_query = "SELECT product_name, price, stock_quantity FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($product_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($product_name_ordered, $price, $current_stock);
        $stmt->fetch();
        $stmt->close();

        if ($price) {
            if ($current_stock >= $quantity) {
                $total_amount = $price * $quantity;

                $conn->begin_transaction();
                $success = true;

                // Insert order
                $query = "INSERT INTO orders (user_id, order_date, total_amount, product_id, quantity) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("isdii", $user_id, $order_date, $total_amount, $product_id, $quantity);
                if (!$stmt->execute()) {
                    $error_message = "Error placing order: " . $conn->error;
                    $success = false;
                }
                $stmt->close();

                if ($success) {
                    $order_id = $conn->insert_id;

                    // Update product stock
                    $update_stock_query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
                    $stmt = $conn->prepare($update_stock_query);
                    $stmt->bind_param("ii", $quantity, $product_id);
                    if (!$stmt->execute()) {
                        $error_message = "Error updating product stock: " . $conn->error;
                        $success = false;
                    }
                    $stmt->close();
                }

                if ($success) {
                    // Insert order details
                    $details_query = "INSERT INTO order_details (order_id, product_id, quantity, price, order_date) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($details_query);
                    $stmt->bind_param("iiids", $order_id, $product_id, $quantity, $price, $order_date);
                    if (!$stmt->execute()) {
                        $error_message = "Error inserting order details: " . $conn->error;
                        $success = false;
                    }
                    $stmt->close();
                }

                // Deduct ingredients
                if ($success) {
                    $deduct_ingredients_query = "UPDATE ingredients_stock SET ";
                    $updates = [];
                    $types = '';
                    $params = [];

                    $product_name_lower = trim(strtolower($product_name_ordered));

                    if ($product_name_lower === 'boba milktea') {
                        $updates[] = "milk_tea_cup = milk_tea_cup - ?";
                        $types .= 'i'; $params[] = $quantity;
                        $updates[] = "milk_tea_powder = milk_tea_powder - ?";
                        $types .= 'i'; $params[] = $quantity;
                        $updates[] = "pearl = pearl - ?";
                        $types .= 'i'; $params[] = $quantity;
                        $updates[] = "milk = milk - ?";
                        $types .= 'i'; $params[] = $quantity;

                        $deduct_ingredients_query .= implode(', ', $updates) . "
                            WHERE id = (SELECT MAX(id) FROM ingredients_stock)
                            AND milk_tea_cup >= ? AND milk_tea_powder >= ? AND pearl >= ? AND milk >= ?";
                        $types .= 'iiii';
                        $params = array_merge($params, [$quantity, $quantity, $quantity, $quantity]);

                    } elseif ($product_name_lower === 'chocolate milk tea') {
                        $cup = $quantity * 1;
                        $powder = $quantity * 2;
                        $milk_tea_powder = $quantity * 1;
                        $pearl = $quantity * 1;
                        $milk = $quantity * 1;

                        $updates[] = "milk_tea_cup = milk_tea_cup - ?";
                        $updates[] = "powder = powder - ?";
                        $updates[] = "milk_tea_powder = milk_tea_powder - ?";
                        $updates[] = "pearl = pearl - ?";
                        $updates[] = "milk = milk - ?";

                        $deduct_ingredients_query .= implode(', ', $updates) . "
                            WHERE id = (SELECT MAX(id) FROM ingredients_stock)
                            AND milk_tea_cup >= ? AND powder >= ? AND milk_tea_powder >= ? AND pearl >= ? AND milk >= ?";

                        $params = [$cup, $powder, $milk_tea_powder, $pearl, $milk, $cup, $powder, $milk_tea_powder, $pearl, $milk];
                        $types = str_repeat('i', count($params));
                    } else {
                        $error_message = "Ingredient deduction for '" . htmlspecialchars($product_name_ordered) . "' is not defined.";
                        $success = false;
                    }

                    if ($success && !empty($updates)) {
                        $stmt = $conn->prepare($deduct_ingredients_query);
                        if ($stmt === false) {
                            $error_message = "Error preparing ingredient deduction query: " . $conn->error;
                            $success = false;
                        } else {
                            $stmt->bind_param($types, ...$params);
                            if (!$stmt->execute()) {
                                $error_message = "Error deducting ingredients: " . $stmt->error;
                                $success = false;
                            }
                            $stmt->close();
                        }
                    }
                }

                if ($success) {
                    $conn->commit();
                    header("Location: user_order.php?success=1");
                    exit();
                } else {
                    $conn->rollback();
                }
            } else {
                $error_message = "Insufficient stock for " . htmlspecialchars($product_name_ordered) . ".";
            }
        } else {
            $error_message = "Product not found.";
        }
    } else {
        $error_message = "Please select a product and enter a valid quantity.";
    }
}
?>


<!-- Optional: display form and messages here -->

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>User Orders | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        .error-container {
            background-color: #ffe6e6;
            color: #d63031;
            border: 1px solid #d63031;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 500;
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
            <li><a href="user_order.php" class="active"><i class="bx bx-box"></i><span class="links_name">Order Milk Tea</span></a></li>
            <li><a href="user_myorders.php"><i class="bx bx-cart"></i><span class="links_name">My Orders</span></a></li>
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
                <img src="images/admin.jpg" alt="" />
                <span class="admin_name">User</span>
                <i class="bx bx-chevron-down"></i>
            </div>
        </nav>

        <div class="home-content">
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
            <?php if (!empty($error_message)): ?>
        <div class="error-container">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>


            <div class="center-btn">
                <button id="addOrderBtn" class="add-product-btn">Add User Order</button>
            </div>

            <div class="add-product" id="addOrderForm" style="display: none; position: relative;">
                <button class="close-btn" id="closeFormBtn" title="Close Form">✖</button>
                <h3 class="form-title">Place New Order</h3>
                <form action="user_order.php" method="POST" class="product-form">
                    <input type="hidden" name="user_id" value="<?= $user_id ?>" />

                    <div class="form-group">
                        <label for="product_id">Select Product</label>
                    <select id="product_id" name="product_id" required>
                        <option value="">-- Choose a product --</option>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <option value="<?= $row['product_id'] ?>" data-price="<?= $row['price'] ?>">
                                <?= htmlspecialchars($row['product_name']) ?> - ₱<?= number_format($row['price'], 2) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" required placeholder="Enter Quantity" value="1" min="1" />
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

        // Sidebar toggle remains unchanged
        const sidebar = document.querySelector(".sidebar");
        const sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle("active");
            sidebarBtn.classList.toggle("bx-menu-alt-right");
            sidebarBtn.classList.toggle("bx-menu");
        });
    </script>
</body>
</html>