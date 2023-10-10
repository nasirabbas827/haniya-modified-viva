<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

if (isset($_POST['update_status'])) {
    $requestId = $_POST['request_id'];
    $status = $_POST['status'];

    $query = "UPDATE test_requests SET status = '$status' WHERE request_id = $requestId";
    mysqli_query($conn, $query);

    header('location: test_requests.php');
}

if (isset($_GET['request_id'])) {
    $requestId = $_GET['request_id'];
    $query = "SELECT * FROM test_requests WHERE request_id = $requestId";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Request</title>
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
    <h3>Edit Request</h3>
    <form method="POST" action="edit_request.php">
        <div class="form-group">
            <label for="request_id">Request ID</label>
            <input type="text" class="form-control" id="request_id" name="request_id" value="<?php echo $row['request_id']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Approved" <?php if ($row['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="update_status">Update Status</button>
    </form>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
