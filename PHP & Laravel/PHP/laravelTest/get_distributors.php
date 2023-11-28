<?php
// Database connection configuration (replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'laravel-test';

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$regionId = isset($_GET['region_id']) ? $_GET['region_id'] : null;
$territoryId = isset($_GET['territory_id']) ? $_GET['territory_id'] : null;

// Prepare a query to fetch distributors based on region or territory
if (!empty($regionId)) {
    $sql = "SELECT * FROM users WHERE region_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $regionId);
} elseif (!empty($territoryId)) {
    $sql = "SELECT * FROM users WHERE territory_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $territoryId);
} else {
    // If neither region_id nor territory_id is provided, return an empty array
    echo json_encode([]);
    exit;
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$distributors = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $distributors[] = $row;
    }
}

// Return distributor data as JSON
header('Content-Type: application/json');
echo json_encode($distributors);

// Close the database connection
$stmt->close();
$conn->close();
?>
