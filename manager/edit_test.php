<?php
session_start(); // Start session
include('config.php');
// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$email = $_SESSION['email']; // Get the user's email from the session


// Check if test ID is provided
if (!isset($_GET['test_id'])) {
    // Redirect or display error message
}

$testId = $_GET['test_id'];

// Fetch test detail based on test ID
$query = "SELECT * FROM test_details WHERE test_id = $testId";
$result = mysqli_query($conn, $query);
$testDetail = mysqli_fetch_assoc($result);

// Fetch diagnostic test categories
$categoryQuery = "SELECT * FROM test_categories";
$categories = mysqli_query($conn, $categoryQuery);

// Handle form submission
if (isset($_POST['edit_test'])) {
    $testName = $_POST['test_name'];
    $testCategory = $_POST['test_category'];
    $cost = $_POST['cost'];
    $reportingTime = $_POST['reporting_time'];
    
    // Update the test detail in the database
    $updateQuery = "UPDATE test_details SET test_name = '$testName', category_id = $testCategory, cost = '$cost', reporting_time = '$reportingTime' WHERE test_id = $testId";
    if (mysqli_query($conn, $updateQuery)) {
        // Redirect or display success message
        header('Location: test_details.php');
        exit();
    } else {
        // Error occurred while updating the test detail
        echo "Error updating test detail: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<?php
include('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Test Detail</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert library -->

    <style>
        body{
            background-color: antiquewhite;
        }
    </style>

<body>
    <div class="container mt-5">
        <h2>Edit Test Detail</h2>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" class="form-control" name="test_name" placeholder="Test Name" value="<?php echo $testDetail['test_name']; ?>">
            </div>
            <div class="form-group">
                <select class="form-control" name="test_category">
                    <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $testDetail['category_id']) echo 'selected'; ?>><?php echo $category['category_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="cost" placeholder="Cost" value="<?php echo $testDetail['cost']; ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="reporting_time" placeholder="Reporting Time" value="<?php echo $testDetail['reporting_time']; ?>">
            </div>
            <button type="submit" name="edit_test" class="btn btn-primary">Save</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
