<?php
session_start();
include('config.php');

// Check if a center ID is provided in the URL
if (isset($_GET['center_id'])) {
    $centerId = $_GET['center_id'];

    // Retrieve center information
    $centerQuery = "SELECT * FROM diagnostic_centers WHERE center_id = $centerId";
    $centerResult = mysqli_query($conn, $centerQuery);
    $center = mysqli_fetch_assoc($centerResult);

    // Retrieve categories at the selected center
    $categoryQuery = "SELECT * FROM test_categories WHERE center_id = $centerId";
    $categoriesResult = mysqli_query($conn, $categoryQuery);
} else {
    // Redirect to a page or display an error if no center ID is provided
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diagnostic Center Tests</title>
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
    <h3 class="text-center mb-4">Tests Available at <?php echo $center['center_name']; ?></h3>

    <!-- Display categories -->
    <?php if (mysqli_num_rows($categoriesResult) > 0) { ?>
        <ul class="list-group">
            <?php while ($category = mysqli_fetch_assoc($categoriesResult)) { ?>
                <li class="list-group-item">
                    <a href="view_category_tests.php?category_id=<?php echo $category['category_id']; ?>&center_id=<?php echo $centerId; ?>">
                        <?php echo $category['category_name']; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No categories available at this center.</p>
    <?php } ?>
</div>

</body>
</html>
