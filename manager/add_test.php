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
    // Handle the case where the manager is not associated with any diagnostic center
    // You can redirect or display an error message as needed
    $errorMessage = 'You are not currently assigned to manage any diagnostic center.';
}

// Add category
if (isset($_POST['add_category'])) {
    $categoryName = $_POST['category_name'];
    $query = "INSERT INTO test_categories (category_name, center_id) VALUES ('$categoryName', $centerId)";
    if (mysqli_query($conn, $query)) {
        $successMessage = 'Category added successfully.'; // Set success message
    } else {
        $errorMessage = 'Error: ' . mysqli_error($conn); // Set error message
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Test Categories</title>
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
    <h2 class="mt-3 text-center mb-4">Diagnostic Test Categories</h2>

    <!-- Add category form -->
    <h3 class="mt-3 text-center mb-4">Add Category</h3>

    <?php if (!empty($successMessage)) { ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php } ?>

    <?php if (!empty($errorMessage)) { ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="category_name">Category Name: </label>
            <input type="text" name="category_name" placeholder="Category Name" class="form-control">
        </div>
        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        <a class="btn btn-primary" href="view_categories.php">Manage Categories</a>
    </form>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
