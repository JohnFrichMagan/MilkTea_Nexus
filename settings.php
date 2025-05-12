<?php
require_once 'config.php'; 
session_start(); // Start the session

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login if not logged in
    exit();
}

// Get the admin ID from the session
$admin_id = $_SESSION['admin_id'];
$message = '';
$error = '';

// Fetch the current admin data
$sql = "SELECT username, email FROM Admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Update profile info (name & email)
if (isset($_POST['save_profile'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    // Validate email format
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Update the database with the new username and email
        $sql = "UPDATE Admins SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_username, $new_email, $admin_id);
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
            // Update session variables as well
            $_SESSION['admin_username'] = $new_username;
            $_SESSION['admin_email'] = $new_email;
        } else {
            $error = "Profile update failed.";
        }
        $stmt->close();
    }
}

// Update password
if (isset($_POST['change_password'])) {
    $current = trim($_POST['current_password']);
    $new = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    // Get the current hashed password from the database
    $stmt = $conn->prepare("SELECT password FROM Admins WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($hashed);
    $stmt->fetch();
    $stmt->close();

    // Validate the current password
    if (!password_verify($current, $hashed)) {
        $error = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } elseif (strlen($new) < 8) {
        $error = "New password must be at least 8 characters.";
    } else {
        // Hash the new password and update the database
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE Admins SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hash, $admin_id);
        if ($stmt->execute()) {
            $message = "Password changed successfully.";
        } else {
            $error = "Password update failed.";
        }
        $stmt->close();
    }
}

// Delete account (use with caution)
if (isset($_POST['delete_account'])) {
    // Delete admin account from the database
    $stmt = $conn->prepare("DELETE FROM Admins WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->close();
    // Destroy the session and redirect
    session_destroy();
    header("Location: admin_register.php?account_deleted=true");
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
    .tabs { display: flex; gap: 20px; margin-bottom: 30px; }
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
        <li><a href="admin_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="product.php" class="active"><i class="bx bx-box"></i><span class="links_name">Product</span></a></li>
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
            <span class="dashboard">Product Management</span>
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

    <!-- Main Content -->
    <div class="content">

      <!-- Display success/error messages -->
      <?php if ($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
      <?php endif; ?>
      <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
      <?php endif; ?>

      <!-- Tabs for different settings -->
      <div class="tabs">
        <div class="tab active" data-tab="profile">Profile</div>
        <div class="tab" data-tab="security">Security</div>
        <div class="tab" data-tab="notifications">Notifications</div>
      </div>

      <!-- Profile Settings Tab -->
      <div class="tab-content" id="profile">
        <form method="POST">
          <div class="form-group">
            <label for="adminName">Admin Name</label>
            <input type="text" name="username" id="adminName" value="<?php echo htmlspecialchars($username); ?>" required>
          </div>
          <div class="form-group">
            <label for="adminEmail">Email Address</label>
            <input type="email" name="email" id="adminEmail" value="<?php echo htmlspecialchars($email); ?>" required>
          </div>
          <button class="btn-save" name="save_profile">Save Profile</button>
        </form>
      </div>

      <!-- Security Settings Tab -->
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

      <!-- Notification Settings Tab -->
      <div class="tab-content" id="notifications" style="display:none;">
        <form method="POST">
          <div class="form-group">
            <label for="emailNotif">Email Notifications</label>
            <select name="email_notif" id="emailNotif">
              <option>Enabled</option>
              <option>Disabled</option>
            </select>
          </div>
          <div class="form-group">
            <label for="smsNotif">SMS Notifications</label>
            <select name="sms_notif" id="smsNotif">
              <option>Enabled</option>
              <option>Disabled</option>
            </select>
          </div>
          <button class="btn-save" name="save_notifications">Save Notifications</button>
        </form>
      </div>
    </div>
  </section>

  <!-- Scripts -->
  <script>
    // Sidebar toggle functionality
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

    // Tab switching functionality
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).style.display = 'block';
      });
    });
  </script>

</body>
</html>

