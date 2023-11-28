<?php
include ("header.html");
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

// Function to insert a new territory into the database
function insertTerritory($zone_id, $region_id, $territory_code, $territory_name) {
    global $conn;
    $sql = "INSERT INTO territories (zone_id, region_id, territory_code, territory_name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $zone_id, $region_id, $territory_code, $territory_name);
    return $stmt->execute();
}

// Function to update an existing territory in the database
function updateTerritory($id, $zone_id, $region_id, $territory_code, $territory_name) {
    global $conn;
    $sql = "UPDATE territories SET zone_id = ?, region_id = ?, territory_code = ?, territory_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $zone_id, $region_id, $territory_code, $territory_name, $id);
    return $stmt->execute();
}

// Function to retrieve all territories from the database
function getAllTerritories() {
    global $conn;
    $sql = "SELECT territories.id, zones.zone_code, regions.region_name, territories.territory_code, territories.territory_name 
            FROM territories
            INNER JOIN zones ON territories.zone_id = zones.id
            INNER JOIN regions ON territories.region_id = regions.id";
    $result = $conn->query($sql);
    if(!$result){
        die("Query failed: " .mysqli_error($conn));
    }
    $territories = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $territories[] = $row;
        }
    }
    return $territories;
}

// Retrieve all zones for the dropdown
function getAllZones() {
    global $conn;
    $sql = "SELECT id, zone_code FROM zones";
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
function getAllRegions() {
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

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $zone_id = $_POST["zone_id"];
        $region_id = $_POST["region_id"];
        $territory_code = $_POST["territory_code"];
        $territory_name = $_POST["territory_name"];

        if (isset($_POST["territory_id"])) {
            // Update an existing territory
            $territory_id = $_POST["territory_id"];
            updateTerritory($territory_id, $zone_id, $region_id, $territory_code, $territory_name);
        } else {
            // Insert a new territory
            insertTerritory($zone_id, $region_id, $territory_code, $territory_name);
        }
    }
}

// Retrieve all territories
$territories = getAllTerritories();

// Retrieve all zones and regions for the dropdowns
$zones = getAllZones();
$regions = getAllRegions();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Territory Registration</title>
</head>
<body>
<h1>Territory Registration</h1>
<form method="post">
    <?php if (isset($_GET["edit"])): ?>
        <input type="hidden" name="territory_id" value="<?php echo $_GET["edit"]; ?>">
    <?php endif; ?>

    <label for="zone_id">Select Zone:</label>
    <select name="zone_id" id="zone_id">
        <option value="">Select Zone</option>
            <?php foreach ($zones as $zone): ?>
                <option value="<?php echo $zone["id"]; ?>"><?php echo $zone["zone_code"]; ?></option>
            <?php endforeach; ?>
    </select><br>

    <label for="region_id">Select Region:</label>
    <select name="region_id" id="region_id">
        <option value="">Select Region</option>
        </select><br>


<script>
document.getElementById("zone_id").addEventListener("change", function () {
    var zoneId = this.value;
    var regionSelect = document.getElementById("region_id");

    // Clear existing options
    regionSelect.innerHTML = '<option value="">Select Region</option>';

    // If a zone is selected, make an AJAX request to get relevant regions
    if (zoneId !== "") {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var regions = JSON.parse(xhr.responseText);
                regions.forEach(function (region) {
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
</script>



    <!-- Generate the Territory Code automatically -->
    <label for="territory_code">Territory Code:</label>
    <input type="text" name="territory_code" readonly value="<?php echo generateTerritoryCode(); ?>"><br>
    <label for="territory_name">Territory Name:</label>
    <input type="text" name="territory_name" required><br>
    <input type="submit" name="submit" value="Save" id="submitbtn">
</form>


    <h2>Territory List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Zone</th>
            <th>Region</th>
            <th>Territory Code</th>
            <th>Territory Name</th>
            <th>Action</th>
        </tr>
        <?php foreach ($territories as $territory): ?>
            <tr>
                <td><?php echo $territory["id"]; ?></td>
                <td><?php echo $territory["zone_code"]; ?></td>
                <td><?php echo $territory["region_name"]; ?></td>
                <td><?php echo $territory["territory_code"]; ?></td>
                <td><?php echo $territory["territory_name"]; ?></td>
                <td>
                    <a href="?edit=<?php echo $territory["id"]; ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    // Handle territory editing
    if (isset($_GET["edit"])) {
        $edit_id = $_GET["edit"];
        $edit_territory = $territories[array_search($edit_id, array_column($territories, 'id'))];

        // Populate the form with the selected territory's data
        echo "<script>
                document.querySelector('input[name=\"territory_id\"]').value = '{$edit_territory['id']}';
                document.querySelector('input[name=\"territory_code\"]').value = '{$edit_territory['territory_code']}';
                document.querySelector('input[name=\"territory_name\"]').value = '{$edit_territory['territory_name']}';
              </script>";
    }

    $conn->close();

    // Function to generate a unique territory code
    function generateTerritoryCode() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
}
    ?>
</body>
</html>
