<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

// Fetch all feedback along with patient name and test name
$query = "SELECT tf.id, tr.test_name, tf.feedback, tf.rating, m.Username AS patient_name
          FROM test_feedback tf
          LEFT JOIN test_requests tr ON tf.test_id = tr.request_id
          LEFT JOIN patients m ON tf.patient_id = m.id";
$result = mysqli_query($conn, $query);

// Check if the query was executed successfully
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Process the delete action if "delete_id" is set in the URL
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM test_feedback WHERE id = '$deleteId'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if (!$deleteResult) {
        die("Failed to delete feedback: " . mysqli_error($conn));
    }

    // Redirect back to the view_feedback page after deleting the feedback
    header("Location: feedback.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Feedback</title>
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
    <h3 class="text-center mb-4">View Patient Feedback</h3>
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Test Name</th>
                    <th>Feedback</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['test_name']; ?></td>
                        <td><?php echo $row['feedback']; ?></td>
                        <td><?php echo $row['rating']; ?></td>
                        <td>
                            <a href="feedback.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No feedback available.</p>
    <?php } ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
