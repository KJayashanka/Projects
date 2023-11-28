<?php
include("header.html");
echo "<link rel=stylesheet type=text/css href=mystylesheet1.css>";

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

if (isset($_GET["order_number"])) {
    $order_number = $_GET["order_number"];

    // Fetch order details for the selected order number
    $sql = "SELECT * FROM orders WHERE order_number = '$order_number'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Order Details</h1>";
        echo "<table border='1'>";
        echo "<tr><th>Order Number</th><th>Customer Name</th><th>Product Name</th><th>Product Code</th><th>Price</th><th>Discount (%)</th><th>Purchase Quantity</th><th>Free Quantity</th><th>Amount</th></tr>";
       
        $netAmount = 0; // Initialize net amount

        while ($row = $result->fetch_assoc()) {
            $price = $row["price"];
            $discount = $row["discount"];
            $purchaseQuantity = $row["purchase_quantity"];
            $amount = $price * $purchaseQuantity * ((100 - $discount) / 100); // Calculate the discount amount
            
            $netAmount += $amount; // Accumulate the amount for net amount
            echo "<tr>";
            echo "<td>" . $row["order_number"] . "</td>";
            echo "<td>" . $row["customer_name"] . "</td>";
            echo "<td>" . $row["product_name"] . "</td>";
            echo "<td>" . $row["product_code"] . "</td>";
            echo "<td>" . $price . "</td>";
            echo "<td>" . $discount . "</td>";
            echo "<td>" . $purchaseQuantity . "</td>";
            echo "<td>" . $row["free_quantity"] . "</td>";
            echo "<td>" . number_format($amount, 2) . "</td>";
            echo "</tr>";
        }

        // Add the total row with the net amount
      
        echo "<tr><td colspan='8'>Net Amount:</td><td>" . number_format($netAmount, 2) . "</td></tr>";

        echo "</table>";
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
