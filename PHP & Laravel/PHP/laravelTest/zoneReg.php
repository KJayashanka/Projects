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

// Function to insert or update a zone in the database
function saveZone($zone_id, $long_description, $short_description)
{
    global $conn;
    
    if ($zone_id) {
        // Update an existing zone
        $sql = "UPDATE zones SET long_description = ?, short_description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $long_description, $short_description, $zone_id);
    } else {
        // Insert a new zone
        $sql = "INSERT INTO zones (long_description, short_description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $long_description, $short_description);
    }

    if ($stmt->execute()) {
        // If inserting a new zone, generate the Zone Code
        if (!$zone_id) {
            $zone_id = $stmt->insert_id;
            $zone_code = "Z" . str_pad($zone_id, 4, '0', STR_PAD_LEFT);

            // Update the Zone Code in the database
            $update_sql = "UPDATE zones SET zone_code = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $zone_code, $zone_id);
            $update_stmt->execute();
        }

        echo "Zone saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    return $stmt->close();
}

// Function to retrieve all zones from the database
function getAllZones()
{
    global $conn;
    $sql = "SELECT * FROM zones";
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
        $long_description = $_POST["long_description"];
        $short_description = $_POST["short_description"];

        // Insert or update the zone
        saveZone($zone_id, $long_description, $short_description);
    }
}

// Retrieve all zones
$zones = getAllZones();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zone Registration</title>
</head>
<body>
<h1>Zone Registration</h1>
<form method="post">
    <?php if (isset($_GET["edit"])): ?>
        <input type="hidden" name="zone_id" value="<?php echo $_GET["edit"]; ?>">
    <?php endif; ?>
    <!-- Display the Zone Code based on the inserted or updated zone -->
    <label for="zone_code">Zone Code:</label>
    <input type="text" name="zone_code" readonly
        value="<?php
            if (isset($_GET["edit"])) {
                $editZone = $zones[array_search($_GET["edit"], array_column($zones, 'id'))];
                echo $editZone["zone_code"];
            } else {
                // For new zones, the code will be generated when saved
                echo "Automatically";
            }
        ?>"><br>
    <label for="long_description">Long Description:</label>
    <input type="text" name="long_description" required
        value="<?php echo isset($_GET["edit"]) ? $editZone["long_description"] : ''; ?>"><br>
    <label for="short_description">Short Description:</label>
    <input type="text" name="short_description" required
        value="<?php echo isset($_GET["edit"]) ? $editZone["short_description"] : ''; ?>"><br>
    <input type="submit" name="submit" value="Save" id="submitbtn">
</form>

<h2>Zone List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Zone Code</th>
        <th>Long Description</th>
        <th>Short Description</th>
        <th>Action</th>
    </tr>
    <?php foreach ($zones as $zone): ?>
        <tr>
            <td><?php echo $zone["id"]; ?></td>
            <td><?php echo $zone["zone_code"]; ?></td>
            <td><?php echo $zone["long_description"]; ?></td>
            <td><?php echo $zone["short_description"]; ?></td>
            <td>
                <a href="?edit=<?php echo $zone["id"]; ?>">Edit</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
// Handle zone editing
if (isset($_GET["edit"])) {
    // No need for JavaScript to populate the form with selected zone's data
}

$conn->close();
?>
</body>
</html>
