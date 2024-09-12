<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Get equipment_id from the query string
$equipment_id = isset($_GET['equipment_id']) ? intval($_GET['equipment_id']) : 0;

// Validate equipment_id
if ($equipment_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid equipment ID']);
    $conn->close();
    exit();
}

// Prepare the SQL statement
$sql = "
    SELECT d.id, d.serials, d.location, d.date_rcvd, i.picture, i.name
    FROM equipment_details d
    JOIN equipment_info i ON d.equipment_id = i.id
    WHERE d.equipment_id = ? AND d.in_used = 'No'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $equipment_id); // Bind the equipment_id parameter

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    echo json_encode(['success' => true, 'items' => $items]);
} else {
    echo json_encode(['success' => false, 'message' => 'Query error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
