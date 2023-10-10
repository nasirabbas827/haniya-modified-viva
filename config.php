<?php
$conn = mysqli_connect('localhost', 'root', '', 'diagnostice');
if (!$conn) {
	die("Connection failed".mysqli_connect_error());
}
?>