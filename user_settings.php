<?php
require_once 'config.php'; 
// Start the session at the top of the page
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the admin is not logged in
    header("Location: user_login.php");
    exit(); // Make sure to stop the script here after redirection
}

// Assuming $user_id is set now, proceed with fetching the user data
$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch current user data
$sql = "SELECT username, email FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Update profile info
if (isset($_POST['save_profile'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $sql = "UPDATE user SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
            $username = $new_username;
            $email = $new_email;
        } else {
            $error = "Update failed.";
        }
        $stmt->close();
    }
}

// Update password
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Get current password hash
    $stmt = $conn->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $hashed)) {
        $error = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } elseif (strlen($new) < 8) {
        $error = "New password must be at least 8 characters.";
    } else {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hash, $user_id);
        if ($stmt->execute()) {
            $message = "Password changed successfully.";
        } else {
            $error = "Password update failed.";
        }
        $stmt->close();
    }
}

// Delete account
if (isset($_POST['delete_account'])) {
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    header("Location: user_register.php?account_deleted=true");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <title>Settings | MILKTEA NEXUS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; background: #c1956c; margin: 0; }
    .content { margin-right: 30px; padding: 80px; }
    .tabs { display: flex; gap: 20px; margin-bottom: 30px;}
    .tab {
      padding: 10px 50px; background: #f1dfc6; border-radius: 20px;
      cursor: pointer; font-weight: 500;
    }
    .tab.active { background: #e2ad7e; color: #fff; }
    .tab-content { background: #fff; padding: 20px; border-radius: 50px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; color: #333; }
    .form-group input, .form-group select {
      width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 20px;
    }
    .btn-save {
      background: #e2ad7e; color: #fff; padding: 10px 20px;
      border: none; border-radius: 20px; cursor: pointer; font-weight: 500;
    }
    .profile-details {
    display: flex; /* Enable flexbox for centering */
    justify-content: center; /* Center content horizontally */
    align-items: center; /* Center content vertically */
    /* Add any other styling for the container if needed */
    padding: 10px; /* Example padding */
    background-color: #f0f0f0; /* Example background color */
    border-radius: 5px; /* Example border radius */
}

.user-info {
    display: flex; /* Enable flexbox for name and icon */
    align-items: center; /* Align name and icon vertically */
}

.user_name {
    margin-right: 8px; /* Add some space between the name and the icon */
}

.profile-icon svg {
    width: 24px; /* Adjust the size of the icon */
    height: 24px;
    fill: rgba(0, 0, 0, 0.7); /* Adjust the color of the icon */
}
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo-details">
      <i class="bx bx-coffee"></i>
      <span class="logo_name">MILKTEA NEXUS</span>
      </div>
        <ul class="nav-links">
            <li><a href="user_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
            <li><a href="user_order.php" class="active"><i class="bx bx-box"></i><span class="links_name">Order Milk Tea</span></a></li>
            <li><a href="user_myorders.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">My Orders</span></a></li>
            <li><a href="user_favorites.php"><i class="bx bx-line-chart"></i><span class="links_name">Favorites</span></a></li>
            <li><a href="user_settings.php"><i class="bx bx-cog"></i><span class="links_name">Setting</span></a></li>
            <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
        </ul>
    </div>
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">Settings</span>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search..." />
                <i class="bx bx-search"></i>
            </div>
            <div class="profile-details">
        <img src="images/admin.jpg" alt="" />
        <span class="admin_name">User</span>
        <i class="bx bx-chevron-down"></i>
      </div>
        </nav>
  <!-- Main Content -->
  <div class="content">


    <?php if ($message): ?>
      <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tabs">
      <div class="tab active" data-tab="profile">Profile</div>
      <div class="tab" data-tab="security">Security</div>
      <div class="tab" data-tab="notifications">Notifications</div>
    </div>

    <!-- Profile Settings -->
    <div class="tab-content" id="profile">
      <form method="POST">
        <div class="form-group">
          <label for="userName">User Name</label>
          <input type="text" name="username" id="userName" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="form-group">
          <label for="userEmail">Email Address</label>
          <input type="email" name="email" id="userEmail" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <button class="btn-save" name="save_profile">Save Profile</button>
      </form>
    </div>

    <!-- Security Settings -->
    <div class="tab-content" id="security" style="display:none;">
      <form method="POST">
        <div class="form-group">
          <label for="currentPassword">Current Password</label>
          <input type="password" name="current_password" id="currentPassword" required placeholder="••••••••">
        </div>
        <div class="form-group">
          <label for="newPassword">New Password</label>
          <input type="password" name="new_password" id="newPassword" required placeholder="••••••••">
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirm New Password</label>
          <input type="password" name="confirm_password" id="confirmPassword" required placeholder="••••••••">
        </div>
        <button class="btn-save" name="change_password">Change Password</button>
      </form>
    </div>

    <!-- Notification Settings -->
    <div class="tab-content" id="notifications" style="display:none;">
      <div class="form-group">
        <label for="emailNotif">Email Notifications</label>
        <select id="emailNotif">
          <option>Enabled</option>
          <option>Disabled</option>
        </select>
      </div>
      <div class="form-group">
        <label for="smsNotif">SMS Notifications</label>
        <select id="smsNotif">
          <option>Enabled</option>
          <option>Disabled</option>
        </select>
      </div>
      <button class="btn-save">Save Notifications</button>
    </div>
  </div>
  </section>

  <!-- Scripts -->
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
    
    // Tab switching
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c=>c.style.display='none');
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).style.display = 'block';
      });
    });
  </script>

</body>
</html>
