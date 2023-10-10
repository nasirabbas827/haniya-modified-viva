<?php
session_start();
include('config.php');

// Check if the manager is logged in and has a valid session
if (!isset($_SESSION['email']) || !isset($_SESSION['managerId'])) {
    header('location: manager_login.php');
    exit();
}

$managerId = $_SESSION['managerId'];
$email = $_SESSION['email'];

// Query to fetch the diagnostic center information managed by this manager
$sql = "SELECT * FROM diagnostic_centers WHERE manager_id = $managerId";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $center = mysqli_fetch_assoc($result);
    // You can access center details like $center['center_name'], $center['location'], etc.
} else {
    // Handle the case where no center is associated with this manager
    $center = null;
}

// Additional code to fetch and display other dashboard information goes here
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
    <!-- Add your CSS and Bootstrap includes here -->
</head>
<body>
<?php include('navbar.php'); ?>
<?php $center; ?>

    <!-- Add your HTML structure for the manager's dashboard here -->
    <h1>Welcome, <?php echo $email; ?>!</h1>

    <?php if ($center) { ?>
        <h2>Your Managed Diagnostic Center:</h2>
        <p>Center ID: <?php echo $center['center_id']; ?></p>
        <p>Center Name: <?php echo $center['center_name']; ?></p>
        <p>Location: <?php echo $center['location']; ?></p>
        <!-- Add more center details here if needed -->
    <?php } else { ?>
        <p>You are not currently assigned to manage any diagnostic center.</p>
    <?php } ?>

    <!-- Add other dashboard content here -->

    <a href="logout.php">Logout</a> <!-- Link to logout page -->
</body>
</html>
