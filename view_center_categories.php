<?php
session_start();
include('config.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Check if center_id is provided in the URL
if (!isset($_GET['center_id'])) {
    header("Location: index.php");
    exit();
}

$centerId = $_GET['center_id'];

// Fetch center details
$centerQuery = "SELECT * FROM diagnostic_centers WHERE center_id = $centerId";
$centerResult = mysqli_query($conn, $centerQuery);
$center = mysqli_fetch_assoc($centerResult);

// Fetch test categories offered by the selected center
$categoriesQuery = "SELECT category_id, category_name 
                    FROM test_categories
                    WHERE center_id = $centerId";
$categoriesResult = mysqli_query($conn, $categoriesQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Test Categories</title>
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
    <h3>Test Categories Offered by <?php echo $center['center_name']; ?></h3>
    <ul class="list-group">
        <?php while ($category = mysqli_fetch_assoc($categoriesResult)) { ?>
            <li class="list-group-item">
                <a href="view_tests_in_category.php?center_id=<?php echo $centerId; ?>&category_id=<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></a>
            </li>
        <?php } ?>
    </ul>
</div>

</body>
</html>
