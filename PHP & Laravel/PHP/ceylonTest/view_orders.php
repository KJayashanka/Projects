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

// Fetch and display order details with applied discounts
$sql = "SELECT o.order_number, o.customer_name, o.order_date, o.order_time, SUM((o.price * o.purchase_quantity * (100 - p.discount) / 100)) as net_amount
        FROM orders o
        LEFT JOIN products p ON o.product_code = p.product_code
        GROUP BY o.order_number";
$result = $conn->query($sql);

if ($result === false) {
    echo "Error in SQL query: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        echo "<h1>View Orders</h1>";
        echo "<table border='1'>";
        echo "<tr><th>Order Number</th><th>Customer Name</th><th>Order Date</th><th>Order Time</th><th>Net Amount</th><th>Details</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["order_number"] . "</td>";
            echo "<td>" . $row["customer_name"] . "</td>";
            echo "<td>" . $row["order_date"] . "</td>";
            echo "<td>" . $row["order_time"] . "</td>";
            echo "<td>" . number_format($row["net_amount"], 2) . "</td>";
          
            echo '<td><a href="view_order_details.php?order_number=' . $row["order_number"] . '">Details</a></td>';
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No orders found.";
    }
}

$conn->close();
?>
