<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "borrower"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data
$sql = "SELECT id,first_name, middle_name, last_name, suffix, roles, item_to_borrow, locations, borrow_datetime, status FROM borrowers";
$result = $conn->query($sql);

$items = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

$conn->close();

// Return JSON response
echo json_encode($items);
?>
