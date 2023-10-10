<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

// Add Manager
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_manager'])) {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $status = "approved"; 

    // Insert manager details into the database
    $sql = "INSERT INTO managers (name, email, password, status) VALUES ('$name', '$email', '$password', '$status')";

    if ($conn->query($sql) === TRUE) {
        $message = "Manager added successfully.";
    } else {
        $error = "Error adding manager: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Managers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: antiquewhite;
        }
</style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h3 class="mt-4 mb-4 text-center" >Add Manager</h3>
        <form action="add_manager.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_manager">Add Manager</button>
        </form>
    </div>
</body>
</html>
