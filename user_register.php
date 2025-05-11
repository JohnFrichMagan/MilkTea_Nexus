<?php
// user_register.php
require_once 'config.php'; // Include your database connection

$register_error = ''; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // **THIS LINE SHOULD BE THE VERY FIRST ONE INSIDE THE IF BLOCK**
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
            // Check if the username or email is already taken for an admin
            $sql = "SELECT COUNT(*) FROM User WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($sql); // Use $conn from config.php
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->bind_result($user_exists);
            $stmt->fetch();
            $stmt->close();

            if ($user_exists) {
                $register_error = "This username or email is already registered.";
            } else {
                // Hash the password before storing it in the database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the user table, including username
                $sql = "INSERT INTO User (username, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql); // Use $conn from config.php
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                $stmt->execute();

                // Redirect to the user login page after successful registration
                header("Location: user_login.php?registration=success");
                exit();
            }
            $conn->close(); // Close the connection

        } catch (Exception $e) {
            // Handle database errors
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
    <title>User Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f8f8;
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
            color: #000000;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #000000;
            font-weight: 600;
            font-size: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 18px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #00bcd4;
            outline: none;
        }

        .form-group button {
            background-color: #1F81C8;
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
            background-color: transparent;
            cursor: pointer;
        }


        .form-group .login-link {
            background-color: transparent;
            text-decoration: none;
            font-size: 16px;
            margin-left: 5px;
            color: #ff6f61;
            cursor: pointer;
        }


        .form-group p {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
        }

        .form-group a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ff4444;
            font-size: 14px;
            margin-top: 15px;
            text-align: center;
        }

        .password-input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-container input {
            padding-right: 40px;
        }

        .password-toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #7f8c8d;
            font-size: 20px;
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
        <h2>Signup</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Create Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" required>
                    <i class="far fa-eye password-toggle-icon" id="password-toggle"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="password-input-container">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <i class="far fa-eye password-toggle-icon" id="confirm_password-toggle"></i>
                </div>
            </div>
            <div class="form-group">
                <button type="submit">Signup</button>
            </div>
            <div class="form-group">
                <p>Already have an account?
                    <a href="user_login.php" class="login-link">Login</a>
                </p>
            </div>

            <?php if ($register_error): ?>
                <p class="error-message"><?php echo $register_error; ?></p>
            <?php endif; ?>
        </form>

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
    <script src="https://kit.fontawesome.com/9521706caf.js" crossorigin="anonymous"></script>
    <script>
        const passwordInput1 = document.getElementById("password");
        const passwordToggleIcon1 = document.getElementById("password-toggle");
        const passwordInput2 = document.getElementById("confirm_password");
        const passwordToggleIcon2 = document.getElementById("confirm_password-toggle");

        passwordToggleIcon1.addEventListener("click", function () {
            if (passwordInput1.type === "password") {
                passwordInput1.type = "text";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            } else {
                passwordInput1.type = "password";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            }
        });

        passwordToggleIcon2.addEventListener("click", function () {
            if (passwordInput2.type === "password") {
                passwordInput2.type = "text";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            } else {
                passwordInput2.type = "password";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            }
        });
    </script>
</body>
</html>
