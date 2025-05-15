<?php
require_once 'config.php'; 
session_start();

// Check if staff is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.php");
    exit();
}

// Fetch current staff data
$staff_id = $_SESSION['staff_id'];
$message = '';
$error = '';

// Fetch the current profile data
$sql = "SELECT name, email FROM staff WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Update profile info
if (isset($_POST['save_profile'])) {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $sql = "UPDATE staff SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_name, $new_email, $staff_id);
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
            $name = $new_name;
            $email = $new_email;
        } else {
            $error = "Update failed.";
        }
        $stmt->close();
    }
}

// Change password logic
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if current password is correct
    $sql = "SELECT password FROM staff WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $stored_password)) {
        $error = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Update the password
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $sql = "UPDATE staff SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password_hash, $staff_id);
        if ($stmt->execute()) {
            $message = "Password updated successfully.";
        } else {
            $error = "Password update failed.";
        }
        $stmt->close();
    }
}

// Delete account logic
if (isset($_POST['delete_account'])) {
    $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    header("Location: staff_register.php?account_deleted=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <title>Staff Settings | MILKTEA NEXUS</title>
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
      display: flex; justify-content: center; align-items: center; padding: 10px;
      background-color: #f0f0f0; border-radius: 5px;
    }
    .user-info {
      display: flex; align-items: center;
    }
    .user_name { margin-right: 8px; }
    .profile-icon svg {
      width: 24px; height: 24px; fill: rgba(0, 0, 0, 0.7);
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
      <<li><a href="staff_dashboard.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
            <li><a href="staff_financial_report.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Financial Reports</span></a></li>
            <li><a href="staff_customer_orders.php"><i class="bx bx-cart"></i><span class="links_name">Customer Orders</span></a></li>
            <li><a href="staff_settings.php"><i class="bx bx-cog"></i><span class="links_name">Settings</span></a></li>
            <li class="log_out"><a href="index.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
  </div>

  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class="bx bx-menu sidebarBtn"></i>
        <span class="dashboard">Settings</span>
      </div>
      <div class="profile-details">
        <img src="images/staff.jpg" alt="" />
        <span class="admin_name"><?php echo htmlspecialchars($name); ?></span>
        <i class="bx bx-chevron-down"></i>
      </div>
    </nav>

    <div class="content">
      <?php if ($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
      <?php endif; ?>
      <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
      <?php endif; ?>

      <div class="tabs">
        <div class="tab active" data-tab="profile">Profile</div>
        <div class="tab" data-tab="security">Security</div>
        <div class="tab" data-tab="notifications">Notifications</div>
      </div>

      <!-- Profile Settings -->
      <div class="tab-content" id="profile">
        <form method="POST">
          <div class="form-group">
            <label for="staffName">Name</label>
            <input type="text" name="name" id="staffName" value="<?php echo htmlspecialchars($name); ?>" required>
          </div>
          <div class="form-group">
            <label for="staffEmail">Email Address</label>
            <input type="email" name="email" id="staffEmail" value="<?php echo htmlspecialchars($email); ?>" required>
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

  <script>
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".sidebarBtn");
    if (sidebarBtn) {
        sidebarBtn.onclick = function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else {
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
            }
        };
    }

    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function() {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
        tab.classList.add('active');
        document.getElementById(tab.getAttribute('data-tab')).style.display = 'block';
      });
    });
  </script>
</body>
</html>
