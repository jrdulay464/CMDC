<?php
session_start();

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

// Fetch user ID from session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT student_id, user_type, profile_picture, phone, course, first_name,last_name FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Determine if the user is an admin
$isAdmin = $userData['user_type'] === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        .button {
            background-color: #2980b9;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #3498db;
        }
        .form-group {
            margin-bottom: 15px;

        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 100%;
            padding: 10px;
            background-color: #2980b9; /* Navbar background color */
            display: flex;
            justify-content: space-between; /* Align items to both ends */
            align-items: center;
            padding-bottom: 50px;
        }
        .admin-container {
            position: absolute;
            right: 20px;
            top: 10px;
            display: flex;
            align-items: center;
        }
        .admin-container p {
            margin-right: 10px;
            color: white;
            font-weight: bold;
        }
        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 120px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
            top: 50px;
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
        .menu {
            position: fixed;
            top: 60px;
            left: 0;
            width: 300px;
            height: 100%;
            background-color: #2980b9;
            transition: left 0.3s ease;
            z-index: 1000;
        }
        .menu a {
            display: block;
            color: white;
            padding: 20px;
            text-decoration: none;
            border-bottom: 1px solid #555;
            transition: background-color 0.3s ease;
        }
        .menu a:hover {
            background-color: #2471a3;
        }
        .menu-toggle {
            position: fixed;
            top: 10px;
            left: 10px;
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 1001;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <button class="menu-toggle" onclick="toggleMenu()">☰ Menu</button>
        <div class="admin-container">
            <p id="user-name"><?php echo htmlspecialchars($userData['first_name']); ?></p>
            <img src="uploads/<?php echo htmlspecialchars($userData['profile_picture']); ?>" alt="Profile Picture" class="profile-picture" onclick="toggleDropdown()">
            <div class="dropdown-content" id="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="../index.php">Logout</a>
            </div>
        </div>
    </div>

    <!-- Slide-in Menu -->
    <div class="menu" id="menu">
        <a href="dashboard.html">Dashboard</a>
        <a href="inventory.html" id="inventory-link">Add Equipment/Item</a>
        <a href="catalog.html">List of Equipment</a>
        <a href="borrow.html" id="borrow-link">Borrowed Equipment History</a>
        <a href="returned.html" id="returned-link">Returned Equipment History</a>
        <a href="not_returned.html" id="not-returned-link">Not Returned Equipment History</a>
        <a href="lost.html" id="lost-link">Lost Equipment History</a>
        <a href="damaged.html" id="damaged-link">Damaged Equipment History</a>
    </div>
    <div class="container">
        <h1>Profile</h1>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <img src="uploads/<?php echo htmlspecialchars($userData['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                <input type="file" name="profile_picture">
            </div>
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($userData['student_id']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="student_id">Student Full Name:</label>
                <input type="text" id="student_name" name="student_name" value="<?php echo htmlspecialchars($userData['first_name']); echo htmlspecialchars($userData['last_name']); ?>" readonly>
             <div class="form-group">
                <label for="student_id">Student Phone Number:</label>
                <input type="text" id="student_phone" name="student_phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" readonly>
            </div>   
            </div>
            <div class="form-group">
                <label for="user_type">User Type:</label>
                <input type="text" id="user_type" name="user_type" value="<?php echo htmlspecialchars($userData['user_type']); ?>" readonly>
            </div>
            <button type="submit" class="button">Update Profile</button>
        </form>

        <script>
            function toggleDropdown() {
                var dropdownContent = document.getElementById("dropdown-content");
                dropdownContent.classList.toggle("show");
            }

            function toggleMenu() {
                var menu = document.getElementById("menu");
                var menuLeft = menu.style.left === "0px" ? "-300px" : "0px";
                menu.style.left = menuLeft;
            }

            function adjustUIForUserType(isAdmin) {
                const historyLinks = document.querySelectorAll('#menu a[id$="-link"]');
                historyLinks.forEach(link => link.style.display = isAdmin ? 'block' : 'none');
                const inventoryLink = document.getElementById('inventory-link');
                if (isAdmin) {
                    inventoryLink.style.display = 'block';
                } else {
                    inventoryLink.style.display = 'none';
                }
            }

            // Initialize UI based on user type
            document.addEventListener('DOMContentLoaded', function() {
                adjustUIForUserType(<?php echo json_encode($isAdmin); ?>);
            });
        </script>
    </div>
</body>
</html>
