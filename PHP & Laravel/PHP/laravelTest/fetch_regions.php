<?php
// fetch_regions.php
include("header1.html");

// Database connection configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'laravel-test';

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["zone"])) {
    $zone = $_GET["zone"];

    // Fetch relevant regions based on the selected zone
    $region_query = "SELECT region_name FROM regions WHERE zone_code = '$zone'";
    $region_result = $conn->query($region_query);

    if ($region_result->num_rows > 0) {
        $region_options = "";
        while ($row = $region_result->fetch_assoc()) {
            $region_options .= "<option>{$row["region_name"]}</option>";
        }
        echo $region_options;
    } else {
        echo "<option value='' disabled selected>No regions found</option>";
    }
}
?>
