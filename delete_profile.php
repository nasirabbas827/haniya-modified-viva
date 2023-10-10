<?php
session_start();
include('config.php');

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  
  // Delete the user's profile from the database
  $query = "DELETE FROM patients WHERE id='$id'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: login.php");
    exit();
  } else {
    echo "Error deleting profile: " . mysqli_error($conn);
  }
}
?>
