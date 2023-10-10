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

// Fetch diagnostic test categories associated with the manager's center
$categoryQuery = "SELECT * FROM test_categories WHERE center_id = $centerId";
$categories = mysqli_query($conn, $categoryQuery);

// Add test detail
$successMessage = ''; // Initialize success message variable
if (isset($_POST['add_test'])) {
    $testName = $_POST['test_name'];
    $testCategory = $_POST['test_category'];
    $cost = $_POST['cost'];
    $reportingTime = $_POST['reporting_time'];

    // Retrieve category name
    $categoryQuery = "SELECT category_name FROM test_categories WHERE category_id = $testCategory";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $categoryName = $categoryRow['category_name'];

    $query = "INSERT INTO test_details (test_name, category_id, category_name, cost, reporting_time) VALUES ('$testName', $testCategory, '$categoryName', '$cost', '$reportingTime')";
    if (mysqli_query($conn, $query)) {
        $successMessage = 'Test added successfully.'; // Set success message
    } else {
        $errorMessage = 'Error: ' . mysqli_error($conn); // Set error message
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <div class="container mt-5">
        <h2 class="text-center mt-4 mb-4">Test Details</h2>

        <!-- Add test detail form -->
        <h3 class="text-center mt-4 mb-4">Add Test Detail</h3>

        <?php if (!empty($successMessage)) { ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php } ?>

        <?php if (!empty($errorMessage)) { ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php } ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="test_name">Test Name:</label>
                <input type="text" class="form-control" id="test_name" name="test_name" placeholder="Test Name">
            </div>
            <div class="form-group">
                <label for="test_category">Test Category:</label>
                <select class="form-control" id="test_category" name="test_category">
                    <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cost">Cost:</label>
                <input type="text" class="form-control" id="cost" name="cost" placeholder="Cost">
            </div>
            <div class="form-group">
                <label for="reporting_time">Reporting Time:</label>
                <input type="text" class="form-control" id="reporting_time" name="reporting_time" placeholder="Reporting Time">
            </div>
            <button type="submit" name="add_test" class="btn btn-primary">Add Test Details</button>
            <a class="btn btn-primary" href="view_test_details.php">Manage Test Details</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
