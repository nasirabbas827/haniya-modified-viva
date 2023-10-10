<?php
session_start(); // Start session
include('config.php');
// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$email = $_SESSION['email']; // Get the user's email from the session



// Check if the form is submitted
if (isset($_POST['edit_category'])) {
    $categoryId = $_POST['category_id'];
    $newCategoryName = $_POST['category_name'];

    // Update the category name in the database
    $query = "UPDATE test_categories SET category_name = '$newCategoryName' WHERE category_id = $categoryId";
    mysqli_query($conn, $query);
    // Redirect or display success message
    header("Location: add_test.php");
    exit(); // Important to prevent further execution of the script
}

// Fetch the category details
if (isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];

    $query = "SELECT * FROM test_categories WHERE category_id = $categoryId";
    $result = mysqli_query($conn, $query);
    $category = mysqli_fetch_assoc($result);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert library -->

    <style>
        body{
            background-color: antiquewhite;
        }
    </style>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>Edit Category</h2>

    <?php if ($category) { ?>
        <form method="POST" action="">
            <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $category['category_name']; ?>">
            </div>
            <button type="submit" name="edit_category" class="btn btn-primary">Update</button>
        </form>
    <?php } else { ?>
        <p>Invalid category ID.</p>
    <?php } ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
