<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

if (isset($_GET['center_id'])) {
    $center_id = $_GET['center_id'];

    // Fetch center details from the database
    $sql = "SELECT dc.*, m.id AS manager_id, m.name AS manager_name FROM diagnostic_centers dc
            LEFT JOIN managers m ON dc.manager_id = m.id
            WHERE center_id = $center_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $center = mysqli_fetch_assoc($result);
    } else {
        // Handle any errors
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch managers from the "managers" table
$managerList = array();
$managerSql = "SELECT id, name FROM managers";
$managerResult = mysqli_query($conn, $managerSql);

if ($managerResult) {
    while ($row = mysqli_fetch_assoc($managerResult)) {
        $managerList[] = $row;
    }
} else {
    // Handle any errors
    echo "Error: " . mysqli_error($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect updated data from the form
    $center_name = $_POST['center_name'];
    $location = $_POST['location'];
    $contact_details = $_POST['contact_details'];
    $manager_id = $_POST['manager_id'];

    // Update the diagnostic center in the database
    $updateSql = "UPDATE diagnostic_centers SET center_name = '$center_name', location = '$location', contact_details = '$contact_details', manager_id = '$manager_id' WHERE center_id = $center_id";

    if (mysqli_query($conn, $updateSql)) {
        // Successfully updated the center
        header('location: manage_centers.php'); // Redirect back to the management page
        exit();
    } else {
        // Handle any errors
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Diagnostic Center</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: antiquewhite;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Edit Diagnostic Center</h1>
        <form method="post">
            <div class="form-group">
                <label for="center_name">Center Name:</label>
                <input type="text" class="form-control" id="center_name" name="center_name" value="<?php echo $center['center_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $center['location']; ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_details">Contact Details:</label>
                <input type="text" class="form-control" id="contact_details" name="contact_details" value="<?php echo $center['contact_details']; ?>" required>
            </div>
            <div class="form-group">
                <label for="manager_id">Manager:</label>
                <select class="form-control" id="manager_id" name="manager_id">
                    <?php
                    foreach ($managerList as $manager) {
                        $selected = ($manager['id'] == $center['manager_id']) ? "selected" : "";
                        echo "<option value='{$manager['id']}' $selected>{$manager['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Center</button>
        </form>
    </div>
</body>
</html>
