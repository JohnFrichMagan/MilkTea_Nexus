<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <title>Analytics | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Chart.js for future graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
    <div class="sidebar">
      <div class="logo-details">
        <i class="bx bx-coffee"></i>
        <span class="logo_name">MILKTEA NEXUS</span>
      </div>
      <ul class="nav-links">
        <li>
            <a href="admin_dashboard.php">
              <i class="bx bx-grid-alt"></i>
              <span class="links_name">Dashboard</span>
            </a>
            <li>
              <a href="product.php">
                <i class="bx bx-box"></i>
                <span class="links_name">Product</span>
              </a>
            </li>
            <li>
              <a href="analytics.php">
                <i class="bx bx-pie-chart-alt-2"></i>
                <span class="links_name">Analytics</span>
              </a>
            </li>
            <li>
              <a href="financial_reports.php">
                <i class="bx bx-line-chart  "></i>
                <span class="links_name">Financial Reports</span>
              </a>
            </li>
            <li>
              <a href="human_resource.php">
                <i class="bx bx-group"></i>
                <span class="links_name">Human Resource</span>
              </a>
            </li>
            <li>
                <a href="menu.php">
                  <i class="bx bx-menu"></i>
                  <span class="links_name">Menu</span>
                </a>
              </li>
            
            <li>
              <a href="settings.php">
                <i class="bx bx-cog"></i>
                <span class="links_name">Setting</span>
              </a>
            </li>
            <li class="log_out">
              <a href="index.php">
                <i class="bx bx-log-out"></i>
                <span class="links_name">Log out</span>
              </a>
            </li>
      </ul>
    </div>

    <section class="home-section">
      <nav>
        <div class="sidebar-button">
          <i class="bx bx-menu sidebarBtn"></i>
          <span class="dashboard">Analytics Dashboard</span>
        </div>
        <div class="search-box">
          <input type="text" placeholder="Search..." />
          <i class="bx bx-search"></i>
        </div>
        <div class="profile-details">
          <img src="images/profile.jpg" alt="" />
          <span class="admin_name">Admin</span>
          <i class="bx bx-chevron-down"></i>
        </div>
      </nav>

      <div class="home-content">
        <div class="overview-boxes">
          <div class="box">
            <div class="right-side">
              <div class="box-topic">Monthly Sales</div>
              <div class="number">₱15,240</div>
              <div class="indicator">
                <i class="bx bx-up-arrow-alt"></i>
                <span class="text">This Month</span>
              </div>
            </div>
            <i class="bx bx-bar-chart-alt-2 cart"></i>
          </div>
          <div class="box">
            <div class="right-side">
              <div class="box-topic">New Customers</div>
              <div class="number">89</div>
              <div class="indicator">
                <i class="bx bx-up-arrow-alt"></i>
                <span class="text">Since last week</span>
              </div>
            </div>
            <i class="bx bx-user-plus cart two"></i>
          </div>
          <div class="box">
            <div class="right-side">
              <div class="box-topic">Returning Customers</div>
              <div class="number">42</div>
              <div class="indicator">
                <i class="bx bx-down-arrow-alt down"></i>
                <span class="text">Compared to last month</span>
              </div>
            </div>
            <i class="bx bx-refresh cart three"></i>
          </div>
        </div>

        <div class="sales-boxes">
          <div class="recent-sales box">
            <div class="title">Sales Overview</div>
            <canvas id="salesChart" height="150"></canvas>
          </div>
        </div>
      </div>
    </section>

    <script>
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".sidebarBtn");
      sidebarBtn.onclick = function () {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
          sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      };

      // Chart.js Sample Line Chart
      const ctx = document.getElementById("salesChart").getContext("2d");
      const salesChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: ["Jan", "Feb", "Mar", "Apr", "May"],
          datasets: [{
            label: "Sales (₱)",
            data: [3000, 4500, 5000, 7000, 8000],
            backgroundColor: "rgba(91, 192, 222, 0.2)",
            borderColor: "#5bc0de",
            borderWidth: 2,
            fill: true,
            tension: 0.3,
          }],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: true,
              position: 'top'
            }
          }
        }
      });
    </script>
  </body>
</html>
