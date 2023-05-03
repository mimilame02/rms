<?php
session_start();
require_once '../includes/dbconfig.php';

// Retrieve form data
$condition_name = $_POST['condition_name'];
$unit_type_picture = $_POST['unit_type_picture'];

// Validate form data (e.g. check if required fields are not empty)

// Insert data into database
$sql = "INSERT INTO unit_condition (condition_name, unit_type_picture) VALUES ('$condition_name', '$unit_type_picture')";

if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
// Close connection
mysqli_close($conn);

 // Redirect back to the settings page
 header("Location: settings.php");
 exit();
?>
