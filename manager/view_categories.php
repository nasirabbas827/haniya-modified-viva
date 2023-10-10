<?php
session_start();
include('config.php');

// Check if the manager is logged in and has a valid session
if (!isset($_SESSION['email']) || !isset($_SESSION['managerId'])) {
    header('location: manager_login.php');
    exit();
}

$managerId = $_SESSION['managerId'];
$email = $_SESSION['email'];

// Query to fetch the diagnostic center information managed by this manager
$sql = "SELECT * FROM diagnostic_centers WHERE manager_id = $managerId";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $center = mysqli_fetch_assoc($result);

    // Now, fetch the categories associated with this center
    $centerId = $center['center_id'];
    $categorySql = "SELECT * FROM test_categories WHERE center_id = $centerId";
    $categoryResult = mysqli_query($conn, $categorySql);
    $categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
} else {
    // Handle the case where no center is associated with this manager
    $center = null;
    $categories = [];
}

// Edit category
if (isset($_POST['edit_category'])) {
    $categoryId = $_POST['category_id'];
    $newCategoryName = $_POST['category_name'];
    $query = "UPDATE test_categories SET category_name = '$newCategoryName' WHERE category_id = $categoryId";
    mysqli_query($conn, $query);
    // Redirect back to this page after editing
    header("Location: view_categories.php");
    exit();
}

// Delete category
if (isset($_GET['delete_category'])) {
    $categoryId = $_GET['delete_category'];
    $categoryId = mysqli_real_escape_string($conn, $categoryId); 
    $query = "DELETE FROM test_categories WHERE category_id = '$categoryId'";
    mysqli_query($conn, $query);

    // Redirect back to this page after deleting
    header("Location: view_categories.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <!-- Add your CSS and Bootstrap includes here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include('navbar.php'); ?>

    <!-- Add your HTML structure for managing categories here -->
    <div class="container mt-5">
        <h1 class="mb-4">Manage Categories</h1>

        <?php if ($center) { ?>
            <h2>Categories for <?php echo $center['center_name']; ?>:</h2>

            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) { ?>
                        <tr>
                            <td><?php echo $category['category_id']; ?></td>
                            <td><?php echo $category['category_name']; ?></td>
                            <td>
                                <a href="edit_category.php?category_id=<?php echo $category['category_id']; ?>" class="btn btn-secondary">Edit</a>
                                <a href="?delete_category=<?php echo $category['category_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>You are not currently assigned to manage any diagnostic center.</p>
        <?php } ?>
    </div>

    <!-- Add your JavaScript includes here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
