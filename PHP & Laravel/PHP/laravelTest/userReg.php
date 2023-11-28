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

// Function to insert a new user into the database
function insertUser($name, $nic, $address, $mobile, $email, $gender, $territory_id, $username, $password) {
    global $conn;
    $sql = "INSERT INTO users (name, nic, address, mobile, email, gender, territory_id, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiss", $name, $nic, $address, $mobile, $email, $gender, $territory_id, $username, $password);

    if ($stmt->execute()) {
        echo "User registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    return $stmt->close();
}

// Function to update a user in the database
function updateUser($id, $name, $nic, $address, $mobile, $email, $gender, $territory_id, $username, $password) {
    global $conn;
    $sql = "UPDATE users SET name=?, nic=?, address=?, mobile=?, email=?, gender=?, territory_id=?, username=?, password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssissi", $name, $nic, $address, $mobile, $email, $gender, $territory_id, $username, $password, $id);

    if ($stmt->execute()) {
        echo "User data updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    return $stmt->close();
}

// Function to retrieve all users from the database
function getAllUsers()
{
    global $conn;
    $sql = "SELECT users.id, users.name, users.nic, users.address, users.mobile, users.email, users.gender, territories.territory_name, users.username
            FROM users
            INNER JOIN territories ON users.territory_id = territories.id";
    $result = $conn->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Retrieve all territories for the dropdown
function getAllTerritories() {
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

$users = getAllUsers();
$territories = getAllTerritories();

// Handle user registration and editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $name = $_POST["name"];
        $nic = $_POST["nic"];
        $address = $_POST["address"];
        $mobile = $_POST["mobile"];
        $email = $_POST["email"];
        $gender = $_POST["gender"];
        $territory_id = $_POST["territory_id"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (isset($_POST["edit_id"])) {
            // Editing an existing user
            $edit_id = $_POST["edit_id"];
            updateUser($edit_id, $name, $nic, $address, $mobile, $email, $gender, $territory_id, $username, $password);
        } else {
            // Registering a new user
            insertUser($name, $nic, $address, $mobile, $email, $gender, $territory_id, $username, $password);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration and List</title>
</head>
<body>
    <h1>User Registration</h1>
    <form method="post">
        <?php if (isset($_GET["edit"])): ?>
            <!-- Editing an existing user -->
            <?php
            $edit_id = $_GET["edit"];
            $edit_user = $users[array_search($edit_id, array_column($users, 'id'))];
            ?>
            <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <?php endif; ?>
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php if (isset($edit_user)) echo $edit_user["name"]; ?>" required><br>
        <label for="nic">NIC:</label>
        <input type="text" name="nic" value="<?php if (isset($edit_user)) echo $edit_user["nic"]; ?>" required><br>
        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php if (isset($edit_user)) echo $edit_user["address"]; ?>" required><br>
        <label for="mobile">Mobile:</label>
        <input type="text" name="mobile" value="<?php if (isset($edit_user)) echo $edit_user["mobile"]; ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php if (isset($edit_user)) echo $edit_user["email"]; ?>" required><br>
        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="Male" <?php if (isset($edit_user) && $edit_user["gender"] === "Male") echo "selected"; ?>>Male</option>
            <option value="Female" <?php if (isset($edit_user) && $edit_user["gender"] === "Female") echo "selected"; ?>>Female</option>
            <option value="Other" <?php if (isset($edit_user) && $edit_user["gender"] === "Other") echo "selected"; ?>>Other</option>
        </select><br>
        <label for="territory_id">Territory:</label>
        <select name="territory_id" required>
            <?php foreach ($territories as $territory): ?>
                <option value="<?php echo $territory["id"]; ?>" <?php if (isset($edit_user["territory_id"]) && $edit_user["territory_id"] === $territory["id"]) echo "selected"; ?>><?php echo $territory["territory_name"]; ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php if (isset($edit_user)) echo $edit_user["username"]; ?>" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" <?php if (!isset($edit_user)) echo 'required'; ?>><br>

        <input type="submit" name="submit" value="<?php if (isset($edit_user)) echo "Update"; else echo "Register"; ?>" id="submitbtn">
    </form>

    <h2>User List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>NIC</th>
            <th>Address</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Territory</th>
            <th>User Name</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user["id"]; ?></td>
                <td><?php echo $user["name"]; ?></td>
                <td><?php echo $user["nic"]; ?></td>
                <td><?php echo $user["address"] ?></td>
                <td><?php echo $user["mobile"] ?></td>
                <td><?php echo $user["email"] ?></td>
                <td><?php echo $user["gender"] ?></td>
                <td><?php echo $user["territory_name"] ?></td>
                <td><?php echo $user["username"] ?></td>
                <td>
                    <a href="?edit=<?php echo $user["id"]; ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
