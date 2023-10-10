<?php
session_start(); // Start session

include('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user is approved
    $sql = "SELECT * FROM managers WHERE email='$email' AND status='approved'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];
        $managerId = $row['id'];

        // Verify the password without hashing (for demonstration purposes only)
        if ($password === $storedPassword) {
            // Check if the manager is associated with any diagnostic center
            $centerQuery = "SELECT * FROM diagnostic_centers WHERE manager_id='$managerId'";
            $centerResult = $conn->query($centerQuery);

            if ($centerResult->num_rows > 0) {
                $_SESSION['email'] = $email;
                $_SESSION['managerId'] = $managerId;
                header("Location: manager_dashboard.php"); // Redirect to the dashboard page after successful login
                exit();
            } else {
                $error = "You are not assigned to manage any diagnostic center.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "You are not an approved user.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: antiquewhite;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">Online Diagnostic Center</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="manager_register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center" >Manager Login</h2>
        <?php
        if (isset($error)) {
            echo '<p style="color: red;">' . $error . '</p>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
