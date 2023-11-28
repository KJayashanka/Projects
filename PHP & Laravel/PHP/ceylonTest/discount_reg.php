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

// Function to fetch a product ID by product name
function getProductIDByName($conn, $product_name) {
    $sql = "SELECT id FROM products WHERE product_name = '$product_name'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row["id"];
    }

    return null;
}

// Function to fetch a discount by its ID
function getDiscountById($conn, $id) {
    $sql = "SELECT * FROM discounts WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        return $result->fetch_assoc();
    }

    return null;
}

// Handle form submission for discount registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register_discount"])) {
    if (
        isset($_POST["discount_label"]) &&
        isset($_POST["purchase_product"]) &&
        isset($_POST["discount_amount"])
    ) {
        $discount_label = $_POST["discount_label"];
        $purchase_product = $_POST["purchase_product"];
        $discount_amount = $_POST["discount_amount"];

        // Get the product ID for the selected product name
        $product_id = getProductIDByName($conn, $purchase_product);

        if ($product_id !== null) {
            // Insert discount details into the "discounts" table, including product_id
            $sql = "INSERT INTO discounts (discount_label, purchase_product, discount_amount, p_id) VALUES ('$discount_label', '$purchase_product', '$discount_amount', '$product_id')";

            if ($conn->query($sql) === true) {
                // Update the "products" table with the discount amount
                $update_product_sql = "UPDATE products SET discount = '$discount_amount' WHERE id = '$product_id'";
                $conn->query($update_product_sql);

                echo "Discount registered successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Product not found or product name is not valid.";
        }
    } else {
        echo "Please fill in all the required fields.";
    }
}

// Fetch discount data from the database
$discounts = [];
$sql = "SELECT * FROM discounts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $discounts[] = $row;
    }
}

// Fetch product names from the database for the purchase product dropdown
$product_options = "";
$product_sql = "SELECT product_name FROM products";
$product_result = $conn->query($product_sql);

if ($product_result->num_rows > 0) {
    while ($row = $product_result->fetch_assoc()) {
        $product_name = $row["product_name"];
        $product_options .= "<option value='$product_name'>$product_name</option>";
    }
}

// Fetch product_id from the products table for discounts
$product_sql = "SELECT p.id AS product_id FROM products p";
$product_result = $conn->query($product_sql);

if ($product_result->num_rows > 0) {
    while ($row = $product_result->fetch_assoc()) {
        $product_id = $row["product_id"];

    }
}

// Display the discount registration form and the table to display discounts
echo '
<!DOCTYPE html>
<html>
<head>
    <title>Discount Registration and Display</title>
</head>
<body>
    <h1>Discount Registration</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <label for="discount_label">Discount Label:</label>
        <input type="text" id="discount_label" name="discount_label" required><br>
        <label for="purchase_product">Purchase Product:</label>
        <select name="purchase_product">
            ' . $product_options . '
        </select><br>
        <input type="hidden" name="product_id" id="product_id">
        <label for="discount_amount">Discount Amount (%):</label>
        <input type="number" id="discount_amount" name="discount_amount" required><br>
        <button type="submit" name="register_discount">Register Discount</button>
    </form>

    <h1>Display Discounts</h1>
    <table border="1">
        <tr>
            <th>Discount Label</th>
            <th>Purchase Product</th>
            <th>Discount Amount (%)</th>
            <th>Edit</th>
        </tr>';

foreach ($discounts as $discount) {
    echo '<tr>';
    echo '<td>' . $discount["discount_label"] . '</td>';
    echo '<td>' . $discount["purchase_product"] . '</td>';
    echo '<td>' . $discount["discount_amount"] . '</td>';
    echo '<td><a href="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?edit_id=' . $discount["id"] . '">Edit</a></td>';
    echo '</tr>';
}

echo '
    </table>
';

// Handle form submission for editing a discount
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_discount"])) {
    if (
        isset($_POST["edit_id"]) &&
        isset($_POST["discount_label"]) &&
        isset($_POST["purchase_product"]) &&
        isset($_POST["discount_amount"])
    ) {
        $edit_id = $_POST["edit_id"];
        $discount_label = $_POST["discount_label"];
        $purchase_product = $_POST["purchase_product"];
        $discount_amount = $_POST["discount_amount"];

        // Update the discount details in the "discounts" table
        $edit_sql = "UPDATE discounts SET discount_label = '$discount_label', purchase_product = '$purchase_product', discount_amount = '$discount_amount' WHERE id = $edit_id";

        if ($conn->query($edit_sql) === true) {
            // Update the "products" table with the discount amount
            $product_id = getProductIDByName($conn, $purchase_product);
            if ($product_id !== null) {
                $update_product_sql = "UPDATE products SET discount = '$discount_amount' WHERE id = '$product_id'";
                $conn->query($update_product_sql);
            }

            echo "Discount updated successfully.";
        } else {
            echo "Error: " . $edit_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Please fill in all the required fields.";
    }
}

// If an edit link is clicked, show the edit form
if (isset($_GET["edit_id"])) {
    $edit_id = $_GET["edit_id"];
    $edit_discount = getDiscountById($conn, $edit_id);

    if ($edit_discount) {
        echo '
        <h1>Edit Discount</h1>
        <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input type="hidden" name="edit_id" value="' . $edit_id . '">
            <label for="discount_label">Discount Label:</label>
            <input type="text" id="discount_label" name="discount_label" value="' . $edit_discount["discount_label"] . '" required><br>
            <label for="purchase_product">Purchase Product:</label>
            <select name="purchase_product">
            ';

            // Fetch product names and populate the dropdown
            $product_sql = "SELECT product_name FROM products";
            $product_result = $conn->query($product_sql);

            if ($product_result->num_rows > 0) {
                while ($row = $product_result->fetch_assoc()) {
                    $product_name = $row["product_name"];
                    $selected = ($product_name == $edit_discount["purchase_product"]) ? "selected" : "";
                    echo "<option value='$product_name' $selected>$product_name</option>";
                }
            }

            echo '
            </select><br>
            <label for="discount_amount">Discount Amount (%):</label>
            <input type="number" id="discount_amount" name="discount_amount" value="' . $edit_discount["discount_amount"] . '" required><br>
            <button type="submit" name="edit_discount">Save Changes</button>
        </form>
        ';
    }
}

$conn->close();
?>
