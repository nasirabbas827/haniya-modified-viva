<?php
session_start();
include('config.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$patientId = $_SESSION['id'];
$query = "SELECT Username, Email, contactno FROM patients WHERE id = $patientId";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$patientName = $row['Username'];
$patientEmail = $row['Email'];
$patientContactNo = $row['contactno'];

// Search for centers based on location
if (isset($_POST['search_center'])) {
    $location = $_POST['location'];

    // Fetch centers in the selected location
    $centersQuery = "SELECT * FROM diagnostic_centers WHERE location = '$location'";
    $centersResult = mysqli_query($conn, $centersQuery);
}

// Add test request
if (isset($_POST['submit_request'])) {
    $testId = $_POST['test_id'];
    $categoryId = $_POST['category_id'];
    $categoryName = $_POST['category_name'];

    // Fetch the test details including test name, cost, and reporting time
    $testQuery = "SELECT test_name, cost, reporting_time FROM test_details WHERE test_id = $testId";
    $testResult = mysqli_query($conn, $testQuery);
    $testRow = mysqli_fetch_assoc($testResult);
    $testName = $testRow['test_name'];
    $cost = $testRow['cost'];
    $reportingTime = $testRow['reporting_time'];

    $dateRequested = date('Y-m-d'); // Get the current date
    $query = "INSERT INTO test_requests (patient_name, test_id, patient_email, patient_contactno, status, date_requested, test_name, cost, reporting_time, category_id, category_name) VALUES ('$patientName', $testId, '$patientEmail', '$patientContactNo', 'pending', '$dateRequested', '$testName', $cost, '$reportingTime', $categoryId, '$categoryName')";
    mysqli_query($conn, $query);

    // Display success message
    $successMessage = "Test request submitted successfully!";
}

$query = "SELECT tr.test_name, tr.status, tf.feedback, tf.rating, m.Username AS patient_name
          FROM test_requests tr
          LEFT JOIN test_feedback tf ON tr.request_id = tf.test_id
          LEFT JOIN patients m ON tf.patient_id = m.id
          WHERE tr.status = 'approved'";
$result = mysqli_query($conn, $query);

// Check if the query was executed successfully
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Diagnostic Center - Home Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: antiquewhite;
        }
    </style>
</head>
<body>
<?php
include('navbar.php');
?>
<div class="container mt-4">
    <!-- Search for centers based on location -->
    <h3>Search for Centers by Location</h3>
    <form method="POST" action="">
        <div class="form-group">
            <input type="text" name="location" class="form-control" placeholder="Enter Location">
        </div>
        <button type="submit" name="search_center" class="btn btn-primary">Search Centers</button>
    </form>

    <?php
    if (isset($centersResult) && mysqli_num_rows($centersResult) > 0) {
        ?>
        <h3>Select a Center</h3>
        <ul class="list-group">
            <?php while ($center = mysqli_fetch_assoc($centersResult)) { ?>
                <li class="list-group-item">
                    <a href="view_center_categories.php?center_id=<?php echo $center['center_id']; ?>"><?php echo $center['center_name']; ?></a>
                </li>
            <?php } ?>
        </ul>
    <?php } elseif (isset($_POST['search_center'])) {
        echo "<p>No centers found in the selected location.</p>";
    } ?>
</div>

<div class="container mt-4">
    <h3 class="text-center mb-4">User Feedback</h3>
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Patient: <?php echo $row['patient_name']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Test Name: <?php echo $row['test_name']; ?></h6>
                            <p class="card-text">Feedback: <?php echo $row['feedback']; ?></p>
                            <p class="card-text">
                                Rating: <?php echo str_repeat('<i class="fas fa-star text-warning"></i>', $row['rating']); ?>
                                <?php echo str_repeat('<i class="far fa-star text-warning"></i>', 5 - $row['rating']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>No feedback available.</p>
    <?php } ?>
</div>

<!-- Add the following script to include FontAwesome icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>
</html>
