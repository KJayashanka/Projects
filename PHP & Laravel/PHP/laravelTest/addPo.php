<?php
include("header1.html");
echo "<link rel=stylesheet type=text/css href=mystylesheet1.css>";
// Database connection configuration (replace with your actual credentials)
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

// Generate a unique 5-character PO number
$po_number = generateRandomString(5);

// Function to generate a random string of given length
function generateRandomString($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

// Fetch data from the 'zones' table for the zone dropdown
function getAllZones()
{
    global $conn;
    $sql = "SELECT id, zone_code, short_description FROM zones";
    $result = $conn->query($sql);
    $zones = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $zones[] = $row;
        }
    }
    return $zones;
}

// Retrieve all regions for the dropdown
function getAllRegions()
{
    global $conn;
    $sql = "SELECT * FROM regions";
    $result = $conn->query($sql);
    $regions = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $regions[] = $row;
        }
    }
    return $regions;
}

// Fetch data from the 'territories' table for the territory dropdown
function getAllTerritories()
{
    global $conn;
    $sql = "SELECT * FROM territories";
    $result = $conn->query($sql);
    $territories = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $territories[] = $row;
        }
    }
    return $territories;
}

// Fetch product prices from the 'products' table
function getAllDistributors()
{
    global $conn;
    $sql = "SELECT * FROM users"; // Modify the query to fetch distributor data
    $result = $conn->query($sql);
    $distributors = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $distributors[] = $row;
        }
    }
    return $distributors;
}

// Retrieve all territories
$territories = getAllTerritories();

// Retrieve all zones and regions for the dropdowns
$zones = getAllZones();
$regions = getAllRegions();
$distributors = getAllDistributors();

// Fetch data from the 'products' table to display product information
$product_table = "<table border='1'><tr><th>Add to PO</th><th>SKU Code</th><th>SKU Name</th><th>Distributor Price</th><th>Unit Price (MRP)</th><th>Available Qty</th>
                  <th>Units</th><th>Total Price</th></tr>";
$product_query = "SELECT sku_code, sku_name, distributor_price, mrp, units FROM products";
$product_result = $conn->query($product_query);


if ($product_result->num_rows > 0) {
    while ($row = $product_result->fetch_assoc()) {
        $product_table .= "<tr>";
        $product_table .= "<td><input type='checkbox' name='products[]' value='{$row["sku_code"]}' onclick='populateHiddenFields(this)'></td>";
        $product_table .= "<td>{$row["sku_code"]}</td>";
        $product_table .= "<td>{$row["sku_name"]}</td>";
        $product_table .= "<td>{$row["distributor_price"]}</td>";
        $product_table .= "<td>{$row["mrp"]}</td>";
        $product_table .= "<td>{$row["units"]}</td>";
        // Add input fields for units and calculate total price
        $product_table .= "<td><input type='number' name='unit_amount[]' value='1' min='1' onchange='calculateTotalPrice(this)' data-mrp='{$row["mrp"]}'></td>";
        $product_table .= "<td class='total_price'>{$row["mrp"]}</td>"; // Initialize with MRP

        // Add hidden input fields for product details
        $product_table .= "<input type='hidden' name='sku_code[]' value='{$row["sku_code"]}'>";
        $product_table .= "<input type='hidden' name='sku_name[]' value='{$row["sku_name"]}'>";
        $product_table .= "<input type='hidden' name='distributor_price[]' value='{$row["distributor_price"]}'>";
        $product_table .= "<input type='hidden' name='mrp[]' value='{$row["mrp"]}'>";
        $product_table .= "<input type='hidden' name='units[]' value='{$row["units"]}'>";

        $product_table .= "</tr>";
    }
}
$product_table .= "</table>";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_po"])) {

    // Process the form data, including selected products, and insert into the 'purchase_orders' table
    $zone = isset($_POST["zone_hdn"]) ? $_POST["zone_hdn"] : null;
    $region = isset($_POST["region_hdn"]) ? $_POST["region_hdn"] : null;
    $territory = isset($_POST["territory_hdn"]) ? $_POST["territory_hdn"] : null;
    $distributor = isset($_POST["distributor"]) ? $_POST["distributor"] : null;
    $remark = $_POST["remark"];
    $selected_products = $_POST["products"];
    $unit_amounts = $_POST["unit_amount"];
    $sku_codes = $_POST["sku_code"];
    $sku_names = $_POST["sku_name"];
    $distributor_prices = $_POST["distributor_price"];
    $mrps = $_POST["mrp"];
    $units = $_POST["units"];

    // Insert data into the 'purchase_orders' table
    $stmt = $conn->prepare("INSERT INTO purchase_orders (po_number, distributor, order_date, `zone`, region, territory, remark) VALUES (?, ?, NOW(), ?, ?, ?, ?)");
    $order_date = date('Y-m-d H:i:s'); // Current date and time
    $stmt->bind_param("ssssss", $po_number, $distributor, $zone, $region, $territory, $remark);

    $po_id_query = "SELECT max(id) as id FROM purchase_orders";
    $purchase_order_result = $conn->query($po_id_query);
    $row = $purchase_order_result->fetch_assoc();
    $purchase_order_id =  $row['id'] + 1;

    $flag_order = TRUE;
    $flag_invoice = TRUE;

    // Fetch the last inserted order ID
    $last_order_id = $conn->insert_id;

    // Loop through selected products and save their details to the array
    foreach ($selected_products as $index => $sku) {
        if (isset($unit_amounts[$index])) {
            $sql = "SELECT * FROM products where sku_code = '$sku'";
            $result = $conn->query($sql) or die($conn->error);;
            while ($row = $result->fetch_assoc()) {
                $sku_code = $row['sku_code'];
                $sku_name = $row['sku_name'];
                $distributor_price = $row['distributor_price'];
                $mrp = $row['mrp'];
                $unit_count = $unit_amounts[array_search($sku_code, $sku_codes)];

                $total_price_line = $unit_count * $mrp;
            }
        }
        // Insert product details into the 'purchase_order' table
        $stmtProduct = $conn->prepare("INSERT INTO purchase_orders_lines (po_number, sku_code, sku_name, distributor_price, mrp, units, total_price, id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtProduct->bind_param("ssssssds",  $po_number, $sku_code, $sku_name, $distributor_price, $mrp, $unit_count,$total_price_line, $purchase_order_id);


        if ($stmt->execute() && $stmtProduct->execute() && $flag_order) {
            // Order inserted successfully
            echo "Order created successfully!";
            $flag_order = false;
        }
        $total_price = 0;
        foreach ($selected_products as $sku) {
            if (isset($unit_amounts[$sku])) {
                $total_price += ($unit_amounts[$sku] * $productPrices[$sku]);
            }

            // Insert data into the 'invoices' table
            $invoice_number = generateRandomString(8); // Generate a unique invoice number
            $invoice_date = date('Y-m-d H:i:s'); // Current date and time

            $stmt = $conn->prepare("INSERT INTO invoices (invoice_number, po_number, distributor, invoice_date, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $invoice_number, $po_number, $distributor, $invoice_date);

            if ($stmt->execute() && $flag_invoice) {
                // Invoice inserted successfully
                echo "Invoice created successfully!";
                $flag_invoice = FALSE;
            } else {
                // Error occurred while inserting the invoice
                if ($flag_invoice) {
                    echo "Error creating invoice: " . $stmt->error;
                }
            }

            // Reset the selected products
            $selected_products = [];
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Individual Purchase Order</title>
</head>

<body>
    <h1>Individual Purchase Order</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="zone_id">Select Zone:</label>
        <input type="hidden" id="zone_hdn" name="zone_hdn">
        <select name="zone_" id="zone_id" onChange="zoneFunction(this)">
            <option value="">Select Zone</option>
            <?php foreach ($zones as $zone) : ?>
                <option value="<?php echo $zone["id"]; ?>"><?php echo $zone["short_description"]; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="region_id">Select Region:</label>
        <input type="hidden" id="region_hdn" name="region_hdn">
        <select name="region" id="region_id" onChange="regionFunction(this)">
            <option value="">Select Region</option>
        </select><br>

        <script>
            document.getElementById("zone_id").addEventListener("change", function() {
                var zoneId = this.value;
                var regionSelect = document.getElementById("region_id");

                // Clear existing options
                regionSelect.innerHTML = '<option value="">Select Region</option>';

                // If a zone is selected, make an AJAX request to get relevant regions
                if (zoneId !== "") {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var regions = JSON.parse(xhr.responseText);
                            regions.forEach(function(region) {
                                var option = document.createElement("option");
                                option.value = region.id;
                                option.text = region.region_name; // Use region name here
                                regionSelect.appendChild(option);
                            });
                        }
                    };
                    xhr.open("GET", "get_regions.php?zone_id=" + zoneId, true);
                    xhr.send();
                }
            });

            function zoneFunction(sel) {
                var text = sel.options[sel.selectedIndex].text;
                document.getElementById("zone_hdn").value = text;
            }

            function regionFunction(sel) {
                var text = sel.options[sel.selectedIndex].text;
                document.getElementById("region_hdn").value = text;
            }
        </script>

        <label for="territory_id">Select Territory:</label>
        <input type="hidden" id="territory_hdn" name="territory_hdn">
        <select name="territory" id="territory_id" onChange="territoryFunction(this)">
            <option value="">Select Territory</option>
        </select><br>

        <script>
            document.getElementById("region_id").addEventListener("change", function() {
                var regionId = this.value;
                var territorySelect = document.getElementById("territory_id");


                // Clear existing options
                territorySelect.innerHTML = '<option value="">Select Territory</option>';

                // If a region is selected, make an AJAX request to get relevant territories
                if (regionId !== "") {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var territories = JSON.parse(xhr.responseText);
                            territories.forEach(function(territory) {
                                var option = document.createElement("option");
                                option.value = territory.id;
                                option.text = territory.territory_name; // Use territory name here
                                territorySelect.appendChild(option);
                            });
                        }
                    };
                    xhr.open("GET", "get_territories.php?region_id=" + regionId, true);
                    xhr.send();
                }
            });

            function territoryFunction(sel) {
                var text = sel.options[sel.selectedIndex].text;
                document.getElementById("territory_hdn").value = text;
            }
        </script>

        <label for="distributor_id">Select Distributor:</label>
        <select name="distributor" id="distributor_id">
            <option value="">Select Distributor</option>
        </select><br>

        <script>
            document.getElementById("territory_id").addEventListener("change", function() {
                var territoryId = this.value;
                var distributorSelect = document.getElementById("distributor_id");

                // Clear existing options
                distributorSelect.innerHTML = '<option value="">Select Distributor</option>';

                // If a territory is selected, make an AJAX request to get relevant distributors
                if (territoryId !== "") {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var distributors = JSON.parse(xhr.responseText);
                            distributors.forEach(function(distributor) {
                                var option = document.createElement("option");
                                option.value = distributor.name;
                                option.text = distributor
                                    .username; // Use distributor username or name here
                                distributorSelect.appendChild(option);
                            });
                        }
                    };
                    xhr.open("GET", "get_distributors.php?territory_id=" + territoryId, true);
                    xhr.send();
                }
            });
        </script>
        <label for="date">Date:</label>
        <input type="text" name="date" value="<?php echo date('Y-m-d'); ?>" readonly><br>

        <label for="po_number">PO Number:</label>
        <input type="text" name="po_number" value="<?php echo $po_number; ?>" readonly><br>

        <label for="remark">Remark:</label>
        <input type="text" name="remark" required><br>

        <?php echo $product_table; ?>

        <input type="submit" name="add_po" value="Add PO" id="submitbtn">
    </form>

    <script>
        function populateHiddenFields(checkbox) {
            var row = checkbox.parentNode.parentNode;
            var hiddenInputs = row.getElementsByTagName('input');

            for (var i = 0; i < hiddenInputs.length; i++) {
                if (hiddenInputs[i].type === 'hidden') {
                    var fieldName = hiddenInputs[i].name;
                    var dataIndex = fieldName.lastIndexOf('['); // Get the index of the last '[' character
                    var index = fieldName.substring(dataIndex + 1, fieldName.length -
                        1); // Extract the index from the field name

                    // Use the index to fetch the corresponding data from the row
                    var cellIndex = i - 1; // The cell containing the data
                    var cellValue = row.cells[cellIndex].textContent.trim();

                    // Set the value of the hidden field based on the field name and index
                    hiddenInputs[i].value = cellValue;
                }
            }
        }

        function calculateTotalPrice(input) {
            var row = input.parentNode.parentNode;
            var mrp = parseFloat(row.cells[4].textContent);
            var units = parseFloat(input.value);
            var totalPriceCell = row.cells[7];
            totalPriceCell.textContent = (mrp * units).toFixed(2);
        }

        // Attach onchange event listener to all unit amount inputs
        var unitAmountInputs = document.getElementsByName('unit_amount[]');
        for (var i = 0; i < unitAmountInputs.length; i++) {
            unitAmountInputs[i].addEventListener('change', function() {
                calculateTotalPrice(this);
            });
        }
    </script>
</body>

</html>