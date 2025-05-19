<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MILKTEA NEXUS Landing Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
            body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20210903/pngtree-milk-tea-pearl-milk-image_792621.jpg'); /* Replace with your actual image URL */
    background-size: cover;
    background-position: center top;
    background-repeat: no-repeat;
    min-height: 100vh;
    box-sizing: border-box;
}


        header {
            background-color: rgba(240, 240, 240, 0.8);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px; /* space between logo and text */
        }    
        .logo img {
            width: 40px; /* adjust as needed */
            height: auto;
       }
        .logo_name {
            font-size: 18px;
            font-weight: bold;
            color: #000000; /* or any color matching your design */
     }
        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

       nav ul li a {
    text-decoration: none;
    color: #000000;
    font-weight: 900; /* Extra bold */
    padding: 0.5rem 1rem;
    display: block;
    transition: color 0.3s ease;
}


        nav ul li a:hover {
            color: #d2b48c;
        }

        .login-button {
            background-color: #d2b48c;
            color: black;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-size: 1em;
        }

        .login-button:hover {
            background-color: #a0855b;
        }

        #hero {
            padding: 8rem 2rem;
            text-align: center;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 60px);
            box-sizing: border-box;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-content h1 {
            font-size: 4em;
            margin-bottom: 20px;
            color: #333;
        }

        .hero-content p {
            font-size: 1.5em;
            margin-bottom: 30px;
            color: #000000;
        }

        #hero .login-button {
            font-size: 1.2em;
            padding: 1rem 2rem;
        }

        footer {
            background-color: rgba(51, 51, 51, 0.8);
            color: white;
            text-align: center;
            padding: 1rem;
            position: relative;
            box-sizing: border-box;
        }

        footer p {
            margin: 0;
            font-size: 0.9em;
        }
        p {
    font-size: 1rem;           /* Adjust size as needed */
    font-weight: 500;          /* Medium boldness */
    color: #000000;            /* Dark gray for readability */
    line-height: 1.6;          /* Better line spacing */
    margin-bottom: 1rem;       /* Space below paragraphs */
    font-family: 'Poppins', sans-serif;  /* Matches your theme */
}

    </style>
</head>
<body>
    <header>
    <div class="logo">
  <img src="images/milktea.png" alt="MILKTEA NEXUS Logo">
  <span class="logo_name">MILKTEA NEXUS</span>
</div>

        <nav>
            <ul>
                <li><a href="index.php" class="active"><span class="links_name">Home</span></a></li>
                <li><a href="menus.php" class="active"><span class="links_name">Menu</span></a></li>
                <li><a href="about.php" class="active"><span class="links_name">AboutUs</span></a></li>
                <li><a href="contact.php" class="active"><span class="links_name">ContactUs</span></a></li>
            </ul>
        </nav>
        <a href="admin_login.php" class="login-button">Admin & Staff Login</a>
    </header>

    <main>
        <section id="hero">
            <div class="hero-content">
                <h1>WELCOME TO MILKTEA NEXUS</h1>
                <p>With just a few clicks, you can enjoy the smart way to order your favorite milk tea!</p>
                <a href="user_login.php" class="login-button">LOGIN</a>

            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 MILKTEA NEXUS</p>
    </footer>
</body>
</html>
