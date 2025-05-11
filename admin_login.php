<?php
// admin_login.php
session_start();
require_once 'config.php'; // Include your database connection

$login_error = ''; // Initialize error message
$email = ''; // Initialize email variable to avoid undefined variable warning

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT id, email, password FROM Admins WHERE email = ?";
    $stmt = $conn->prepare($sql); // Use $conn from config.php
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            // Password is correct. Set admin session variables and redirect to the admin dashboard
            $_SESSION["admin_loggedin"] = true;
            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_email"] = $admin["email"];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $login_error = "Invalid admin password.";
        }
    } else {
        $login_error = "Invalid admin email.";
    }
    $stmt->close();
    $conn->close(); // Close the connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e0f7fa; /* A different background color for admin area */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #E2AD7E;
            border-radius: 12px;
            padding: 80px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        h2 {
            text-align: center;
            color:rgb #000000; /* A different heading color for admin */
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #0D0C0C; /* A different label color for admin */
            font-weight: 500;
            font-size: 18px;
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
            color: #000000;
            font-size: 20px;
        }

        .form-group.forgot-password { /* Added this style rule */
            text-align: center;
        }

        .form-group.forgot-password a { /* Modified this style rule */
            display: inline-block;
            color: #0061D0;
            text-decoration: none;
            font-size: 14px;
            margin-top: 12px;
        }

        .form-group.forgot-password a:hover {
            text-decoration: underline;
        }

        .form-group button {
            background-color: #00bcd4; /* A different button color for admin */
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

        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #000000;
        }

        .signup-link a {
            color: #0061D0;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Admin Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Admin Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" required>
                    <i class="bx bx-show password-toggle-icon" id="password-toggle"></i>
                </div>
            </div>
            <div class="form-group forgot-password"> <a href="#">Forgot admin password?</a>
            </div>
            <div class="form-group">
                <button type="submit">Admin Login</button>
            </div>
        </form>
        <div class="signup-link">
           Don't have an admin account? <a href="admin_register.php">Admin Signup</a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById("password");
        const passwordToggleIcon = document.getElementById("password-toggle");

        passwordToggleIcon.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                this.classList.remove("bx-show");
                this.classList.add("bx-hide");
            } else {
                passwordInput.type = "password";
                this.classList.remove("bx-hide");
                this.classList.add("bx-show");
            }
        });
    </script>
</body>
</html>