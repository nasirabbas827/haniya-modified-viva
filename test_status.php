<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Your database connection code
include('config.php');

$patientId = $_SESSION['id'];
$patientEmail = $_SESSION['email'];

// Fetch test requests for the logged-in user
$query = "SELECT * FROM test_requests WHERE patient_id = '$patientId'";
$result = mysqli_query($conn, $query);

// Check if the query was executed successfully
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: antiquewhite;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h3 class="text-center mb-4">Requested Test Status</h3>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Request ID</th>
                <th>Patient Name</th>
                <th>Test Name</th>
                <th>Patient Email</th>
                <th>Patient Contact No.</th>
                <th>Date Requested</th>
                <th>Cost</th>
                <th>Reporting Time</th>
                <th>Category Name</th>
                <th>Status</th>
                <th>Print Invoice</th>
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
                    <td><?php echo $row['date_requested']; ?></td>
                    <td><?php echo $row['cost']; ?></td>
                    <td><?php echo $row['reporting_time']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Approved') { ?>
                            <a href="print_invoice.php?request_id=<?php echo $row['request_id']; ?>" class="btn btn-primary">Print Invoice</a>
                            <a href="feedback.php?request_id=<?php echo $row['request_id']; ?>" class="mt-2 btn btn-success">Give Feedback</a>
                            <?php } else { ?>
                            <span class="text-muted">N/A</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
