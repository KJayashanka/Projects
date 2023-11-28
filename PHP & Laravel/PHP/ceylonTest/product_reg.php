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

// Function to generate a unique product code
function generateProductCode($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $product_code = '';
    for ($i = 0; $i < $length; $i++) {
        $product_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $product_code;
}

// Handle form submission for product registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are set in $_POST
    if (isset($_POST["product_name"]) && isset($_POST["price"]) && isset($_POST["expiry_date"])) {
        $product_name = $conn->real_escape_string($_POST["product_name"]);
        $price = (float)$_POST["price"];
        $expiry_date = $_POST["expiry_date"];

        if (empty($product_name)) {
            echo "Product name cannot be empty.";
        } else {
            $product_code = generateProductCode();

            $sql = "INSERT INTO products (product_code, product_name, price, expiry_date) 
                    VALUES ('$product_code', '$product_name', $price, '$expiry_date')";

            if ($conn->query($sql) === true) {
                echo "Product registered successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        // echo "Missing required fields.";
    }
}

// Handle form submission for editing a product
if (isset($_POST["edit_product_id"])) {
    $product_id = $_POST["edit_product_id"];
    $edited_product_name = $_POST["edited_product_name"];
    $edited_price = $_POST["edited_price"];
    $edited_expiry_date = $_POST["edited_expiry_date"];

    $sql = "UPDATE products SET product_name = '$edited_product_name', price = $edited_price, expiry_date = '$edited_expiry_date' WHERE id = $product_id";

    if ($conn->query($sql) === true) {
        echo "Product updated successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Display product registration form
echo '
<!DOCTYPE html>
<html>
<head>
    <title>Product Registration</title>
</head>
<body>
    <h1>Product Registration</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required>
        <label for="expiry_date">Expiry Date:</label>
        <input type="date" id="expiry_date" name="expiry_date" required><br>
        <button type="submit">Register Product</button>
    </form>
';

// Display registered products in a table
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<h2>Registered Products</h2>';
    echo '<table border="1">';
    echo '<tr><th>Product Code</th><th>Product Name</th><th>Price</th><th>Expiry Date</th><th>Edit</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $product_id = $row["id"];
        echo '<tr>';
        echo '<td>' . $row["product_code"] . '</td>';
        echo '<td>' . $row["product_name"] . '</td>';
        echo '<td>$' . $row["price"] . '</td>';
        echo '<td>' . $row["expiry_date"] . '</td>';
        echo '<td><a href="#edit" onclick="editProduct(' . $product_id . ', \'' . $row["product_name"] . '\', ' . $row["price"] . ', \'' . $row["expiry_date"] . '\')">Edit</a></td>';
        echo '</tr>';
    }
    echo '</table>';
}

echo '
    <h2 id="edit_heading">Edit Product</h2>
    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" id="edit_form" style="display: none;">
        <input type="hidden" id="edit_product_id" name="edit_product_id">
        <label for="edited_product_name">Product Name:</label>
        <input type="text" id="edited_product_name" name="edited_product_name" required>
        <label for="edited_price">Price:</label>
        <input type="number" id="edited_price" name="edited_price" required>
        <label for="edited_expiry_date">Expiry Date:</label>
        <input type="date" id="edited_expiry_date" name="edited_expiry_date" required><br>
        <button type="submit">Update Product</button>
    </form>
';

echo '
    <script>
        function editProduct(id, name, price, expiryDate) {
            document.getElementById("edit_heading").style.display = "block";
            document.getElementById("edit_form").style.display = "block";
            document.getElementById("edit_product_id").value = id;
            document.getElementById("edited_product_name").value = name;
            document.getElementById("edited_price").value = price;
            document.getElementById("edited_expiry_date").value = expiryDate;
        }
    </script>
</body>
</html>
';

$conn->close();
?>
