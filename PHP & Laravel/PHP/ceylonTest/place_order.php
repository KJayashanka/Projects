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

// Handle form submission for placing orders
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["place_order"])) {
    if (
        isset($_POST["customer_name"]) &&
        isset($_POST["order_number"]) &&
        isset($_POST["id"]) &&
        isset($_POST["purchase_quantity"]) &&
        isset($_POST["discount"]) // Add this line to check if discount is set
    ) {
        $customer_name = $conn->real_escape_string($_POST["customer_name"]);
        $order_number = $conn->real_escape_string($_POST["order_number"]);
        $product_ids = $_POST["id"];
        $purchase_quantities = $_POST["purchase_quantity"];
        $product_codes = $_POST['product_code'];
        $discounts = $_POST['discount']; // Retrieve the discounts

        $net_amount = 0; // Initialize the net amount

        // Get the current date and time
        $order_date = date("Y-m-d H:i:s");

        // Insert order details into the database
        foreach ($product_ids as $key => $product_id) {
            $purchase_quantity = $purchase_quantities[array_search($product_id, $product_codes)];
            $discount = $discounts[array_search($product_id, $product_codes)]; // Retrieve the discount for this product

            // Fetch product details, including name, code, and price
            $product_sql = "SELECT product_name, product_code, price FROM products WHERE id = '$product_id'";
            $product_result = $conn->query($product_sql);

            if ($product_result->num_rows > 0) {
                $row = $product_result->fetch_assoc();
                $product_name = $row["product_name"];
                $product_code = $row["product_code"];
                $price = $row["price"];

                // Calculate the free quantity based on the free issues table
                $free_quantity = calculateFreeQuantity($conn, $product_id, $purchase_quantity);

                // Calculate the total amount for the order, including the discount
                $total_amount = $price * $purchase_quantity * (1 - ($discount / 100)); // Apply the discount

                // Insert the order details into the orders table, including date and time
                $insert_sql = "INSERT INTO orders (customer_name, order_number, product_name, product_code, price, purchase_quantity, free_quantity, amount, order_date, order_time, discount) VALUES ('$customer_name', '$order_number', '$product_name', '$product_code', '$price', '$purchase_quantity', '$free_quantity', '$total_amount', '$order_date', '$order_date', '$discount')";

                if ($conn->query($insert_sql) !== true) {
                    echo "Error: " . $insert_sql . "<br>" . $conn->error;
                }

                $net_amount += $total_amount; // Update the net amount
            }
        }

        $update_net_amount_sql = "UPDATE orders SET net_amount = '$net_amount' WHERE order_number = '$order_number'";
        if ($conn->query($update_net_amount_sql) !== true) {
            echo "Error updating net amount: " . $update_net_amount_sql . "<br>" . $conn->error;
        }

        // You can store the net amount in the database or display it as per your requirements.
    }
}
// Function to calculate free quantity based on the free issues table
function calculateFreeQuantity($conn, $product_id, $purchase_quantity)
{
    // Fetch free issues data for the given product
    $free_issues_sql = "SELECT free_quantity, purchase_quantity FROM free_issues WHERE purchase_product_id = '$product_id'";
    $free_issues_result = $conn->query($free_issues_sql);

    if ($free_issues_result !== false && $free_issues_result->num_rows > 0) {
        $row = $free_issues_result->fetch_assoc();
        $purchase_quantity_needed = (int)$row["purchase_quantity"];
        $free_quantity = 0;

        if ($purchase_quantity >= $purchase_quantity_needed && $purchase_quantity_needed > 0) {
            // Calculate the free quantity based on the purchase quantity and the purchase quantity needed for free items
            $free_quantity = floor($purchase_quantity / $purchase_quantity_needed);
        }

        return $free_quantity;
    }

    return 0; // Default to 0 free quantity if no matching record is found
}

// Function to generate a unique order number
function generateOrderNumber($conn)
{
    $order_number = "OD" . rand(1000, 9999);

    // Check if the generated order number already exists
    $check_sql = "SELECT order_number FROM orders WHERE order_number = '$order_number'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        return generateOrderNumber($conn); // Recursively generate a new order number if it already exists
    }

    return $order_number;
}

// Fetch customer names from the database for the customer name dropdown
$customer_options = "";
$sql = "SELECT customer_name FROM customers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customer_name = $row["customer_name"];
        $customer_options .= "<option value='$customer_name'>$customer_name</option>";
    }
}

// Fetch product details from the database for the product table
$product_table = "";
$product_sql = "SELECT p.id, p.product_name, p.product_code, p.price, p.discount, f.lower_limit, f.upper_limit FROM products p LEFT JOIN free_issues f ON p.id = f.purchase_product_id";
$product_result = $conn->query($product_sql);

if (isset($_POST["view_orders"])) {
    // Redirect to a new page for viewing orders
    header("Location: view_orders.php");
    exit;
}

if ($product_result->num_rows > 0) {
    while ($row = $product_result->fetch_assoc()) {
        $product_id = $row["id"];
        $product_name = $row["product_name"];
        $product_code = $row["product_code"];
        $price = $row["price"];
        $discount = $row['discount'];
        $lower_limit = $row['lower_limit'] == null ? 0 : $row['lower_limit'];
        $upper_limit = $row['upper_limit'] == null ? 0 : $row['upper_limit'];

        // Calculate the total amount based on user input
        $total_amount = 0; // Initialize to 0

        // Add product details to the table with input fields for purchase quantity and an unchangeable discount
        $product_table .= "<tr>
            <td><input type='checkbox' name='id[]' value='$product_id'></td>
            <td>$product_name</td>
            <td>$product_code</td>
            <td>$price</td>
            <td><input type='number' name='purchase_quantity[]' value='0' min='0' oninput='calculateAmount(this, $lower_limit, $upper_limit, $product_id)'></td>
            <td><input type='number' name='discount[]' value='$discount' min='0' max='100' readonly></td>
            <td><span class='free-quantity'>0</span></td>
            <td><span class='amount'>0.00</span></td>
            <input type='hidden' name='product_code[]' value='$product_id'>
        </tr>";
    }
}

echo '
<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <script>
        var selectedProducts = {};

        function calculateAmount(input, lower_limit, upper_limit, product_id) {
            var row = input.parentElement.parentElement;
            var priceCell = row.cells[3];
            var purchaseQuantityCell = row.cells[4];
            var discountCell = row.cells[5];
            var freeQuantityCell = row.cells[6];
            var amountCell = row.cells[7];

            var price = parseFloat(priceCell.textContent);
            var purchaseQuantity = parseFloat(input.value);
            selectedProducts[product_id] = purchaseQuantity;
            var discount = parseFloat(discountCell.querySelector("input").value);

            if (lower_limit > 0 && purchaseQuantity >= lower_limit && upper_limit > 0 && purchaseQuantity <= upper_limit) {
                var freeQuantity = purchaseQuantity;
            } else {
                var freeQuantity = 0;
            }

            // Calculate the free quantity based on your rule (e.g., 1 free for every 15 purchased)
            var purchaseQuantityNeededForFree = 12; // You can adjust this as needed
            var freeQuantity = Math.floor(purchaseQuantity / lower_limit);

            // Update the free quantity in the cell
            freeQuantityCell.querySelector(".free-quantity").textContent = freeQuantity;

            // Calculate the total amount with discount
            var totalAmount = price * purchaseQuantity * (1 - (discount / 100));

            // Update the free quantity, amount, and net amount
            freeQuantityCell.querySelector(".free-quantity").textContent = freeQuantity;
            amountCell.querySelector(".amount").textContent = totalAmount.toFixed(2);

            // Calculate and update the net amount when the purchase quantity changes
            calculateNetAmount();
        }

        function calculateNetAmount() {
            var purchaseQuantities = document.querySelectorAll(\'input[name="purchase_quantity[]"]\');
            var discountInputs = document.querySelectorAll(\'input[name="discount[]"]\');
            var amountCells = document.querySelectorAll(\'.amount\');
            var netAmount = 0;

            purchaseQuantities.forEach(function(purchaseQuantity, index) {
                var quantity = parseFloat(purchaseQuantity.value);
                var discount = parseFloat(discountInputs[index].value);
                var price = parseFloat(purchaseQuantity.closest("tr").cells[3].textContent);
                var totalAmount = price * quantity * (1 - (discount / 100));
                netAmount += totalAmount;
            });

            var netAmountCell = document.getElementById("net_amount");
            netAmountCell.textContent = netAmount.toFixed(2);
        }
    </script>
</head>
<body>
    <h1>Place Order</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <label for="customer_name">Customer Name:</label>
        <select name="customer_name">
            <option value="">Customer Name</option>
            ' . $customer_options . '
        </select><br>
        <label for="order_number">Order Number:</label>
        <input type="text" name="order_number" value="' . generateOrderNumber($conn) . '" readonly>
        <h2>Products</h2>
        <table border="1">
            <tr>
                <th>Select</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Price</th>
                <th>Purchase Quantity</th>
                <th>Discount (%)</th>
                <th>Free Quantity</th>
                <th>Amount</th>
            </tr>
            ' . $product_table . '
            <tr>
                <td colspan="7" align="right">Net Amount:</td>
                <td><span id="net_amount">0.00</span></td>
            </tr>
        </table>
        <button type="submit" name="place_order">Place Order</button>
        <button type="submit" name="view_orders">View Orders</button>
    </form>
</body>
</html>
';

$conn->close();
?>
