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

// Get login credentials from form
$inputUsername = $_POST['username'] ?? '';
$inputPassword = $_POST['password'] ?? '';

// Prepare and execute query to check credentials
$sql = "SELECT id, pword, user_type, attempts, lockout_time FROM user WHERE student_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $inputUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Check if the account is locked
    if ($user['lockout_time'] && (time() - strtotime($user['lockout_time'])) < 300) {
        $_SESSION['error'] = "Account is locked due to too many failed login attempts. Please try again later.";
        header("Location: http://localhost/login/index.php");
        exit();
    }

    // Verify the plain text password
    if ($inputPassword === $user['pword']) {
        // Password is correct, reset login attempts and lockout time
        $updateStmt = $conn->prepare("UPDATE user SET attempts = 0, lockout_time = NULL WHERE student_id = ?");
        $updateStmt->bind_param("s", $inputUsername);
        $updateStmt->execute();
        $updateStmt->close();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        header("Location: http://localhost/login/system/dashboard.html");
        exit();
    } else {
        // Incorrect password, increment login attempts
        $newAttempts = $user['attempts'] + 1;

        // Lock the account if attempts reach 5
        $lockoutTime = ($newAttempts >= 5) ? date("Y-m-d H:i:s") : NULL;

        $updateStmt = $conn->prepare("UPDATE user SET attempts = ?, lockout_time = ? WHERE student_id = ?");
        $updateStmt->bind_param("iss", $newAttempts, $lockoutTime, $inputUsername);
        $updateStmt->execute();
        $updateStmt->close();

        $_SESSION['error'] = "Invalid Password. Attempt $newAttempts of 5.";
        header("Location: http://localhost/login/index.php");
        exit();
    }
} else {
    // Username not found
    $_SESSION['error'] = "Account not found.";
    header("Location: http://localhost/login/index.php");
    exit();
}

// Close connection
$stmt->close();
$conn->close();
?>
