<?php
session_start(); // Start session
include('config.php');
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
    // Handle the case where the manager is not associated with any diagnostic center
    // You can redirect or display an error message as needed
    $errorMessage = 'You are not currently assigned to manage any diagnostic center.';
}

// Delete test detail
if (isset($_GET['delete_test'])) {
    $testId = $_GET['delete_test'];

    // Check if the test belongs to the manager's center
    $testQuery = "SELECT test_id FROM test_details WHERE test_id = $testId AND category_id IN (SELECT category_id FROM test_categories WHERE center_id = $centerId)";
    $testResult = mysqli_query($conn, $testQuery);

    if ($testResult && mysqli_num_rows($testResult) > 0) {
        // Display confirmation pop-up
        echo '<script>
            var result = confirm("Are you sure you want to delete this test?");
            if (result) {
                window.location.href = "view_test_details.php?confirm_delete=' . $testId . '";
            }
            else {
                // User clicked cancel, do nothing
            }
          </script>';
        exit();
    } else {
        // Handle the case where the test does not belong to the manager's center
        // You can redirect or display an error message as needed
        $errorMessage = 'You do not have permission to delete this test.';
    }
}

// Handle confirmed deletion
if (isset($_GET['confirm_delete'])) {
    $testId = $_GET['confirm_delete'];
    $query = "DELETE FROM test_details WHERE test_id = $testId";
    mysqli_query($conn, $query);
    header("Location: view_test_details.php");
    exit();
}

// Display test details associated with the manager's center
$query = "SELECT * FROM test_details WHERE category_id IN (SELECT category_id FROM test_categories WHERE center_id = $centerId)";
$result = mysqli_query($conn, $query);

// Fetch diagnostic test categories
$categoryQuery = "SELECT * FROM test_categories";
$categories = mysqli_query($conn, $categoryQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert library -->

    <style>
        body {
            background-color: antiquewhite;
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #fff;
        }

        table th,
        table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-danger:hover,
        .btn-danger:focus {
            color: #fff;
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h3 class="mt-4 mb-4 text-center">Edit/Delete Test Details</h3>
        <?php if (!empty($errorMessage)) { ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php } ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Test ID</th>
                    <th>Test Name</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>Reporting Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row['test_id']; ?></td>
                        <td><?php echo $row['test_name']; ?></td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td><?php echo $row['cost']; ?></td>
                        <td><?php echo $row['reporting_time']; ?></td>
                        <td>
                            <a href="edit_test.php?test_id=<?php echo $row['test_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="?delete_test=<?php echo $row['test_id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
