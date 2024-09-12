<?php
header('Content-Type: application/json');

// Database credentials
$servername = "localhost"; // or your database host
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "equipment"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $alert_level = isset($_POST['alert_level']) ? (int)$_POST['alert_level'] : 0;
    $description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : '';
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;

    // Handle file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileSize = $_FILES['picture']['size'];
        $fileType = $_FILES['picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Define allowed file extensions and size
        $allowedExts = ['jpg', 'jpeg', 'png'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        if (in_array($fileExtension, $allowedExts) && $fileSize <= $maxFileSize) {
            // Directory where the file will be uploaded
            $uploadDir = 'uploads/';
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadFileDir)) {
                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO equipment_info (name, alert_level, description, price, picture) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sisss", $name, $alert_level, $description, $price, $newFileName);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Equipment added successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add equipment: ' . $stmt->error]);
                }

                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type or size']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
