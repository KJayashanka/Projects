<?php
session_start(); // Start the session

include("header.html");
echo "<link rel='stylesheet' type='text/css' href='mystylesheet1.css'>";

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

// Initialize variables
$free_issue_label = $issue_type = $purchase_product = $free_product = $purchase_quantity = $free_quantity = $lower_limit = $upper_limit = "";

// Handle form submission for free issue registration or update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["free_issue_label"]) &&
        isset($_POST["issue_type"]) &&
        isset($_POST["purchase_product"]) &&
        isset($_POST["free_product"]) &&
        isset($_POST["purchase_quantity"]) &&
        isset($_POST["free_quantity"]) &&
        isset($_POST["lower_limit"]) &&
        isset($_POST["upper_limit"])
    ) {
        $free_issue_label = $_POST["free_issue_label"];
        $issue_type = $_POST["issue_type"];
        $purchase_product = $_POST["purchase_product"];
        $free_product = $_POST["free_product"];
        $purchase_quantity = $_POST["purchase_quantity"];
        $free_quantity = $_POST["free_quantity"];
        $lower_limit = $_POST["lower_limit"];
        $upper_limit = $_POST["upper_limit"];

        // Fetch product_id based on the selected purchase_product
        $product_id = null;
        $productQuery = "SELECT id FROM products WHERE product_name = '$purchase_product'";
        $productResult = $conn->query($productQuery);
        if ($productResult && $productResult->num_rows > 0) {
            $productRow = $productResult->fetch_assoc();
            $product_id = $productRow["id"];
        }

        if (isset($_POST["edit_id"])) {
            $edit_id = $_POST["edit_id"];
            $edit_sql = "UPDATE free_issues SET free_issue_label = '$free_issue_label', issue_type = '$issue_type', purchase_product = '$purchase_product', free_product = '$free_product', purchase_quantity = $purchase_quantity, free_quantity = $free_quantity, lower_limit = $lower_limit, upper_limit = $upper_limit, purchase_product_id = $product_id WHERE id = $edit_id";

            if ($conn->query($edit_sql) === true) {
                echo "Free issue updated successfully.";
            } else {
                echo "Error: " . $edit_sql . "<br>" . $conn->error;
            }
        } else {
            $sql = "INSERT INTO free_issues (free_issue_label, issue_type, purchase_product, free_product, purchase_quantity, free_quantity, lower_limit, upper_limit, purchase_product_id) VALUES ('$free_issue_label', '$issue_type', '$purchase_product', '$free_product', $purchase_quantity, $free_quantity, $lower_limit, $upper_limit, $product_id)";

            if ($conn->query($sql) === true) {
                // Save purchase quantity and free quantity in sessions
                $_SESSION['purchase_quantity'] = $purchase_quantity;
                $_SESSION['free_quantity'] = $free_quantity;
                echo "Free issue registered successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Please fill in all the required fields.";
    }
}

$product_options = "";
$sql = "SELECT product_name FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_name = $row["product_name"];
        $product_options .= "<option value='$product_name'>$product_name</option>";
    }
}

echo '
<script>
    function setFreeProduct() {
        var purchaseProduct = document.getElementById("purchase_product");
        var freeProduct = document.getElementById("free_product");
        freeProduct.value = purchaseProduct.value;
    }

    function setFreeProductInEditForm() {
        var purchaseProductEdit = document.getElementById("purchase_product_edit");
        var freeProductEdit = document.getElementById("free_product_edit");
        freeProductEdit.value = purchaseProductEdit.value;
    }

    function updateFreeQuantity() {
        var purchaseQuantityEdit = document.getElementById("purchase_quantity_edit").value;
        var freeQuantityEdit = document.getElementById("free_quantity_edit");
        freeQuantityEdit.value = purchaseQuantityEdit;
    }

    // Function to populate the correct purchase product in the edit form
    function populateEditForm(productName) {
        var purchaseProductEdit = document.getElementById("purchase_product_edit");
        purchaseProductEdit.value = productName;
        setFreeProductInEditForm();
    }
</script>
';

echo '
<!DOCTYPE html>
<html>
<head>
    <title>Free Issue Registration</title>
</head>
<body>
    <h1>Free Issue Registration</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <label for="free_issue_label">Free Issue Label:</label>
        <input type="text" id="free_issue_label" name="free_issue_label" required><br>
        <label for="issue_type">Issue Type:</label>
        <select name="issue_type">
            <option value="Flat">Flat</option>
            <option value="Multiple">Multiple</option>
        </select><br>
        <label for="purchase_product">Purchase Product:</label>
        <select id="purchase_product" name="purchase_product" onchange="setFreeProduct();">
            ' . $product_options . '
        </select><br>
        <label for="free_product">Free Product:</label>
        <input type="text" id="free_product" name="free_product" required><br>
        <label for="purchase_quantity">Purchase Quantity:</label>
        <input type="number" id="purchase_quantity" name="purchase_quantity" required oninput="updateLowerLimit();"><br>
        <label for="free_quantity">Free Quantity:</label>
        <input type="number" id="free_quantity" name="free_quantity" required><br>
        <label for="lower_limit">Lower Limit:</label>
        <input type="number" id="lower_limit" name="lower_limit" required><br>
        <label for="upper_limit">Upper Limit:</label>
        <input type="number" id="upper_limit" name="upper_limit" required><br>
        <button type="submit">Register Free Issue</button>
    </form>
    ';

// JavaScript to set the lower limit
echo '
<script>
function setFreeProduct() {
    var purchaseProduct = document.getElementById("purchase_product");
    var freeProduct = document.getElementById("free_product");
    freeProduct.value = purchaseProduct.value;
}
function updateLowerLimit() {
    var purchaseQuantity = document.getElementById("purchase_quantity").value;
    var lowerLimit = document.getElementById("lower_limit");
    lowerLimit.value = purchaseQuantity;
}
</script>
';

$sql = "SELECT * FROM free_issues";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<h2>Registered Free Issues</h2>';
    echo '<table border="1">';
    echo '<tr><th>Free Issue Label</th><th>Issue Type</th><th>Purchase Product</th><th>Free Product</th><th>Purchase Quantity</th><th>Free Quantity</th><th>Lower Limit</th><th>Upper Limit</th><th>Edit</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $free_issue_id = $row["id"];
        echo '<tr>';
        echo '<td>' . $row["free_issue_label"] . '</td>';
        echo '<td>' . $row["issue_type"] . '</td>';
        echo '<td>' . $row["purchase_product"] . '</td>';
        echo '<td>' . $row["free_product"] . '</td>';
        echo '<td>' . $row["purchase_quantity"] . '</td>';
        echo '<td>' . $row["free_quantity"] . '</td>';
        echo '<td>' . $row["lower_limit"] . '</td>';
        echo '<td>' . $row["upper_limit"] . '</td>';
        echo '<td><a href="?edit=' . $free_issue_id . '">Edit</a></td>';
        echo '</tr>';
    }
    echo '</table>';
}

if (isset($_GET["edit"])) {
    $edit_id = $_GET["edit"];
    $edit_sql = "SELECT * FROM free_issues WHERE id = $edit_id";
    $edit_result = $conn->query($edit_sql);
    if ($edit_result->num_rows > 0) {
        $edit_row = $edit_result->fetch_assoc();

        // Fetch product names from the database
        $product_options_edit = "";
        $sql = "SELECT product_name FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_name = $row["product_name"];
                $selected = ($product_name === $edit_row["purchase_product"]) ? 'selected' : '';
                $product_options_edit .= "<option value='$product_name' $selected>$product_name</option>";
            }
        }

        echo '<h2>Edit Free Issue</h2>';
        echo '
        <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input type="hidden" name="edit_id" value="' . $edit_id . '">
            <label for="free_issue_label">Free Issue Label:</label>
            <input type="text" id="free_issue_label" name="free_issue_label" value="' . $edit_row["free_issue_label"] . '" required><br>
            <label for="issue_type">Issue Type:</label>
            <select name="issue_type">
                <option value="Flat" ' . ($edit_row["issue_type"] === "Flat" ? "selected" : "") . '>Flat</option>
                <option value="Multiple" ' . ($edit_row["issue_type"] === "Multiple" ? "selected" : "") . '>Multiple</option>
            </select><br>
            <label for="purchase_product">Purchase Product:</label>
            <select id="purchase_product_edit" name="purchase_product" onchange="setFreeProductInEditForm();">
                ' . $product_options_edit . '
            </select><br>
            <label for="free_product">Free Product:</label>
            <input type="text" id="free_product_edit" name="free_product" value="' . $edit_row["free_product"] . '" required><br>
            <label for="purchase_quantity">Purchase Quantity:</label>
            <input type="number" id="purchase_quantity_edit" name="purchase_quantity" value="' . $edit_row["purchase_quantity"] . '" required><br>
            <label for="free_quantity">Free Quantity:</label>
            <input type="number" id="free_quantity_edit" name="free_quantity" value="' . $edit_row["free_quantity"] . '" required><br>
            <label for="lower_limit">Lower Limit:</label>
            <input type="number" id="lower_limit" name="lower_limit" value="' . $edit_row["lower_limit"] . '" required><br>
            <label for="upper_limit">Upper Limit:</label>
            <input type="number" id="upper_limit" name="upper_limit" value="' . $edit_row["upper_limit"] . '" required><br>
            <button type="submit">Update Free Issue</button>
        </form>';
    }
}

// JavaScript to update Free Quantity in the edit form
echo '
<script>
    function updateFreeQuantity() {
        var purchaseQuantityEdit = document.getElementById("purchase_quantity_edit").value;
        var freeQuantityEdit = document.getElementById("free_quantity_edit");
        freeQuantityEdit.value = purchaseQuantityEdit;
    }
';

$conn->close();
?>
