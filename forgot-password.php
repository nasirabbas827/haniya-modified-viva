<?php
session_start();

require "mail.php";

$conn = mysqli_connect('localhost', 'root', '', 'diagnostice');
if (!$conn) {
    die("Connection failed" . mysqli_connect_error());
}

$message = '';
$message2 = '';

$mode = "enter_email";
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
}

//something is posted

if (count($_POST) > 0) {
    switch ($mode) {
        case 'enter_email':
            $email = $_POST['email'];
            //validate the email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Please enter a valid email";
            } elseif (!valid_email($email)) {
                $message = "Email entered was not found";
            } else {
                $_SESSION['forgot']['email'] = $email;
                send_email($email);

                header("Location: forgot-password.php?mode=enter_code");
                die;
            }

            break;

        case 'enter_code':
            $code = $_POST['code'];
            $result = code_authentication($code);

            if ($result == "The Code is Correct") {
                $_SESSION['forgot']['code'] = $code;
                header("Location: forgot-password.php?mode=enter_password");
                die;
            } else {
                $message = $result;
            }
            break;

        case 'enter_password':
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];

            if ($password != $cpassword) {
                $message = "Passwords do not match";
            } elseif (!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])) {
                header("Location: forgot-password.php");
                die;
            } else {
                save_password($password);
                if (isset($_SESSION['forgot'])) {
                    unset($_SESSION['forgot']);
                }
                header("Location: login.php");
                die;
            }

            break;

        default:
            // code...
            break;
    }
}

function send_email($email)
{
    global $conn;
    $expire = time() + (60 * 10);
    $code = rand(10000, 99999);
    $email = addslashes($email);

    $query = "INSERT INTO resets (Email,Code,Expire)VALUES('$email','$code','$expire')";
    $query_run = mysqli_query($conn, $query) or die("Could not update");

    //send email
    send_mail($email, 'Password Reset', "Your code is " . $code);
}

function code_authentication($code)
{
    global $conn;
    $code = addslashes($code);
    $expire = time();
    $email = addslashes($_SESSION['forgot']['email']);

    $query = "SELECT * FROM resets WHERE code = '$code' && email = '$email' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['Expire'] > $expire) {
                return "The Code is Correct";
            } else {
                return "The Code Has Expired";
            }
        } else {
            return "The Code You've Entered is Incorrect";
        }
    }
    return "The Code You've Entered is Incorrect";
}

function save_password($password)
{
    global $conn;
    $password = $_POST['password'];
    $email = addslashes($_SESSION['forgot']['email']);

    $query = "UPDATE patients SET password ='$password' WHERE email = '$email' LIMIT 1";
    mysqli_query($conn, $query);
}

function valid_email($email)
{
    global $conn;
    $email = addslashes($email);

    $query = "SELECT * FROM patients WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            return true;
        }
    }

    return false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<!-- Link custom CSS -->
	
    <style>
body {
            background-color: antiquewhite;
        }
		/* Center the form vertically */
		.form {
    margin-top: 5%;
}

/* Add some spacing between the form elements */
.form-group {
    margin-bottom: 1.5rem;
}

/* Style the submit button */
.button {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.button:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

/* Style the login link */
.login-link {
    margin-top: 1.5rem;
    color: #007bff;
}

.login-link a {
    color: #007bff;
}

.login-link a:hover {
    text-decoration: none;
    color: #0056b3;
}
.form {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.form input[type=text], .form input[type=email], .form input[type=password] {
    border: none;
    border-radius: 20px;
    padding: 10px 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.form input[type=text]:focus, .form input[type=email]:focus, .form input[type=password]:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(0, 0, 255, 0.5);
}

.form button[type=submit] {
    background-color: #007bff;
    border: none;
    border-radius: 20px;
    padding: 10px 20px;
    margin-top: 20px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    color: #fff;
    transition: all 0.3s ease-in-out;
}

.form button[type=submit]:hover {
    background-color: #0069d9;
    cursor: pointer;
}
.register-box {
    background-color:#ced4da;
    margin-top: 50px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    border: 1px solid #ced4da;
    padding: 20px;
    border-radius: 5px;
}


	</style>
</head>
<body>

	<?php
switch ($mode) {
    case 'enter_email':
        ?>
    <div class="container">
		<small id="emailHelp" class="form-text"><?php print("$message2");?></small><br>

        <div class="row">
            <div class="col-md-4 offset-md-4 form">

                <form action="forgot-password.php" method="POST" autocomplete="">
                    <h2 class="text-center">Forgot Password</h2>
					<br/>

                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Enter email address">
                    </div>

                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Reset">
                    </div>

                    <div class="form-group">
                      <a href="login.php">Back</a>
                    </div>
                </form>

                <?php
break;
    case 'enter_code':
        ?>
<div class="container">
    <div class="register-box">
        <h1 class="text-center text-decoration-underline">Code Verification</h1>
        <p class="text-center">Enter the code that was sent to your email. <br/><strong>Code expires in 10 minutes!</strong></p>
        <small id="emailHelp" class="form-text"><?php print("$message2");?></small>
        <div class="text-center text-danger" id="error"><?php echo $message; ?></div>
        <form action="forgot-password.php?mode=enter_code" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="code" placeholder="Enter Code">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block" name="SavePassChanges">Next</button>
            </div>
            <div class="form-group">
                <a href="forgot-password.php" class="btn btn-secondary btn-block">Restart Reset</a>
            </div>
        </form>
    </div>
</div>



    <?php
break;
    case 'enter_password':
        ?>
    <div class="register-box">
        <h1 class="text-center " style="text-decoration: underline; margin-top: 30px;">Reset Password</h1>
        <br>
        <small class="text-center" id="emailHelp"><?php print("$message2");?></small><br>
        <form action="forgot-password.php?mode=enter_password" method="post">
            <div class="form-group">
                <input type="password" class="text-center" name="password" placeholder="Enter New Password" style="width: 60%; margin-left:22%; border-radius: 5px; height:40px;">
            </div>
            <div class="form-group">
                <input type="password" class="text-center" name="cpassword" placeholder="Confirm Password" style="width: 60%; margin-left:22%; border-radius: 5px; height:40px;">
            </div>
            <br/>
            <div class="form-group">
                <button type="submit" class="text-center" name="SavePassChanges" style="width: 20%; margin-left:40%; background-color: blue; color:white; border:1px; border-radius: 10px; height:30px;">Continue</button>
            </div>
            <div class="form-group">
                      <a href="forgot-password.php">Restart Reset</a>
                    </div>
			
                <?php
break;

    default:
        // code...
        break;
}
?>
		</form></div></div></div></div>
</body>
</html>