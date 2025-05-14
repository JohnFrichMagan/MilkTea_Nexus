<?php
require_once 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addProduct'])) {
        $name = mysqli_real_escape_string($conn, $_POST['productName']);
        $image_url = mysqli_real_escape_string($conn, $_POST['imageUrl']);

        $sql = "INSERT INTO incoming_products (product_name, image_url) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $image_url);
        $stmt->execute();
        $stmt->close();
        $message = "Product added successfully!";
    }

    if (isset($_POST['editProduct'])) {
        $id = $_POST['productId'];
        $name = mysqli_real_escape_string($conn, $_POST['editProductName']);
        $image_url = mysqli_real_escape_string($conn, $_POST['editImageUrl']);
        $sql = "UPDATE incoming_products SET product_name=?, image_url=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $image_url, $id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['deleteProduct'])) {
        $id = $_POST['deleteId'];
        $sql = "DELETE FROM incoming_products WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

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
            <li><a href="admin_dashboard.php" class="active"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
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
                <span class="dashboard">Add Incoming Products</span>
            </div>

            <div class="search-box">
                <input type="text" placeholder="Search..." />
                <i class="bx bx-search"></i>
            </div>
            <div class="profile-details">
                <span class="admin_name">Admin</span>
                <div class="profile-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 4a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                    </svg>
                </div>
            </div>
        </nav>

        <div class="home-content">
            <div class="center-btn">
                <button id="addProductBtn" class="add-product-btn">Add Incoming Product</button>
            </div>

            <div class="add-product" id="addProductForm" style="display:none;">
                <form action="" method="POST" class="product-form">
                    <button class="close-btn" id="closeFormBtn">✖</button>
                    <h3 class="form-title">Add Incoming Product</h3>
                    <div class="form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" id="productName" name="productName" required placeholder="Enter product name" />
                    </div>
                    <div class="form-group">
                        <label for="imageUrl">Image URL</label>
                        <input type="text" id="imageUrl" name="imageUrl" required placeholder="Enter image URL" />
                    </div>
                    <button type="submit" name="addProduct" class="btn-save">Add Product</button>
                </form>
            </div>

            <div class="add-product" id="editProductForm" style="display:none;">
                <form action="" method="POST" class="product-form">
                    <button class="close-btn" onclick="document.getElementById('editProductForm').style.display='none'; return false;">✖</button>
                    <h3 class="form-title">Edit Product</h3>
                    <input type="hidden" id="editProductId" name="productId">
                    <div class="form-group">
                        <label for="editProductName">Product Name</label>
                        <input type="text" id="editProductName" name="editProductName" required />
                    </div>
                    <div class="form-group">
                        <label for="editImageUrl">Image URL</label>
                        <input type="text" id="editImageUrl" name="editImageUrl" required />
                    </div>
                    <button type="submit" name="editProduct" class="btn-save">Update</button>
                </form>
            </div>

            <div class="product-list">
                <?php foreach ($products as $product): ?>
            <?php
            $escapedName = htmlspecialchars($product['product_name'], ENT_QUOTES);
            $escapedImage = htmlspecialchars($product['image_url'], ENT_QUOTES);
            ?>
                <div class="product-item">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image">
                    <p><?= htmlspecialchars($product['product_name']) ?></p>
                    <div style="margin-top:10px;">
                        <button class="btn-save" onclick="openEditModal(<?= $product['id'] ?>, '<?= $escapedName ?>', '<?= $escapedImage ?>')">Edit</button>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="deleteId" value="<?= $product['id'] ?>">
                            <button type="submit" name="deleteProduct" class="btn-save" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            </div>

            <div class="add-product" id="deleteConfirmBox" style="display:none;">
                <form action="" method="POST" class="product-form">
                    <h3>Are you sure you want to delete this product?</h3>
                    <input type="hidden" name="deleteId" id="deleteId" />
                    <button type="submit" name="deleteProduct" class="btn-save">Yes</button>
                    <button type="button" class="btn-save" onclick="document.getElementById('deleteConfirmBox').style.display='none';">No</button>
                </form>
            </div>
        </div>
    </section>

<script>
    // Show/Hide Add Product Modal
    document.getElementById('addProductBtn').onclick = () => {
        document.getElementById('addProductForm').style.display = 'flex';
    };
    document.getElementById('closeFormBtn').onclick = () => {
        document.getElementById('addProductForm').style.display = 'none';
    };


    function openEditModal(id, name, image) {
        document.getElementById('editProductId').value = id;
        document.getElementById('editProductName').value = name;
        document.getElementById('editImageUrl').value = image;
        document.getElementById('editProductForm').style.display = 'flex';
    }


    function confirmDelete(id) {
        console.log("Delete ID:", id);
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteConfirmBox').style.display = 'flex';
    }

    // Sidebar toggle remains unchanged
    const sidebar = document.querySelector(".sidebar");
    const sidebarBtn = document.querySelector(".sidebarBtn");
    sidebarBtn.addEventListener('click', () => {
        sidebar.classList.toggle("active");
        sidebarBtn.classList.toggle("bx-menu-alt-right");
        sidebarBtn.classList.toggle("bx-menu");
    });
</script>

<style>
/* Reuse your existing styles, and add below for the buttons and spacing */
.product-item button {
    margin: 5px;
    padding: 6px 12px;
    font-size: 14px;
}

.center-btn {
    display: flex;
    justify-content: left;
    margin-top: 30px;
}

.add-product-btn {
    background-color:hsl(0, 8.30%, 26.10%);
    color: white;
    padding: 15px 30px;
    font-size: 18px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.add-product-btn:hover {
    background-color:hsl(0, 8.30%, 26.10%);
}


/* The container for both Add and Edit forms */
.add-edit-container {
    display: none; /* Initially hidden */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: #FFDCC1; /* Black w/ opacity */
    justify-content: center; /* Center content horizontally */
    align-items: center; /* Center content vertically */
}

/* Style for the form within the container */
.product-form {
    background-color: #fff;
    padding: 50px;
    border-radius: 8px;
    width: 1000%;
    max-width: 600px;
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
    background-color:hsl(0, 8.30%, 26.10%);
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
    gap: 32px;
    margin-top: 30px;
    padding: 10px;
    border-top: 2px solid #ccc;
}

.product-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 300px;
    background-color: #FFDCC1;
    border-radius: 8px;
    padding: 50px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.product-item img {
    width: 200px;
    height: 200px;
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
