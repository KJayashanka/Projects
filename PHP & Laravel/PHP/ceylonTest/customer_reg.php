<?php
include("header.html");
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";

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

// Function to generate a unique customer code
function generateCustomerCode($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $customer_code = '';
    for ($i = 0; $i < $length; $i++) {
        $customer_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $customer_code;
}

// Handle form submission for customer registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["customer_name"]) && isset($_POST["customer_address"]) && isset($_POST["customer_contact"])) {
        $customer_name = $_POST["customer_name"];
        $customer_address = $_POST["customer_address"];
        $customer_contact = $_POST["customer_contact"];

        $customer_code = generateCustomerCode();

        $sql = "INSERT INTO customers (customer_code, customer_name, customer_address, customer_contact) VALUES ('$customer_code', '$customer_name', '$customer_address', '$customer_contact')";

        if ($conn->query($sql) === true) {
            echo "Customer registered successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // echo "Please fill in all the required fields.";
    }
}

// Handle form submission for editing a customer
if (isset($_POST["edit_customer_id"])) {
    $customer_id = $_POST["edit_customer_id"];
    $edited_customer_name = $_POST["edited_customer_name"];
    $edited_customer_address = $_POST["edited_customer_address"];
    $edited_customer_contact = $_POST["edited_customer_contact"];

    $sql = "UPDATE customers SET customer_name = '$edited_customer_name', customer_address = '$edited_customer_address', customer_contact = '$edited_customer_contact' WHERE id = $customer_id";

    if ($conn->query($sql) === true) {
        echo "Customer updated successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Display customer registration form
echo '
<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
</head>
<body>
    <h1>Customer Registration</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br>
        <label for="customer_address">Customer Address:</label>
        <input type="text" id="customer_address" name="customer_address" required><br>
        <label for="customer_contact">Customer Contact (10 digits):</label>
        <input type="text" id="customer_contact" name="customer_contact" required maxlength="10">
        <button type="submit">Register Customer</button>
    </form>
';

// Display registered customers in a table
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<h2>Registered Customers</h2>';
    echo '<table border="1">';
    echo '<tr><th>Customer Code</th><th>Customer Name</th><th>Customer Address</th><th>Customer Contact</th><th>Edit</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $customer_id = $row["id"];
        echo '<tr>';
        echo '<td>' . $row["customer_code"] . '</td>';
        echo '<td>' . $row["customer_name"] . '</td>';
        echo '<td>' . $row["customer_address"] . '</td>';
        echo '<td>' . $row["customer_contact"] . '</td>';
        echo '<td><a href="#edit" onclick="editCustomer(' . $customer_id . ', \'' . $row["customer_name"] . '\', \'' . $row["customer_address"] . '\', \'' . $row["customer_contact"] . '\')">Edit</a></td>';
        echo '</tr>';
    }
    echo '</table>';
}

echo '
    <h2 id="edit_heading">Edit Customer</h2>
    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" id="edit_form" style="display: none;">
        <input type="hidden" id="edit_customer_id" name="edit_customer_id">
        <label for="edited_customer_name">Customer Name:</label>
        <input type="text" id="edited_customer_name" name="edited_customer_name" required>
        <label for="edited_customer_address">Customer Address:</label>
        <input type="text" id="edited_customer_address" name="edited_customer_address" required>
        <label for="edited_customer_contact">Customer Contact:</label>
        <input type="text" id="edited_customer_contact" name="edited_customer_contact" required>
        <button type="submit">Update Customer</button>
    </form>
';

echo '
    <script>
        function editCustomer(id, name, address, contact) {
            document.getElementById("edit_heading").style.display = "block";
            document.getElementById("edit_form").style.display = "block";
            document.getElementById("edit_customer_id").value = id;
            document.getElementById("edited_customer_name").value = name;
            document.getElementById("edited_customer_address").value = address;
            document.getElementById("edited_customer_contact").value = contact;
        }
    </script>
</body>
</html>
';

$conn->close();

