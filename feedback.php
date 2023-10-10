<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Your database connection code
include('config.php');

// Check if the request_id parameter exists and the test is approved
if (isset($_GET['request_id'])) {
    $requestId = $_GET['request_id'];

    // Fetch the test request details for the given request_id
    $query = "SELECT * FROM test_requests WHERE request_id = '$requestId' AND status = 'approved'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        die("Invalid request ID or test request not found or not approved.");
    }
} else {
    die("Invalid request.");
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];

    // Get patient_id from the session
    $patientId = $_SESSION['id'];

    // Insert the feedback and rating into the test_feedback table
    $insertQuery = "INSERT INTO test_feedback (patient_id, test_id, feedback, rating) VALUES ('$patientId', '$requestId', '$feedback', '$rating')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if (!$insertResult) {
        die("Failed to submit feedback and rating: " . mysqli_error($conn));
    }

    // Redirect back to the test status page after submitting the feedback
    header("Location: test_status.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Give Feedback & Ratings</title>
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
    <h3 class="text-center mb-4">Give Feedback & Ratings</h3>
    <form method="post">
        <div class="form-group">
            <label for="feedback">Feedback:</label>
            <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="rating">Rating (1-5):</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
