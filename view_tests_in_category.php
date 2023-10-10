<?php
session_start();
include('config.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Check if center_id and category_id are provided in the URL
if (!isset($_GET['center_id']) || !isset($_GET['category_id'])) {
    header("Location: index.php");
    exit();
}

$centerId = $_GET['center_id'];
$categoryID = $_GET['category_id'];

// Fetch center details
$centerQuery = "SELECT * FROM diagnostic_centers WHERE center_id = $centerId";
$centerResult = mysqli_query($conn, $centerQuery);
$center = mysqli_fetch_assoc($centerResult);

// Fetch category details
$categoryQuery = "SELECT category_name FROM test_categories WHERE category_id = $categoryID AND center_id = $centerId";
$categoryResult = mysqli_query($conn, $categoryQuery);
$category = mysqli_fetch_assoc($categoryResult);

// Fetch tests within the selected category
$testsQuery = "SELECT * FROM test_details WHERE category_id = $categoryID";
$testsResult = mysqli_query($conn, $testsQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Tests in Category</title>
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
    <h3>Tests in Category: <?php echo $category['category_name']; ?></h3>
    <h4>Offered by <?php echo $center['center_name']; ?></h4>
    <table class="table">
        <thead>
        <tr>
            <th>Test Name</th>
            <th>Cost</th>
            <th>Reporting Time</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($test = mysqli_fetch_assoc($testsResult)) { ?>
            <tr>
                <td><?php echo $test['test_name']; ?></td>
                <td><?php echo $test['cost']; ?></td>
                <td><?php echo $test['reporting_time']; ?></td>
                <td>
                    <a href="request_test.php?test_id=<?php echo $test['test_id']; ?>&center_id=<?php echo $centerId; ?>&category_id=<?php echo $categoryID; ?>"
                       class="btn btn-primary">Request Test</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
