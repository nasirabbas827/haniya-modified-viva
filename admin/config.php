<?php
// database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diagnostice";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>