<?php
session_start();
include('config.php');

// Initialize location variable
$centerLocation = "";
// Initialize $result as null
$result = null;
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve location value from the form
    $centerLocation = $_POST['center_location'];
    
    // Construct the SQL query based on the selected location
    $query = "SELECT * FROM diagnostic_centers WHERE location = '$centerLocation'";
    $result = mysqli_query($conn, $query);

    // Check if the query was executed successfully
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    
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

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
    <div class="carousel-item active">
        <img class="d-block w-100" src="images/sl1 (1).jpg" alt="First slide" height="700px">
        <div class="carousel-caption d-none d-md-block heading">
            <h2>Welcome to Online Diagnostic Center</h2>
            <h4>Your Trusted Partner in Health Diagnosis</h4>
        </div>
    </div>
    <div class="carousel-item">
        <img class="d-block w-100" src="images/sl1 (2).jpg" alt="Second slide" height="700px">
        <div class="carousel-caption d-none d-md-block heading">
            <h2>Expert Diagnostic Services at Your Fingertips</h2>
            <h4>Quick and Accurate Results Guaranteed</h4>
        </div>
    </div>
    <div class="carousel-item">
        <img class="d-block w-100" src="images/sl1 (3).jpg" alt="Third slide" height="700px">
        <div class="carousel-caption d-none d-md-block heading">
            <h2>State-of-the-Art Facilities and Equipment</h2>
            <h4>Ensuring the Highest Quality of Service</h4>
        </div>
    </div>
</div>

    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<div class="container mt-4">
    <h3 class="text-center mb-4">Search Diagnostic Centers by Location</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label for="center_location">Enter Location</label>
            <input type="text" class="form-control" id="center_location" name="center_location" value="<?php echo $centerLocation; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<!-- Display search results -->
<div class="container mt-4">
    <h3 class="text-center mb-4">Search Results</h3>
    <?php if ($result !== null) { // Check if $result is not null ?>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <li>
                        <a href="view_tests.php?center_id=<?php echo $row['center_id']; ?>"><?php echo $row['center_name']; ?> (<?php echo $row['location']; ?>)</a>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No diagnostic centers found in the specified location.</p>
        <?php } ?>
    <?php } ?>
</div>
<section class="py-5">
  <div class="container">
    <h2>Welcome to Diagnostic Center</h2>
    <div class="row">
      <div class="col-md-6 mb-4">
        <img src="https://images.unsplash.com/photo-1595311182166-d63273ddc386?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=386&q=80/500x300" alt="Diagnostic Center" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <p>At Diagnostic Center, we are committed to providing our patients with the highest quality of care possible. We offer a wide range of diagnostic services, including:</p>
        <ul>
          <li>Blood tests</li>
          <li>Imaging services</li>
          <li>Specialty tests</li>
          <li>Health screenings</li>
        </ul>
        <p>We use state-of-the-art equipment and techniques to ensure that our patients receive accurate and timely results. Our team of experienced professionals is dedicated to providing personalized care to each and every patient.</p>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container">
    <h2>Our Team</h2>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="https://images.unsplash.com/photo-1582719471327-5bd41fcf7f7f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Nnx8bGFiJTIwZG9jdG9yfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" alt="Card image cap" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Dr. Jane Doe</h5>
            <p class="card-text">Medical Director</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="https://images.unsplash.com/photo-1609188076864-c35269136b09?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTF8fGxhYiUyMGRvY3RvcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" alt="Card image cap" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Dr. John Smith</h5>
            <p class="card-text">Radiologist</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="https://images.unsplash.com/photo-1578496479763-c21c718af028?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTd8fGxhYiUyMGRvY3RvcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" alt="Card image cap" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Dr. Sarah Lee</h5>
            <p class="card-text">Pathologist</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container mt-4">
    <?php 
    $feedbackQuery = "SELECT tr.test_name, tr.status, tf.feedback, tf.rating, m.Username AS patient_name
    FROM test_requests tr
    LEFT JOIN test_feedback tf ON tr.request_id = tf.test_id
    LEFT JOIN patients m ON tf.patient_id = m.id
    WHERE tr.status = 'approved'";
    
    $feedbackResult = mysqli_query($conn, $feedbackQuery);

    // Check if the query was executed successfully
    if (!$feedbackResult) {
        die("Query failed: " . mysqli_error($conn));
    }
    ?>
    <h3 class="text-center mb-4">User Feedback</h3>
    <?php if (mysqli_num_rows($feedbackResult) > 0) { ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($feedbackResult)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Patient: <?php echo $row['patient_name']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Test Name: <?php echo $row['test_name']; ?></h6>
                            <p class="card-text">Feedback: <?php echo $row['feedback']; ?></p>
                            <p class="card-text">
                                Rating: <?php echo str_repeat('<i class="fas fa-star text-warning"></i>', $row['rating']); ?>
                                <?php echo str_repeat('<i class="far fa-star text-warning"></i>', 5 - $row['rating']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>No feedback available.</p>
    <?php } ?>
</div>


<!-- Add the following script to include FontAwesome icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>


<script
src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
crossorigin="anonymous"
></script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
crossorigin="anonymous"
></script>
<script
src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
crossorigin="anonymous"
></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
        <?php
include 'footer.php';
?>

</body>
</html>