<?php
include("header.html");
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laravel-test";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert a new product into the database
function insertProduct($sku_code, $sku_name, $distributor_price, $mrp, $units, $measure)
{
    global $conn;
    $sql = "INSERT INTO products (sku_code, sku_name, distributor, mrp, units, measure) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $sku_code, $sku_name, $distributor_price, $mrp, $units, $measure);
    return $stmt->execute();
}

// Function to update a product in the database
function updateProduct($id, $sku_code, $sku_name, $distributor_price, $mrp, $units, $measure)
{
    global $conn;
    $sql = "UPDATE products SET sku_code=?, sku_name=?, distributor_price=?, mrp=?, units=?, measure=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $sku_code, $sku_name, $distributor_price, $mrp, $units, $measure, $id);
    return $stmt->execute();
}

// Function to retrieve all products from the database
function getAllProducts()
{
    global $conn;
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    $products = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $sku_code = $_POST["sku_code"];
        $sku_name = $_POST["sku_name"];
        $distributor = $_POST["distributor"];
        $mrp = $_POST["mrp"];
        $units = $_POST["units"];
        $measure = $_POST["measure"];

        if (isset($_POST["edit_id"])) {
            // Editing an existing product
            $edit_id = $_POST["edit_id"];
            updateProduct($edit_id, $sku_code, $sku_name, $distributor, $mrp, $units, $measure);
        } else {
            // Insert a new product
            insertProduct($sku_code, $sku_name, $distributor, $mrp, $units, $measure);
        }
    }
}

// Retrieve all products
$products = getAllProducts();

// Handle product editing
if (isset($_GET["edit"])) {
    $edit_id = $_GET["edit"];
    $edit_product = $products[array_search($edit_id, array_column($products, 'id'))];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Product Registration</title>
</head>

<body>
    <h1>Product Registration</h1>
    <form method="post">
        <?php if (isset($edit_product)): ?>
            <!-- Editing an existing product -->
            <input type="hidden" name="edit_id" value="<?php echo $edit_product['id']; ?>">
        <?php endif; ?>
        <label for="sku_code">SKU Code:</label>
        <input type="text" name="sku_code" value="<?php if (isset($edit_product)) echo $edit_product["sku_code"]; ?>" required><br>
        <label for="sku_name">SKU Name:</label>
        <input type="text" name="sku_name" value="<?php if (isset($edit_product)) echo $edit_product["sku_name"]; ?>" required><br>
        <label for="distributor">Distributor Price:</label>
        <input type="text" name="distributor" value="<?php if (isset($edit_product)) echo $edit_product["distributor_price"]; ?>" required><br>
        <label for="mrp">MRP:</label>
        <input type="text" name="mrp" value="<?php if (isset($edit_product)) echo $edit_product["mrp"]; ?>" required><br>
        <label for="units">Units:</label>
        <input type="number" name="units" value="<?php if (isset($edit_product)) echo $edit_product["units"]; ?>" required><br>
        <label for="measure">Measure:</label>
        <select name="measure" required>
            <option value="ml" <?php if (isset($edit_product) && $edit_product["measure"] === "ml") echo "selected"; ?>>ml</option>
            <option value="g" <?php if (isset($edit_product) && $edit_product["measure"] === "g") echo "selected"; ?>>g</option>
            <option value="kg" <?php if (isset($edit_product) && $edit_product["measure"] === "kg") echo "selected"; ?>>kg</option>
        </select><br>
        <input type="submit" name="submit" value="<?php if (isset($edit_product)) echo "Update"; else echo "Register"; ?>" id="submitbtn">
    </form>

    <h2>Product List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>SKU Code</th>
            <th>SKU Name</th>
            <th>Distributor Price</th>
            <th>MRP</th>
            <th>Units</th>
            <th>Measure</th>
            <th>Action</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product["id"]; ?></td>
                <td><?php echo $product["sku_code"]; ?></td>
                <td><?php echo $product["sku_name"]; ?></td>
                <td><?php echo $product["distributor_price"] ?></td>
                <td><?php echo $product["mrp"] ?></td>
                <td><?php echo $product["units"] ?></td>
                <td><?php echo $product["measure"]?></td>
                <td>
                    <a href="?edit=<?php echo $product['id']; ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php $conn->close(); ?>
</body>

</html>
