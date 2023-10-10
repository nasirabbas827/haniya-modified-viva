<?php
include('config.php');
session_start();
$successMessage = ''; // Variable to store success message

if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

// Add category to a specific diagnostic center
if (isset($_POST['add_category'])) {
    $center_id = $_POST['center_id'];
    $categoryName = $_POST['category_name'];
    
    // Insert the category with the associated center_id
    $query = "INSERT INTO test_categories (center_id, category_name) VALUES ('$center_id', '$categoryName')";
    
    if (mysqli_query($conn, $query)) {
        $successMessage = 'Category added successfully.'; // Set success message
    } else {
        $errorMessage = 'Error: ' . mysqli_error($conn); // Set error message
    }
}

// Fetch diagnostic centers for the dropdown
$centers = array();
$sql = "SELECT center_id, center_name FROM diagnostic_centers";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $centers[] = $row;
    }
} else {
    // Handle any errors
    echo "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Test Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body{
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

    <form method="POST" action="">
        <div class="form-group">
            <label for="center_id">Select Diagnostic Center: </label>
            <select name="center_id" class="form-control">
                <?php foreach ($centers as $center) { ?>
                    <option value="<?php echo $center['center_id']; ?>"><?php echo $center['center_name']; ?></option>
                <?php } ?>
            </select>
        </div>
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
