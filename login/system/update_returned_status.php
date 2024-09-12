<?php
// update_returned_status.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "borrower";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE borrowers SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Close connections
$stmt->close();
$conn->close();
?>

