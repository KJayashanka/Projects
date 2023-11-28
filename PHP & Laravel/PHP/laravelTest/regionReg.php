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

// Function to insert a new region into the database
function insertRegion($zone_id, $region_code, $region_name) {
    global $conn;
    $sql = "INSERT INTO regions (zone_id, region_code, region_name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $zone_id, $region_code, $region_name);
    return $stmt->execute();
}

// Function to update an existing region in the database
function updateRegion($id, $zone_id, $region_code, $region_name) {
    global $conn;
    $sql = "UPDATE regions SET zone_id = ?, region_code = ?, region_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $zone_id, $region_code, $region_name, $id);
    return $stmt->execute();
}

// Function to retrieve all regions from the database
function getAllRegions() {
    global $conn;
    $sql = "SELECT regions.id, zones.zone_code, regions.region_code, regions.region_name 
            FROM regions
            INNER JOIN zones ON regions.zone_id = zones.id";
    $result = $conn->query($sql);
    $regions = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $regions[] = $row;
        }
    }
    return $regions;
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

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $zone_id = $_POST["zone_id"];
        $region_code = $_POST["region_code"];
        $region_name = $_POST["region_name"];

        if (isset($_POST["region_id"])) {
            // Update an existing region
            $region_id = $_POST["region_id"];
            updateRegion($region_id, $zone_id, $region_code, $region_name);
        } else {
            // Insert a new region
            insertRegion($zone_id, $region_code, $region_name);
        }
    }
}

// Retrieve all regions
$regions = getAllRegions();

// Retrieve all zones for the dropdown
$zones = getAllZones();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Region Registration</title>
</head>
<body>
<h1>Region Registration</h1>
<form method="post">
    <?php if (isset($_GET["edit"])): ?>
        <input type="hidden" name="region_id" value="<?php echo $_GET["edit"]; ?>">
    <?php endif; ?>
    <label for="zone_id">Select Zone:</label>
        <select name="zone_id">
            <option value="">Select Zone</option>
            <?php foreach ($zones as $zone): ?>
                <option value="<?php echo $zone["id"]; ?>"><?php echo $zone["zone_code"]; ?></option>
            <?php endforeach; ?>
        </select><br>
    <!-- Display "automatically" inside the Region Code input field -->
    <label for="region_code">Region Code:</label>
    <input type="text" name="region_code" readonly value="<?php echo generateRegionCode(); ?>"><br>
    <label for="region_name">Region Name:</label>
    <input type="text" name="region_name" required><br>
    <input type="submit" name="submit" value="Save" id="submitbtn">
</form>

    <h2>Region List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Zone</th>
            <th>Region Code</th>
            <th>Region Name</th>
            <th>Action</th>
        </tr>
        <?php foreach ($regions as $region): ?>
            <tr>
                <td><?php echo $region["id"]; ?></td>
                <td><?php echo $region["zone_code"]; ?></td>
                <td><?php echo $region["region_code"]; ?></td>
                <td><?php echo $region["region_name"]; ?></td>
                <td>
                    <a href="?edit=<?php echo $region["id"]; ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    // Handle region editing
    if (isset($_GET["edit"])) {
        $edit_id = $_GET["edit"];
        $edit_region = $regions[array_search($edit_id, array_column($regions, 'id'))];

        // Populate the form with the selected region's data
        echo "<script>
        
                document.querySelector('input[name=\"region_id\"]').value = '{$edit_region['id']}';
                document.querySelector('input[name=\"region_code\"]').value = '{$edit_region['region_code']}';
                document.querySelector('input[name=\"region_name\"]').value = '{$edit_region['region_name']}';
              </script>";
    }

    function generateRegionCode() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }
    $conn->close();
    ?>
</body>
</html>
