<?php
session_start();
include('config.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Check if test_id, center_id, and category_id are provided in the URL
if (!isset($_GET['test_id']) || !isset($_GET['center_id']) || !isset($_GET['category_id'])) {
    header("Location: index.php");
    exit();
}

$testId = $_GET['test_id'];
$centerId = $_GET['center_id'];
$categoryID = $_GET['category_id'];

// Fetch test details
$testQuery = "SELECT * FROM test_details WHERE test_id = $testId";
$testResult = mysqli_query($conn, $testQuery);
$test = mysqli_fetch_assoc($testResult);

// Fetch center details
$centerQuery = "SELECT * FROM diagnostic_centers WHERE center_id = $centerId";
$centerResult = mysqli_query($conn, $centerQuery);
$center = mysqli_fetch_assoc($centerResult);

// Fetch category details
$categoryQuery = "SELECT category_name FROM test_categories WHERE category_id = $categoryID AND center_id = $centerId";
$categoryResult = mysqli_query($conn, $categoryQuery);
$category = mysqli_fetch_assoc($categoryResult);

// Fetch patient details
$patientId = $_SESSION['id'];
$patientQuery = "SELECT * FROM patients WHERE id = $patientId";
$patientResult = mysqli_query($conn, $patientQuery);
$patient = mysqli_fetch_assoc($patientResult);

// Add test request
if (isset($_POST['submit_request'])) {
    $dateRequested = date('Y-m-d'); // Get the current date

    // Insert test request with patient details
    $query = "INSERT INTO test_requests (patient_id, patient_name, test_id, patient_email, patient_contactno, status, date_requested, test_name, cost, reporting_time, category_id, category_name, center_id) VALUES ('$patientId', '{$patient['Username']}', $testId, '{$patient['Email']}', '{$patient['contactno']}', 'Pending', '$dateRequested', '{$test['test_name']}', {$test['cost']}, '{$test['reporting_time']}', $categoryID, '{$category['category_name']}', $centerId)";
    mysqli_query($conn, $query);

    // Display success message
    $successMessage = "Test request submitted successfully!";
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Test</title>
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
    <h3>Request Test: <?php echo $test['test_name']; ?></h3>
    <h4>Category: <?php echo $category['category_name']; ?></h4>
    <h4>Offered by <?php echo $center['center_name']; ?></h4>

    <?php if (isset($successMessage)) { ?>
        <p class="mt-3 alert alert-success"><?php echo $successMessage; ?></p>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="test_name">Test Name</label>
            <input type="text" name="test_name" value="<?php echo $test['test_name']; ?>" readonly class="form-control">
        </div>

        <div class="form-group">
            <label for="test_cost">Cost</label>
            <input type="text" name="test_cost" value="<?php echo $test['cost']; ?>" readonly class="form-control">
        </div>

        <div class="form-group">
            <label for="reporting_time">Reporting Time</label>
            <input type="text" name="reporting_time" value="<?php echo $test['reporting_time']; ?>" readonly class="form-control">
        </div>

        <button type="submit" name="submit_request" class="btn btn-primary">Request Test</button>
    </form>
</div>
</body>
</html>
