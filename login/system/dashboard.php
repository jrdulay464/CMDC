<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("logo/BG.jpg");
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow-x: hidden; /* Hide horizontal scrollbar */
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px;
            background-color: #2980b9; /* Navbar background color */
        }

        .navbar-logo {
            width: 80px;
            margin-left: 20px;
        }

        .admin-container {
            position: relative;
            margin-left: -20px; /* Adjusted margin to move more to the left */
        }

        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            margin-left: -100px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 120px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
            margin-left: -150px; /* Adjusted margin to align with profile picture */
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .show {
            display: block;
        }

        .container-wrapper {
            display: flex;
            width: 80%;
            margin: 80px auto; /* Adjusted margin */
        }

        .container {
            width: 48%; /* Adjusted width for each container */
            background-color: lightblue; /* Container background color */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            height: 800px;
        }

        .container:first-child {
            margin-right: 4%; /* Add margin to create space */
        }

        h1 {
            color: #333; /* Heading color */
            margin-top: 0;
        }

        .dashboard-buttons {
            display: flex;
            flex-direction: column; /* Align buttons vertically */
            margin-bottom: 20px;
            margin-left: 20px; /* Adjusted margin */
        }

        .dashboard-button {
            background-color: #2980b9; /* Button background color */
            color: #fff; /* Button text color */
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin-bottom: 10px; /* Added margin between buttons */
        }

        .dashboard-button:hover {
            background-color: #2471a3; /* Button hover background color */
        }

        .picture img {
            border-radius: 50%; /* Make the image round */
            width: 50px; /* Adjusted image width */
            height: 50px; /* Adjusted image height */
            object-fit: cover; /* Maintain aspect ratio */
        }
    </style>
</head>
<body>
<div class="navbar">
    <img src="logo/logo.jpg" alt="Logo" class="navbar-logo">
    <div class="admin-container">
        <?php
            // Connect to database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "login_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch user information
            $sql = "SELECT first_name, last_name, student_id FROM user WHERE username = 'current_logged_in_user'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<div class='user-info'>";
                    echo "<p>" . $row["first_name"] . " " . $row["last_name"] . "</p>";
                    echo "<p>Student ID: " . $row["student_id"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
        ?>

        <img src="logo/logo.jpg" alt="Profile Picture" class="profile-picture" onclick="toggleDropdown()">
        <div class="dropdown-content" id="dropdown-content">
            <a href="#">Profile</a>
            <a href="#">Logout</a>
        </div>
    </div>
</div>

<div class="container-wrapper">
    <div class="container">
        <h1>Dashboard</h1>

        <!-- Dashboard buttons -->
        <div class="dashboard-buttons">
            <div class="picture">
                <img src="pictures/logo.jpg" alt="Logo">
            </div>
        </div>
    </div>

    <!-- Second container -->
    <div class="container">
        <!-- You can add content here -->
    </div>
</div>

<script>
    function toggleDropdown() {
        var dropdownContent = document.getElementById("dropdown-content");
        dropdownContent.classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.profile-picture')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

</body>
</html>
