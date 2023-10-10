<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include('config.php');

if (isset($_POST['submit'])) {
    $id = $_SESSION['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contactno = $_POST['contactno'];
    $password = $_POST['password'];

    $query = "UPDATE patients SET Username='$username', Email='$email', contactno='$contactno', Password='$password' WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['contactno'] = $contactno;
        header("Location: home.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

$id = $_SESSION['id'];
$query = "SELECT * FROM patients WHERE id='$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    <style>
        /* Add some custom CSS styling */
        body {
            background-color: #F8F8F8;
        }
        h2 {
            color: #484848;
            font-weight: bold;
            margin-top: 20px;
        }
        form {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px 0px #DDDDDD;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

    </style>
</head>
<body>
    <?php 
    include('navbar.php');
    ?>
    <div class="container mt-5 mb-5">
    <h2>Update Profile</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" value="<?php echo $row['Username']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo $row['Email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="contactno">Contact No:</label>
            <input type="text" class="form-control" name="contactno" value="<?php echo $row['contactno']; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <!-- Change input type to "text" to make the password visible -->
            <input type="text" class="form-control" name="password" value="<?php echo $row['Password']; ?>" required>
        </div>
        <!-- Use a Bootstrap button class for styling -->
        <button type="submit" name="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-danger" onclick="deleteProfile()">Delete Profile</button>

    </form>
</div>

<!-- Link Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
function deleteProfile() {
  if (confirm("Are you sure you want to delete your profile?")) {
    // Submit a POST request to a PHP script that deletes the user's profile
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Redirect to the login page after successful deletion
        window.location.replace("login.php");
      }
    };
    xhttp.open("POST", "delete_profile.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id=<?php echo $_SESSION['id']; ?>");
  }
}
</script>
<?php
include('footer.php');
?>
</body>
</html>
