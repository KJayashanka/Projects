<?php
// Database configuration (similar to your original code)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laravel-test";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve regions based on the selected zone
if (isset($_GET["zone_id"])) {
    $zone_id = $_GET["zone_id"];
    $sql = "SELECT * FROM regions WHERE zone_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $zone_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $regions = array();
    while ($row = $result->fetch_assoc()) {
        $regions[] = $row;
    }

    echo json_encode($regions);
}

$conn->close();
?>
