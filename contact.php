<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us | MILKTEA NEXUS</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20210903/pngtree-milk-tea-pearl-milk-image_792621.jpg');
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

    .contact-section {
      padding: 100px 20px 60px;
      max-width: 1000px;
      margin: auto;
      text-align: center;
    }

    .contact-section h2 {
      font-size: 2.6rem;
      color: #000000;
      margin-bottom: 10px;
    }

    .contact-section p {
      font-size: 1.05rem;
      color: #000000;
      margin-bottom: 40px;
    }

    .contact-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
    }

    .card {
      background: #d2b48c;
      padding: 30px 20px;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      flex: 1 1 300px;
      text-align: center;
    }

    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #000000;
      margin-bottom: 15px;
    }

    .card h3 {
      color: #000000;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 1rem;
      color: #000000;
      margin: 8px 0;
    }

    .card i {
      color: #000000;
      margin-right: 10px;
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

  <section class="contact-section">
    <h2>Contact Us</h2>
    <p>Feel free to reach out to us for collaborations, orders, or inquiries!</p>

    <div class="contact-cards">
      <!-- Your Info -->
      <div class="card">
        <img src="images/magan.jpg" alt="Owner Image" class="profile-img" />
        <h3>John Frich Magan</h3>
        <p><i class='bx bx-envelope'></i> johnfrichmagan@gmail.com</p>
        <p><i class='bx bx-phone'></i> +63 912 345 6789</p>
        <p><i class='bx bx-map'></i> Mantibugao, Philippines</p>
      </div>

      <!-- Partner Info -->
      <div class="card">
        <img src="images/apple.jpg" alt="Partner Image" class="profile-img" />
        <h3>Apple Sairah Rezano</h3>
        <p><i class='bx bx-envelope'></i> applerezano@gmail.com</p>
        <p><i class='bx bx-phone'></i> +63 987 654 3210</p>
        <p><i class='bx bx-map'></i>Lingion , Philippines</p>
      </div>
    </div>
  </section>

  <footer>
    &copy; 2025 MILKTEA NEXUS. All rights reserved.
  </footer>

</body>
</html>
