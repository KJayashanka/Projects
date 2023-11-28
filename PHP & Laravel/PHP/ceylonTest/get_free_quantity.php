<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylontask";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["product_id"]) && isset($_GET["purchase_quantity"])) {
    $product_id = $_GET["product_id"];
    $purchase_quantity = $_GET["purchase_quantity"];

    // Fetch purchase quantity rule from the "free_issues" table based on the product identifier
    $free_quantity = 0; // Initialize free quantity to 0
    $free_quantity_sql = "SELECT free_quantity, purchase_quantity FROM free_issues WHERE purchase_product_id = '$product_id'";
    $free_quantity_result = $conn->query($free_quantity_sql);

    if ($free_quantity_result->num_rows > 0) {
        $free_row = $free_quantity_result->fetch_assoc();
        $purchase_quantity_needed_for_free = $free_row["purchase_quantity"];
        if ($purchase_quantity >= $purchase_quantity_needed_for_free) {
            $free_quantity = floor($purchase_quantity / $purchase_quantity_needed_for_free);
        }
    }

    echo $free_quantity; // Send the free quantity back as a response
}

$conn->close();
