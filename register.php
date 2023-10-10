<?php
$message = '';

if(isset($_POST['username'])){
	
	$username=$_POST['username'];
	$email=$_POST['email'];
	$password=$_POST['password'];
	$cpassword=$_POST['cpassword'];
	$contact=$_POST['contact'];
	
	$conn = mysqli_connect('localhost', 'root', '', 'diagnostice');

if (!$conn) {
	die("Connection failed");}
	
	
//securing data
$username=preg_replace("#[^0-9a-z]#i","","$username");
$password=mysqli_real_escape_string($conn,$_POST['password']);	
$email=mysqli_real_escape_string($conn,$_POST['email']);
$contact=mysqli_real_escape_string($conn,$_POST['contact']);
	
//check for duplicates
	
$user_query=mysqli_query($conn,"SELECT username FROM patients WHERE username='$username'LIMIT 1")or die("Could not check username");
$count_username=mysqli_num_rows($user_query);
	
$email_query=mysqli_query($conn,"SELECT email FROM patients WHERE email='$email'LIMIT 1")or die("Could not check Email");
$count_email=mysqli_num_rows($email_query);

$contact_query=mysqli_query($conn,"SELECT contactno FROM patients WHERE contactno='$contact'LIMIT 1")or die("Could not check your contact");
$count_contact=mysqli_num_rows($contact_query);


	
if($count_username>0){
	$message='Your username is already taken';
}elseif($count_email>0){
	$message='Your email is already in use';
}elseif($count_contact>0){
	$message='Your contact is already in use';
}else{
	
//insert the patients
$query=mysqli_query($conn,"INSERT INTO patients (Username,Email,contactno,Password)VALUES('$username','$email','$contact','$password')") or die("Could not insert your information");

header("Location:login.php");
	
}
	
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Form</title>
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
    <form name="frm" id="form" action="register.php" method="POST" class="form">
        <h2 class="text-center">Registration Form</h2>
        <p class="text-center">It's quick and easy.</p>

        <div id="error" class="text-center"><?php echo $message; ?></div>

        <div class="form-group">
            <label for="username">Full Name:</label>
            <input class="form-control" type="text" name="username" id="username" placeholder="Full Name">
        </div>
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input class="form-control" type="email" name="email" id="email" placeholder="Email Address">
        </div>
        <div class="form-group">
            <label for="contact">Phone Number:</label>
            <input class="form-control" type="text" name="contact" id="contact" placeholder="Phone Number">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input class="form-control" type="password" id="password" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="cpassword">Confirm Password:</label>
            <input class="form-control" type="password" id="cpassword" name="cpassword" placeholder="Confirm password">
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit" name="register" id="submit">Register</button>
        </div>
        <div class="mb-4 text-center">Register as Manager <a href="./manager/manager_register.php">Here</a></div>
    </form>
</div>

<!-- Add jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
