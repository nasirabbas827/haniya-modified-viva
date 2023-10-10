<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

// Function to fetch all diagnostic centers with manager names
function getDiagnosticCentersWithManagers($conn) {
    $centers = array();

    $sql = "SELECT dc.center_id, dc.center_name, dc.location, dc.contact_details, m.name AS manager_name
            FROM diagnostic_centers dc
            LEFT JOIN managers m ON dc.manager_id = m.id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $centers[] = $row;
        }
    } else {
        // Handle any errors
        echo "Error: " . mysqli_error($conn);
    }

    return $centers;
}

// Delete center if requested
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $delete_sql = "DELETE FROM diagnostic_centers WHERE center_id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        // Successfully deleted center
        header('location: manage_centers.php'); // Redirect back to the management page
        exit();
    } else {
        // Handle any errors
        echo "Error: " . mysqli_error($conn);
    }
}

$centers = getDiagnosticCentersWithManagers($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Diagnostic Centers</title>
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
        <h1>Manage Diagnostic Centers</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Center Name</th>
                    <th>Location</th>
                    <th>Contact Details</th>
                    <th>Manager</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($centers as $center) { ?>
                    <tr>
                        <td><?php echo $center['center_name']; ?></td>
                        <td><?php echo $center['location']; ?></td>
                        <td><?php echo $center['contact_details']; ?></td>
                        <td><?php echo $center['manager_name']; ?></td>
                        <td>
                            <a href="edit_center.php?center_id=<?php echo $center['center_id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="manage_centers.php?delete_id=<?php echo $center['center_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this center?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
