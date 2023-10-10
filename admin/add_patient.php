<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: adminlogin.php');
    exit();
}
// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Add a new patient
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_patient'])) {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $contactno = sanitizeInput($_POST['contactno']);
    $password = sanitizeInput($_POST['password']);

    $sql = "INSERT INTO patients (Username, Email, contactno, Password) VALUES ('$username', '$email', '$contactno', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New patient added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Patients</title>
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

<div class="container mt-5">


    <!-- Add Patient Form -->
    <h3 class="mt-4 mb-4 text-center">Add Patient</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="contactno">Contact No:</label>
            <input type="text" id="contactno" name="contactno" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <input type="submit" name="add_patient" value="Add Patient" class="btn btn-primary">
    </form>


</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
