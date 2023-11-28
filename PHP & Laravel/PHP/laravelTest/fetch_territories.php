<?php
// fetch_territories.php
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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["zone"]) && isset($_GET["region"])) {
    $zone = $_GET["zone"];
    $region = $_GET["region"];

    // Fetch relevant territories based on the selected zone and region
    $territory_query = "SELECT territory_name FROM territories WHERE region_name = '$region'";
    $territory_result = $conn->query($territory_query);

    if ($territory_result->num_rows > 0) {
        $territory_options = "";
        while ($row = $territory_result->fetch_assoc()) {
            $territory_options .= "<option>{$row["territory_name"]}</option>";
        }
        echo $territory_options;
    } else {
        echo "<option value='' disabled selected>No territories found</option>";
    }
}
?>
