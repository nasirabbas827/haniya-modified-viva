<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

// Fetch managers from the "managers" table
$managerList = array();
$sql = "SELECT id, name FROM managers";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $managerList[] = $row;
    }
} else {
    // Handle any errors
    echo "Error: " . mysqli_error($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data from the form
    $center_name = $_POST['center_name'];
    $location = $_POST['location'];
    $contact_details = $_POST['contact_details'];
    $manager_id = $_POST['manager_id']; // Assuming you have a dropdown/select for managers

    // Insert the new diagnostic center into the database
    $sql = "INSERT INTO diagnostic_centers (center_name, location, contact_details, manager_id) VALUES ('$center_name', '$location', '$contact_details', '$manager_id')";
    
    if (mysqli_query($conn, $sql)) {
        // Successfully added the center
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
    <title>Add New Diagnostic Center</title>
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
        <h1>Add New Diagnostic Center</h1>
        <form method="post" action="add_center.php">
            <div class="form-group">
                <label for="center_name">Center Name:</label>
                <input type="text" class="form-control" id="center_name" name="center_name" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="contact_details">Contact Details:</label>
                <input type="text" class="form-control" id="contact_details" name="contact_details" required>
            </div>
            <div class="form-group">
                <label for="manager_id">Manager:</label>
                <select class="form-control" id="manager_id" name="manager_id">
                    <?php
                    foreach ($managerList as $manager) {
                        echo "<option value='{$manager['id']}'>{$manager['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Center</button>
            <button class="btn btn-dark"><a href="manage_centers.php">View Center</a></button>
        </form>
    </div>
</body>
</html>
