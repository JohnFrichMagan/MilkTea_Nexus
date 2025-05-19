<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Menu | MILKTEA NEXUS</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
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

    .back-button {
      position: absolute;
      top: 20px;
      left: 20px;
      background-color: #d2b48c;
      color: black;
      padding: 10px 16px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: bold;
      font-size: 0.95rem;
      transition: background 0.3s ease;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    .back-button:hover {
      background-color: #d2b48c;
    }

    .menu-section {
      padding: 100px 40px 60px;
      text-align: center;
    }

    .menu-section h2 {
      font-size: 2.8rem;
      color: #000000;
      margin-bottom: 10px;
    }

    .menu-section p {
      color: #000000;
      margin-bottom: 40px;
      font-size: 1.1rem;
    }

    .menu-items {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .menu-card {
      background: #d2b48c;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .menu-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .menu-card img {
      width: 70%;
      height: 200px;
      object-fit: cover;
    }

    .menu-card-content {
      padding: 20px;
    }

    .menu-card h3 {
      font-size: 1.4rem;
      color: #000000;
      margin-bottom: 8px;
    }

    .menu-card p {
      font-size: 1rem;
      color: #000000;
      margin-bottom: 12px;
    }

    .price {
      font-weight: bold;
      color: #333;
      font-size: 1.1rem;
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
  </style>
</head>
<body>

  <a href="index.php" class="back-button"><i class='bx bx-arrow-back'></i> Back</a>

  <section class="menu-section">
    <h2>Our Menu</h2>
    <p>Explore our handcrafted milk tea flavors made with the freshest ingredients</p>

    <div class="menu-items">
      <div class="menu-card">
        <img src="https://i.pinimg.com/736x/bf/82/5d/bf825d56daf06fd8c231e2464b3e5d7d.jpg" alt="Chocolate Milk Tea" />
        <div class="menu-card-content">
          <h3>Chocolate Milk Tea</h3>
          <p>A smooth blend of black tea and creamy milk, sweetened to perfection.</p>
          <span class="price">₱100.00</span>
        </div>
      </div>

      <div class="menu-card">
        <img src="https://i.pinimg.com/736x/03/f6/fb/03f6fb4d51076c1d5b29657c5053907b.jpg" alt="Taro Milk Tea" />
        <div class="menu-card-content">
          <h3>Boba Milktea</h3>
          <p>Sweet and nutty boba flavor with a rich, creamy base.</p>
          <span class="price">₱100.00</span>
        </div>
      </div>

      <div class="menu-card">
        <img src="https://i.pinimg.com/736x/0f/ec/fd/0fecfd852b17df468585827b6673e816.jpg" alt="Wintermelon Milk Tea" />
        <div class="menu-card-content">
          <h3>Rosehip Milk Tea</h3>
          <p>Light, refreshing, and delicately sweet with roseship essence.</p>
          <span class="price">₱99.00</span>
        </div>
      </div>
  </section>

  <footer>
    &copy; 2025 MILKTEA NEXUS. All rights reserved.
  </footer>

</body>
</html>
