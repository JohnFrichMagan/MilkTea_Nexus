<?php
// admin_register.php
require_once 'config.php'; // Include your database connection

$register_error = ''; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate input
    if (empty($username)) {
        $register_error = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $register_error = "Username can only contain letters, numbers, and underscores.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $register_error = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirm_password) {
        $register_error = "Passwords do not match.";
    } else {
        try {
            $sql = "SELECT COUNT(*) FROM Admins WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->bind_result($user_exists);
            $stmt->fetch();
            $stmt->close();

            if ($user_exists) {
                $register_error = "This username or email is already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO Admins (username, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                $stmt->execute();

                header("Location: admin_login.php?registration=success");
                exit();
            }
            $conn->close();
        } catch (Exception $e) {
            $register_error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        .register-switch {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .switch-btn {
            background-color: #b0bec5;
            border: none;
            color: #000;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 5px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .switch-btn.active,
        .switch-btn:hover {
            background-color: #00bcd4;
            color: white;
        }

        h2 {
            text-align: center;
            color: #00bcd4;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #00897b;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #b2ebf2;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #00bcd4;
            outline: none;
        }

        .form-group .password-input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-group .password-input-container input {
            padding-right: 40px;
        }

        .form-group .password-toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #00897b;
            font-size: 20px;
        }

        .form-group button {
            background-color: #00bcd4;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #00acc1;
        }

        .form-group a {
            color: #00bcd4;
            text-decoration: none;
            font-size: 14px;
            display: block;
            text-align: center;
            margin-top: 12px;
        }

        .form-group a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ff4444;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-switch">
            <button onclick="window.location.href='admin_register.php'" class="switch-btn active">Admin Register</button>
            <button onclick="window.location.href='staff_register.php'" class="switch-btn">Staff Register</button>
        </div>

        <h2>Admin Signup</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Admin Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Create Admin Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" required>
                    <i class="bx bx-show password-toggle-icon" id="password-toggle"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Admin Password</label>
                <div class="password-input-container">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <i class="bx bx-show password-toggle-icon" id="confirm-password-toggle"></i>
                </div>
            </div>
            <div class="form-group">
                <button type="submit">Register Admin</button>
            </div>
            <div class="form-group">
                Already an admin? <a href="admin_login.php">Admin Login</a>
            </div>
            <?php if ($register_error): ?>
                <p class="error-message"><?php echo $register_error; ?></p>
            <?php endif; ?>
        </form>
    </div>

    <script>
        const passwordInput1 = document.getElementById("password");
        const passwordToggleIcon1 = document.getElementById("password-toggle");
        const passwordInput2 = document.getElementById("confirm_password");
        const passwordToggleIcon2 = document.getElementById("confirm-password-toggle");

        passwordToggleIcon1.addEventListener("click", function() {
            if (passwordInput1.type === "password") {
                passwordInput1.type = "text";
                this.classList.remove("bx-show");
                this.classList.add("bx-hide");
            } else {
                passwordInput1.type = "password";
                this.classList.remove("bx-hide");
                this.classList.add("bx-show");
            }
        });

        passwordToggleIcon2.addEventListener("click", function() {
            if (passwordInput2.type === "password") {
                passwordInput2.type = "text";
                this.classList.remove("bx-show");
                this.classList.add("bx-hide");
            } else {
                passwordInput2.type = "password";
                this.classList.remove("bx-hide");
                this.classList.add("bx-show");
            }
        });
    </script>
</body>
</html>
