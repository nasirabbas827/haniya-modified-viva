<?php
include('config.php');
session_start();
if (!isset($_SESSION['adminlogin']) || !$_SESSION['adminlogin']) {
    header('location: admin_login.php');
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

// Update an existing patient
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_patient'])) {
    $patientId = $_POST['patient_id'];
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $contactno = sanitizeInput($_POST['contactno']);
    $password = sanitizeInput($_POST['password']);

    $sql = "UPDATE patients SET Username='$username', Email='$email', contactno='$contactno', Password='$password' WHERE id='$patientId'";

    if ($conn->query($sql) === TRUE) {
        echo "Patient record updated successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete a patient
if (isset($_GET['delete']) && isset($_GET['patient_id'])) {
    $patientId = $_GET['patient_id'];

    $sql = "DELETE FROM patients WHERE id='$patientId'";

    if ($conn->query($sql) === TRUE) {
        echo "Patient record deleted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve patient records
$sql = "SELECT * FROM patients";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Patients</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<?php
include('navbar.php');
?>

<div class="container mt-5">
    <h2>Manage Patients</h2>

    <!-- Add Patient Form -->
    <h3>Add Patient</h3>
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

    <br>

    <!-- View Patient Records -->
    <h3>Patient Records</h3>
    <?php if ($result->num_rows > 0) { ?>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['Username']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['contactno']; ?></td>
                        <td>
                            <a href="view_patient.php?patient_id=<?php echo $row['id']; ?>" class="btn btn-primary">View</a>
                            <a href="edit_patient.php?patient_id=<?php echo $row['id']; ?>" class="btn btn-secondary">Edit</a>
                            <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?delete=true&patient_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this patient?')" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No patient records found.</p>
    <?php } ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
