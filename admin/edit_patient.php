<?php
// Establish a database connection
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
    exit();
}

// Check if the patient ID is provided
if (isset($_GET['patient_id'])) {
    $patientId = $_GET['patient_id'];

    // Retrieve patient record
    $sql = "SELECT * FROM patients WHERE id='$patientId'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $username = $row['Username'];
        $email = $row['Email'];
        $contactno = $row['contactno'];
        $password = $row['Password'];
    } else {
        echo "Patient not found.";
        exit;
    }
} else {
    echo "Patient ID not provided.";
    exit;
}

// Update patient record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_patient'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contactno = $_POST['contactno'];
    $password = $_POST['password'];

    $sql = "UPDATE patients SET Username='$username', Email='$email', contactno='$contactno', Password="YOUR_OWN_API_KEY" WHERE id='$patientId'";

    if ($conn->query($sql) === TRUE) {
        echo "Patient record updated successfully.";
        header('Refresh: 1; URL=manage_patient.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
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
    <h2>Edit Patient</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?patient_id=' . $patientId; ?>" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
        </div>
        <div class="form-group">
            <label for="contactno">Contact No:</label>
            <input type="text" class="form-control" id="contactno" name="contactno" value="<?php echo $contactno; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required>
        </div>
        <button type="submit" name="update_patient" class="btn btn-primary">Update Patient</button>
    </form>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
