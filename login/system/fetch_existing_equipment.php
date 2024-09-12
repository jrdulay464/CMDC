<?php
header('Content-Type: application/json');

// Database connection details
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'equipment';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Query to get existing equipment data
$sql = "SELECT id, name FROM equipment_info";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Query error: ' . $conn->error]);
    exit();
}

if ($result->num_rows > 0) {
    $equipment = [];
    while ($row = $result->fetch_assoc()) {
        $equipment[] = $row;
    }
    echo json_encode(['success' => true, 'equipment' => $equipment]);
} else {
    echo json_encode(['success' => false, 'message' => 'No equipment found']);
}

$conn->close();
?>
