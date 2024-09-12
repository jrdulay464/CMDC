<?php
session_start();
header('Content-Type: application/json');

// Database connection for user info
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    $conn->close();
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT first_name, last_name, phone, user_type FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    $stmt->close();
    $conn->close();
    exit();
}

$userData = $userResult->fetch_assoc();
$stmt->close();

// Get item ID from query string
$itemId = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

// Validate item ID
if ($itemId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid item ID']);
    $conn->close();
    exit();
}

// Database connection for equipment info
$equipmentDb = "equipment";
$conn->select_db($equipmentDb);

$sql = "
    SELECT d.id, d.serials, d.location, d.date_rcvd, i.picture, i.name
    FROM equipment_details d
    JOIN equipment_info i ON d.equipment_id = i.id
    WHERE d.id = ? AND d.in_used = 'No'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$equipmentResult = $stmt->get_result();

if ($equipmentResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Item not found or already in use']);
    $stmt->close();
    $conn->close();
    exit();
}

$itemData = $equipmentResult->fetch_assoc();
$stmt->close();
$conn->close();

// Combine user and item data
$response = [
    'success' => true,
    'userInfo' => [
        'name' => $userData['first_name'] . ' ' . $userData['last_name'],
        'phone' => $userData['phone'],
        'user_type' => $userData['user_type']
    ],
    'itemInfo' => [
        'name' => $itemData['name'],
        'serials' => $itemData['serials'],
        'location' => $itemData['location'],
        'date_rcvd' => $itemData['date_rcvd'],
        'picture' => $itemData['picture']
    ]
];

echo json_encode($response);
?>
