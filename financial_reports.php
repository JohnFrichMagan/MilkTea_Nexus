<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <title>Financial Reports | MILKTEA NEXUS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body { font-family: 'Poppins', sans-serif; background: #c1956c; margin: 0; }
    .content { margin-left: 240px; padding: 20px; }
    .page-title { font-size: 24px; font-weight: 600; margin-bottom: 20px; color: #333; }
    .cards { display: flex; gap: 20px; flex-wrap: wrap; }
    .card {
      background: #FFDCC1; flex: 1;
      min-width: 200px; padding: 20px;
      border-radius: 50px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .card .topic { font-size: 14px; color: #666; }
    .card .number { font-size: 28px; font-weight: 600; margin-top: 5px; }
    .chart-container {
      background: #FFDCC1;
  padding: 20px;
  border-radius: 50px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  margin-top: 50px;
  width: 80%; /* Change this to your desired width */
  margin-left: 50px;
  margin-right: 150px;
}
    .table-container {
      background: #FFDCC1;
  padding: 20px;
  border-radius: 50px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  margin-top: 50px;
  width: 80%; /* Change this to your desired width */
  margin-left: 50px;
  margin-right: 150px;
}
    .filter {
      margin-bottom: 10px;
      display: flex; justify-content: flex-end; gap: 10px;
    }
    .filter input, .filter select {
      padding: 6px 10px; border-radius: 6px; border: 1px solid #ccc;
    }
    table {
      width: 100%; border-collapse: collapse;
    }
    th, td {
      text-align: left; padding: 10px; border-bottom: 1px solid #eee;
    }
    th { background: #f5f5f5; }
    /* Add or modify these styles in your style.css */
.top-selling-container {
  background: #FFDCC1;
  padding: 20px;
  border-radius: 50px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  margin-top: 50px;
  width: 80%; /* Change this to your desired width */
  margin-left: 50px;
  margin-right: 150px;
}


.top-selling-title {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 15px;
  color: #333;
}

.top-selling-chart-container {
  position: relative; /* For positioning the legend if needed */
  width: 200px; /* Adjust the size of the chart */
  height: 200px;
  margin: 0 auto; /* Center the chart */
}

.top-selling-list {
  list-style: none;
  padding: 0;
  margin-top: 10px;
  text-align: left; /* Align the list to the left */
}

.top-selling-list li {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
}

.color-indicator {
  display: inline-block;
  width: 10px;
  height: 10px;
  margin-right: 5px;
  border-radius: 50%;
}

/* Define colors for each product */
.color-chocolate { background-color: #36a2eb; }
.color-boba { background-color: #ff6384; }
.color-purple { background-color: #ff9f40; }
.color-matcha { background-color: #4bc0c0; }
.color-strawberry { background-color: #9966ff; }
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
          <a href="#index.php">
            <i class="bx bx-log-out"></i>
            <span class="links_name">Log out</span>
          </a>
        </li>
    </ul>
  </div>

  <!-- Main Content -->
 <section class="home-section">
  <nav>
    <div class="sidebar-button">
      <i class="bx bx-menu sidebarBtn"></i>
      <span class="dashboard">Financial Reports</span>
    </div>
    <div class="search-box">
      <input type="text" placeholder="Search..." />
      <i class="bx bx-search"></i>
    </div>
    <div class="profile-details">
      <?php if (isset($_SESSION["email"])): ?>
        <span class="admin_name"><?php echo htmlspecialchars($_SESSION["email"]); ?></span>
      <?php else: ?>
        <span class="admin_name">Guest</span>
      <?php endif; ?>
      <div class="profile-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M12 4a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
        </svg>
      </div>
    </div>
  </nav>

  <div class="content">
    <div class="top-selling-container">
      <div class="top-selling-title">Top Selling Products</div>
      <div class="top-selling-chart-container">
        <canvas id="topSellingChart"></canvas>
      </div>
      <ul class="top-selling-list">
        <li><span class="color-indicator color-chocolate"></span> Chocolate Bubble Tea - 46%</li>
        <li><span class="color-indicator color-boba"></span> Boba Milk Tea - 31%</li>
        <li><span class="color-indicator color-purple"></span> Purple Boba Milk Tea - 13%</li>
        <li><span class="color-indicator color-matcha"></span> Match Milk Tea - 7%</li>
        <li><span class="color-indicator color-strawberry"></span> Strawberry Milk Tea - 3%</li>
      </ul>
    </div>
    <div class="chart-container">
      <canvas id="financeChart" height="100"></canvas>
    </div>
    <div class="table-container">
      <div class="filter">
        <input type="text" id="quarterSearch" placeholder="Search quarter..." onkeyup="filterQuarterTable()">
        <select id="yearFilter" onchange="filterQuarterTable()">
          <option value="">All Years</option>
          <option>2023</option>
          <option>2024</option>
          <option>2025</option>
        </select>
      </div>
      <table id="quarterTable">
        <thead>
          <tr><th>Quarter</th><th>Year</th><th>Revenue</th><th>Expenses</th><th>Profit</th></tr>
        </thead>
        <tbody>
          <tr><td>Q1</td><td>2024</td><td>₱300,000</td><td>₱180,000</td><td>₱120,000</td></tr>
          <tr><td>Q2</td><td>2024</td><td>₱350,000</td><td>₱210,000</td><td>₱140,000</td></tr>
          <tr><td>Q3</td><td>2024</td><td>₱320,000</td><td>₱200,000</td><td>₱120,000</td></tr>
          <tr><td>Q4</td><td>2024</td><td>₱280,000</td><td>₱190,000</td><td>₱90,000</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

  
  <script>
    // Sidebar toggle (Keep this)
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
  
    // Chart.js: Revenue vs Expenses (Keep this)
    const ctx = document.getElementById('financeChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
          {
            label: 'Revenue',
            data: [100,120,140,150,160,170,180,190,200,210,220,230].map(v=>v*10000),
            borderColor: '#5bc0de',
            backgroundColor: 'rgba(91,192,222,0.2)',
            tension: 0.3,
            fill: true
          },
          {
            label: 'Expenses',
            data: [80,90,100,110,120,130,140,150,160,170,180,190].map(v=>v*10000),
            borderColor: '#f66',
            backgroundColor: 'rgba(246,102,102,0.2)',
            tension: 0.3,
            fill: true
          }
        ]
      },
      options: { responsive: true }
    });

    
  
    // Table Filter (Keep this)
    function filterQuarterTable() {
      const search = document.getElementById('quarterSearch').value.toLowerCase();
      const year = document.getElementById('yearFilter').value;
      document.querySelectorAll('#quarterTable tbody tr').forEach(row => {
        const q = row.cells[0].textContent.toLowerCase();
        const y = row.cells[1].textContent;
        const matchSearch = q.includes(search);
        const matchYear = !year || y === year;
        row.style.display = (matchSearch && matchYear) ? '' : 'none';
      });
    }
  
    // Top Selling Products Chart (Add this)
    const topSellingChartCanvas = document.getElementById('topSellingChart');
  
    if (topSellingChartCanvas) {
      const topSellingCtx = topSellingChartCanvas.getContext('2d');
  
      new Chart(topSellingCtx, {
        type: 'doughnut',
        data: {
          labels: ['Chocolate Bubble Tea', 'Boba Milk Tea', 'Purple Boba Milk Tea', 'Match Milk Tea', 'Strawberry Milk Tea'],
          datasets: [{
            data: [46, 31, 13, 7, 3],
            backgroundColor: [
              '#36a2eb', // Blue
              '#ff6384', // Red
              '#ff9f40', // Orange
              '#4bc0c0', // Green
              '#9966ff'  // Purple
            ],
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false, // Hide the default legend
            }
          }
        }
      });
    }

    
  </script>

</body>
</html>
