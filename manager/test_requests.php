<?php
include('config.php');
session_start();
$successMessage = ''; // Variable to store success message
$errorMessage = ''; // Variable to store error message

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$email = $_SESSION['email']; // Get the user's email from the session

// Retrieve the manager's center ID
$managerId = $_SESSION['managerId'];
$query = "SELECT center_id FROM diagnostic_centers WHERE manager_id = $managerId";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $centerId = $row['center_id'];
} else {
    // Handle the case where the manager is not assigned to any diagnostic center
    // You can redirect or display an error message as needed
    $errorMessage = 'You are not currently assigned to manage any diagnostic center.';
}

// Approve test request
if (isset($_GET['approve_request'])) {
    $requestId = $_GET['approve_request'];
    $query = "UPDATE test_requests SET status = 'Approved' WHERE request_id = $requestId";
    mysqli_query($conn, $query);
    // Redirect or display success message
}

// Update test request
if (isset($_POST['update_request'])) {
    $requestId = $_POST['request_id'];
    $patientName = $_POST['patient_name'];
    $testId = $_POST['test_id'];
    $query = "UPDATE test_requests SET patient_name = '$patientName', test_id = $testId WHERE request_id = $requestId";
    mysqli_query($conn, $query);
    // Redirect or display success message
}

// Cancel test request
if (isset($_GET['cancel_request'])) {
    $requestId = $_GET['cancel_request'];
    $query = "DELETE FROM test_requests WHERE request_id = $requestId";
    mysqli_query($conn, $query);
    // Redirect or display success message
}

// Display test requests for the manager's center only
$query = "SELECT * FROM test_requests WHERE center_id = $centerId";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit/Cancel Test Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert library -->

    <style>
        body {
            background-color: antiquewhite;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h3 class="text-center mt-4 mb-4">Manage Patients Test Requests</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Request ID</th>
                    <th>Patient Name</th>
                    <th>Test Name</th>
                    <th>Patient Email</th>
                    <th>Patient Contact No.</th>
                    <th>Status</th>
                    <th>Date Requested</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['request_id']; ?></td>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['test_name']; ?></td>
                        <td><?php echo $row['patient_email']; ?></td>
                        <td><?php echo $row['patient_contactno']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['date_requested']; ?></td>
                        <td>
                            <?php if ($row['status'] != 'Approved') { ?>
                                <a href="?approve_request=<?php echo $row['request_id']; ?>" class="btn btn-success">Approve</a>
                            <?php } ?>
                            <a href="edit_request.php?request_id=<?php echo $row['request_id']; ?>" class="btn btn-secondary">Edit</a>
                            <a href="?cancel_request=<?php echo $row['request_id']; ?>" class="btn btn-danger">Cancel</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
