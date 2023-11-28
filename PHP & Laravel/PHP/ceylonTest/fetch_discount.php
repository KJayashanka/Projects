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

if (
    isset($_GET["product_id"]) &&
    isset($_GET["purchase_quantity"]) &&
    isset($_GET["discount"])
) {
    $product_id = $_GET["product_id"];
    $purchase_quantity = $_GET["purchase_quantity"];
    $discount = $_GET["discount"];

    // Fetch discount data from the database
    $discount_sql = "SELECT discount_amount FROM discounts WHERE product_id = '$product_id' AND purchase_quantity = '$purchase_quantity' AND discount_percentage = '$discount'";
    $discount_result = $conn->query($discount_sql);

    if ($discount_result->num_rows > 0) {
        $row = $discount_result->fetch_assoc();
        $discount_amount = (float)$row["discount_amount"];

        // Return the discount amount as a response
        echo $discount_amount;
    }
}

$conn->close();
?>
