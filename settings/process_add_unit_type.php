<?php
session_start();
require_once '../includes/dbconfig.php';

// Retrieve form data
$unit_type_name = $_POST['unit_type_name'];

// Insert data into database
$sql = "INSERT INTO unit_type (type_name) VALUES ('$unit_type_name')";

if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


mysqli_close($conn);


    // Redirect back to the settings page
    header("Location: settings.php");
    exit();
?>