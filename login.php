<?php
session_start();

$message = '';

if(isset($_POST['email'])){
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    $remember=$_POST['remember'];

    $conn = mysqli_connect('localhost', 'root', '', 'diagnostice');
    if (!$conn) {
        die("Connection failed");
    }

    // secure the data
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $pass=mysqli_real_escape_string($conn,$_POST['pass']);	
    $query=mysqli_query($conn,"SELECT * FROM patients WHERE email='$email'AND password='$pass'")or die("Could not check Member");
    $count_query=mysqli_num_rows($query);
    if($count_query==0){
        $message="The information you entered was incorrect!";
    }else{

        // start the sessions
        while($row=mysqli_fetch_array($query)){
            $username=$row['username'];
            $id=$row['id'];
        }
        $_SESSION['id']=$id;
        $_SESSION['username']=$username;
        $_SESSION['email']=$email;
        $_SESSION['remember']=$remember;

        if($remember=="yes"){

            // create the cookies
            setcookie("id_cookie",$id,time()+60*60*24*100,"/");
            setcookie("pass_cookie",$pass,time()+60*60*24*100,"/");	
        }

        header("Location: home.php");
    }	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: antiquewhite;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <a class="navbar-brand" href="index.php">Diagnostic Center</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.php">Register Now</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-5">
    <div class="form login-form animated-form">
        <form action="login.php" method="POST">
            <h2 class="text-center">Login</h2>
            <p class="text-center">Login with your email and password.</p>

            <div id="error" class="text-center"><?php echo $message; ?></div>
            <br/>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input class="form-control" type="email" autocomplete="" id="email" name="email"
                       placeholder="Email Address">
            </div>
            <div class="form-group">
                <label for="pass">Password:</label>
                <input class="form-control" type="password" autocomplete="" id="pass" name="pass"
                       placeholder="Password">
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkbox" name="remember" value="yes"
                           checked="checked">
                    <label class="form-check-label" for="checkbox">Remember me</label>
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary btn-block" type="submit" id="submit" name="login" value="Login">
            </div>
            <br>
            <div class="text-center">Forgot password? <a href="forgot-password.php"> Reset here</a></div>
            <div class="text-center">Not yet a member? <a href="register.php">Register now</a></div>
        </form>
    </div>
</div>

<!-- Add jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
