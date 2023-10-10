<?php
session_start();
include('config.php');

// Check if both center_id and category_id are provided in the URL
if (isset($_GET['center_id']) && isset($_GET['category_id'])) {
    $centerId = $_GET['center_id'];
    $categoryId = $_GET['category_id'];

    // Retrieve center and category information
    $centerQuery = "SELECT * FROM diagnostic_centers WHERE center_id = $centerId";
    $centerResult = mysqli_query($conn, $centerQuery);
    $center = mysqli_fetch_assoc($centerResult);

    $categoryQuery = "SELECT * FROM test_categories WHERE category_id = $categoryId";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    $category = mysqli_fetch_assoc($categoryResult);

    // Retrieve tests in the selected category at the selected center
    $testsQuery = "SELECT * FROM test_details WHERE category_id = $categoryId";
    $testsResult = mysqli_query($conn, $testsQuery);
} else {
    // Redirect to a page or display an error if either center_id or category_id is missing
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Tests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include any other necessary CSS files -->

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <a class="navbar-brand" href="index.php">Diagnostic Center</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About Us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Services</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="register.php">Register Now</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./admin/admin_login.php">Admin Login</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
    <h3 class="text-center mb-4">Tests in Category: <?php echo $category['category_name']; ?></h3>

    <!-- Display tests in the category -->
    <?php if (mysqli_num_rows($testsResult) > 0) { ?>
        <ul class="list-group">
            <?php while ($test = mysqli_fetch_assoc($testsResult)) { ?>
                <li class="list-group-item">
                    <strong><?php echo $test['test_name']; ?></strong><br>
                    Cost: $<?php echo $test['cost']; ?><br>
                    Reporting Time: <?php echo $test['reporting_time']; ?> hours
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No tests available in this category at <?php echo $center['center_name']; ?></p>
    <?php } ?>
</div>

</body>
</html>
