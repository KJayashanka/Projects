<?php
include("header1.html");
echo "<link rel=stylesheet type=text/css href=mystylesheet2.css>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Purchase Orders</title>
</head>
<body>
    <h1>View Purchase Orders</h1>

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

    // Fetch unique values for Region from the database
$region_options = "";
$region_query = "SELECT DISTINCT region FROM purchase_orders";
$region_result = $conn->query($region_query);

if ($region_result === false) {
    die("Error executing the region query: " . $conn->error);
}

if ($region_result->num_rows > 0) {
    while ($row = $region_result->fetch_assoc()) {
        $region = $row["region"];
        $region_options .= "<option value=\"$region\">$region</option>";
    }
}

// Fetch unique values for Territory from the database
$territory_options = "";
$territory_query = "SELECT DISTINCT territory FROM purchase_orders";
$territory_result = $conn->query($territory_query);

if ($territory_result === false) {
    die("Error executing the territory query: " . $conn->error);
}

if ($territory_result->num_rows > 0) {
    while ($row = $territory_result->fetch_assoc()) {
        $territory = $row["territory"];
        $territory_options .= "<option value=\"$territory\">$territory</option>";
    }
}

// Fetch unique values for PO Number from the database
$po_number_options = "";
$po_number_query = "SELECT DISTINCT po_number FROM purchase_orders";
$po_number_result = $conn->query($po_number_query);

if ($po_number_result === false) {
    die("Error executing the PO number query: " . $conn->error);
}

if ($po_number_result->num_rows > 0) {
    while ($row = $po_number_result->fetch_assoc()) {
        $po_number = $row["po_number"];
        $po_number_options .= "<option value=\"$po_number\">$po_number</option>";
    }
}


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form input values
        $selected_region = $_POST["region"];
        $selected_territory = $_POST["territory"];
        $selected_po_numbers = isset($_POST["selected_invoices"]) ? $_POST["selected_invoices"] : [];
        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];

        // Construct the SQL query based on selected filters
        $sql = "SELECT region, territory, distributor, order_date, po_number, remark FROM purchase_orders WHERE 1";

        if (!empty($selected_region)) {
            $sql .= " AND region = '$selected_region'";
        }

        if (!empty($selected_territory)) {
            $sql .= " AND territory = '$selected_territory'";
        }

        if (!empty($selected_po_numbers)) {
            $sql .= " AND po_number IN ('" . implode("','", $selected_po_numbers) . "')";
        }

        if (!empty($from_date)) {
            $sql .= " AND order_date >= '$from_date'";
        }

        if (!empty($to_date)) {
            $sql .= " AND order_date <= '$to_date'";
        }

        // Execute the SQL query
        $result = mysqli_query($conn, $sql) or die ("error");

        if ($result->num_rows > 0) {
            echo "<h2>Filtered Purchase Orders:</h2>";
            echo "<form method='post' action='view_selected_invoices.php'>"; // Create a form for generating selected invoices
            echo "<table border='1'>";
            echo "<tr><th>Select</th><th>Region</th><th>Territory</th><th>Distributor</th><th>PO Number</th><th>Order Date</th><th>View Invoice</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><input type='checkbox' name='selected_invoices[]' value='{$row["po_number"]}'></td>"; // Add checkbox
                echo "<td>{$row["region"]}</td>";
                echo "<td>{$row["territory"]}</td>";
                echo "<td>{$row["distributor"]}</td>";
                echo "<td>{$row["po_number"]}</td>";
                echo "<td>{$row["order_date"]}</td>";
                echo "<td><a href='generate_invoice.php?po_number={$row["po_number"]}' target='_blank'>View</a></td>"; // Add link to view invoice
                echo "</tr>";
            }
            echo "</table>";
        
            echo "<input type='submit' name='view_selected' value='View Selected Invoices' id = 'submitbtn'>"; // Add button to view selected invoices
            echo "</form>";
        } else {
            echo "No matching purchase orders found.";
        }
    }
    $conn->close();
    ?>

    <h2>Filter Purchase Orders</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <label for="region">Region:</label>
        <select name="region">
            <option value="" selected>Select Region</option>
            <?php echo $region_options; ?>
        </select><br>

        <label for="territory">Territory:</label>
        <select name="territory">
            <option value="" selected>Select Territory</option>
            <?php echo $territory_options; ?>
        </select><br>

        <label for="po_number">PO Number:</label>
        <select name="po_number">
            <option value="" selected>Select PO Number</option>
            <?php echo $po_number_options; ?>
        </select><br>
        
        <label for="from_date">From Date:</label>
        <input type="date" name="from_date"><br>

        <label for="to_date">To Date:</label>
        <input type="date" name="to_date"><br>

        <input type="submit" value="Filter" id="submitbtn">
    </form>
</body>
</html>
