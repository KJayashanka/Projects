<?php
// Database connection configuration 
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

// Construct the SQL query to retrieve all order details
$sql = "SELECT * FROM purchase_orders";

// Execute the SQL query
$result = mysqli_query($conn, $sql) or die("Error");

// Display all order details
if ($result->num_rows > 0) {
    echo "<h2>All Purchase Orders:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Region</th><th>Territory</th><th>Distributor</th><th>PO Number</th><th>Order Date</th><th>View Invoice</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row["region"]}</td>";
        echo "<td>{$row["territory"]}</td>";
        echo "<td>{$row["distributor"]}</td>";
        echo "<td>{$row["po_number"]}</td>";
        echo "<td>{$row["order_date"]}</td>";
        echo "<td><a href='generate_invoice.php?po_number={$row["po_number"]}' target='_blank'>View</a></td>"; // Add link to view invoice
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No purchase orders found.";
}

$conn->close();
?>
