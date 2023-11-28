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

// Check if the region_id parameter is provided in the GET request
if (isset($_GET['region_id'])) {
    $region_id = $_GET['region_id'];

    // Prepare a query to fetch territories based on the provided region_id
    $sql = "SELECT * FROM territories WHERE region_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $region_id);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $territories = array();

        // Fetch and store territories in an array
        while ($row = $result->fetch_assoc()) {
            $territories[] = $row;
        }

        // Return territories data as JSON
        header('Content-Type: application/json');
        echo json_encode($territories);
    } else {
        // Handle the database query error
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle the case where region_id is not provided in the request
    echo "Region ID is missing in the request.";
}
?>
