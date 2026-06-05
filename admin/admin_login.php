<?php
session_start();
$_SESSION['adminlogin'] = false;
$conn = mysqli_connect('localhost', 'root', '', 'diagnostice');
if ($conn) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        
        $admin_login_query = "SELECT * FROM `admin` WHERE username = '$username' AND password = "YOUR_OWN_API_KEY"";
        $admin_run_login = mysqli_query($conn, $admin_login_query);
        
        if ($admin_run_login) {
            $row_run = mysqli_num_rows($admin_run_login);
            if ($row_run == 1) {
                $_SESSION['username'] = $username;
                $_SESSION['adminlogin'] = true;
                header('location:add_manager.php');
                exit();
            } else {
                echo '<div class="alert alert-danger alert-dismissible" role="alert" id="liveAlert">Wrong
                      <strong> Username or Password!</strong> Try Again!!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        } else {
            echo 'Error executing the login query: ' . mysqli_error($conn);
        }
    }
} else {
    die('Failed to connect to the database: ' . mysqli_connect_error());
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Login</title>
    <!-- All The Attachments -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: antiquewhite;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Diagnostic Center <span>System</span></a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="justify-content-center">
            <div class="align-self-center">
                <form method="post" class="data-form" autocomplete="off">
                    <h1 class="text-center mt-4">Admin Login</h1>
                    <label class="form-text" for="username">Username</label>
                    <input class="form-control" type="text" name="username" id="username" required placeholder="Username">
                    <label class="form-text" for="password">Password</label>
                    <input class="form-control" type="password" name="password" id="password" required placeholder="Password">
                    <div class="text-center mt-4">
                        <button class="btn btn-success" id="submit_btn">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS File -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
