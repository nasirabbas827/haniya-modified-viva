<?php
session_start(); // Start session
include('config.php');
// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$email = $_SESSION['email']; // Get the user's email from the session



// Function to sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}


// Update an existing patient
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_patient'])) {
    $patientId = $_POST['patient_id'];
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $contactno = sanitizeInput($_POST['contactno']);
    $password = sanitizeInput($_POST['password']);

    $sql = "UPDATE patients SET Username='$username', Email='$email', contactno='$contactno', Password="YOUR_OWN_API_KEY" WHERE id='$patientId'";

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
    <style>
        body {
            background-color: antiquewhite;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            color: #fff;
            background-color: #5a6268;
            border-color: #545b62;
        }

        .btn-danger:hover,
        .btn-danger:focus {
            color: #fff;
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>
<?php
include('navbar.php');
?>

<div class="container mt-5">
    
    <h3 class="mt-4 mb-4 text-center">Patient Records</h3>
    <a class="mt-4 mb-4 float-right btn btn-primary" href="add_patient.php">Add Patients</a>

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
