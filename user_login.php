<?php
session_start();
require_once 'config.php'; // Include your database connection

$login_error = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT id, email, password FROM User WHERE email = ?"; // Changed user_id to id to match the table definition
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION["user_loggedin"] = true;
                $_SESSION["user_id"] = $user["id"]; // Changed user_id to id
                $_SESSION["user_email"] = $user["email"];
                header("Location: user_dashboard.php"); // Redirect to user_dashboard.php
                exit();
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "Invalid email.";
        }
        $stmt->close();

    } catch (Exception $e) {
        $login_error = "Database error: " . $e->getMessage(); //catch any database error.
    } finally{
        $conn->close(); // Close the connection
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
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
            padding: 50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        h2 {
            text-align: center;
            color: rgb(0, 0, 0);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #0D0C0C;
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

        .form-group.forgot-password {
            text-align: center;
        }

        .form-group.forgot-password a {
            display: inline-block;
            color: #0061D0;
            text-decoration: none;
            font-size: 18px;
            font-weight: bolder;
            margin-top: 12px;
        }

        .form-group.forgot-password a:hover {
            text-decoration: underline;
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
            margin-top: 10px;
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

         .or-separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #7f8c8d;
        }

        .or-separator::before,
        .or-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
            margin: 0 10px;
        }

        .social-icons {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: center;
            margin-top: 1rem;
        }

        .social-icons a img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .social-icons a img:hover {
            transform: scale(1.1);
        }


        #facebook-icon {
            color: #1877f2;
        }

        #google-icon {
            color: #db4437;
        }

        #tiktok-icon {
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" required>
                    <i class="bx bx-show password-toggle-icon" id="password-toggle"></i>
                </div>
            </div>
            <div class="form-group forgot-password">
                <a href="#">Forgot password?</a>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="user_register.php">Sign Up</a>
        </div>

        <!-- OR and Social Icons Now Below -->
        <div class="or-separator">Or</div>
        <div class="social-icons">
            <a href="https://facebook.com/yourpage" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook">
            </a>
            <a href="#">
                <img src="https://cdn-icons-png.flaticon.com/512/300/300221.png" alt="Google">
            </a>
            <a href="#">
                <img src="https://cdn-icons-png.flaticon.com/512/3046/3046122.png" alt="TikTok">
            </a>
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
