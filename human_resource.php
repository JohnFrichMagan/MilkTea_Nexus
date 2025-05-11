<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title>Human Resource | MILKTEA NEXUS</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Basic styling for the schedule - you'll need more detailed CSS */
        .weekly-schedule {
            margin-top: 20px;
            background-color: #f8f9fa; /* Light background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Enable horizontal scrolling for smaller screens */
            display: flex; /* Enable Flexbox */
            flex-direction: column; /* Arrange items vertically */
        }


        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px; /* Add some space above the button */
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
            white-space: nowrap; /* Prevent text wrapping */
        }

        .schedule-table th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .employee-row td {
            background-color: #ffffff;
        }

        .shift {
            background-color: #d1ecf1; /* Light blue for shifts */
            padding: 8px;
            border-radius: 4px;
            margin: 2px;
            display: inline-block;
        }

        .add-team-member-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: auto; /* Push to the bottom */
            align-self: flex-start; /* Align to the left (default) */
            display: inline-block;
        }

        .add-team-member-button:hover {
            background-color: #0056b3;
        }

        .add-new-member-modal {
            display: none; /* Hidden by default */
            position: fixed;
            /* Remove top and transform to center vertically */
            /* top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); */
            left: 50%;
            bottom: 20px; /* Position it 20px from the bottom */
            transform: translateX(-50%); /* Center horizontally */
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .add-new-member-modal label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .add-new-member-modal input[type="text"],
        .add-new-member-modal select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .add-new-member-modal button {
            background-color: #a72828;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .add-new-member-modal button:hover {
            background-color: #1e7e34;
        }

        .add-new-member-modal .cancel-button {
            background-color: #dc3545;
        }

        .add-new-member-modal .cancel-button:hover {
            background-color: #c82333;
        }
    </style>
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
            </li>
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
                <a href="human_resource.php" class="active">
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
                <span class="dashboard">Human Resource</span>
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
                        <div class="box-topic">Total Employees</div>
                        <div class="number">35</div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">Active Staff</span>
                        </div>
                    </div>
                    <i class="bx bx-user cart"></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">On Leave</div>
                        <div class="number">3</div>
                        <div class="indicator">
                            <i class="bx bx-down-arrow-alt down"></i>
                            <span class="text">Currently Unavailable</span>
                        </div>
                    </div>
                    <i class="bx bx-user-minus cart two"></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">New Hires</div>
                        <div class="number">2</div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">This Month</span>
                        </div>
                    </div>
                    <i class="bx bx-user-plus cart three"></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Resigned</div>
                        <div class="number">1</div>
                        <div class="indicator">
                            <i class="bx bx-down-arrow-alt down"></i>
                            <span class="text">This Month</span>
                        </div>
                    </div>
                    <i class="bx bx-user-x cart four"></i>
                </div>
            </div>

            <div class="recent-sales box" style="flex: 1; margin-left: 20px;">
                <div class="title">Weekly Schedule</div>
                <div class="weekly-schedule">
                    <button class="add-team-member-button">+ Add Team Member</button>
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="employee-row">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="add-new-member-modal">
                <h3>Add New Member</h3>
                <label for="employeeName">Employee Name:</label>
                <input type="text" id="employeeName" name="employeeName">
                <label for="position">Position:</label>
                <select id="position" name="position">
                    <option value="Cashier">Cashier</option>
                    <option value="Staff">Staff</option>
                    <option value="Manager">Manager</option>
                </select>
                <button class="add-button">Add</button>
                <button class="cancel-button">Cancel</button>
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

        // JavaScript to handle the "Add Team Member" modal
        const addTeamMemberButton = document.querySelector(".add-team-member-button");
        const addNewMemberModal = document.querySelector(".add-new-member-modal");
        const cancelButton = document.querySelector(".cancel-button");
        const addButton = document.querySelector(".add-button");
        const scheduleTableBody = document.querySelector(".schedule-table tbody");
        const employeeNameInput = document.getElementById("employeeName");
        const positionSelect = document.getElementById("position");

        addTeamMemberButton.addEventListener("click", () => {
            addNewMemberModal.style.display = "block";
        });

        cancelButton.addEventListener("click", () => {
            addNewMemberModal.style.display = "none";
            employeeNameInput.value = ""; // Clear input
            positionSelect.value = "Cashier"; // Reset select
        });

        addButton.addEventListener("click", () => {
            const employeeName = employeeNameInput.value.trim();
            const position = positionSelect.value;
            const defaultShift = '9pm - 1pm'; // Define the default shift

            if (employeeName) {
                const newRow = scheduleTableBody.insertRow();
                const nameCell = newRow.insertCell();
                const monCell = newRow.insertCell();
                const tueCell = newRow.insertCell();
                const wedCell = newRow.insertCell();
                const thuCell = newRow.insertCell();
                const friCell = newRow.insertCell();
                const satCell = newRow.insertCell();
                const sunCell = newRow.insertCell();

                nameCell.textContent = employeeName;
                monCell.innerHTML = `<div class="shift">${defaultShift}<br>${position}</div>`;
                tueCell.innerHTML = `<div class="shift">${defaultShift}<br>${position}</div>`;
                wedCell.innerHTML = `<div class="shift">${defaultShift}<br>${position}</div>`;
                thuCell.innerHTML = `<div class="shift">${defaultShift}<br>${position}</div>`;
                friCell.innerHTML = `<div class="shift">${defaultShift}<br>${position}</div>`;
                satCell.innerHTML = `<div class="shift">${defaultShift}<br>${position}</div>`;
                sunCell.innerHTML = `<div class="shift">${defaultShift}<br>${position}</div>`;

                newRow.classList.add("employee-row");

                addNewMemberModal.style.display = "none";
                employeeNameInput.value = "";
                positionSelect.value = "Cashier"; // Reset to default after adding
            } else {
                alert("Please enter the employee name.");
            }
        });

        // You'll need to add more JavaScript to handle editing shifts
    </script>
</body>
</html>