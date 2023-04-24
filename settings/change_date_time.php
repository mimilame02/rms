<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $custom_date = $_POST["custom_date"];
  $custom_time = $_POST["custom_time"];
  
  $_SESSION["custom_date_time"] = $custom_date . " " . $custom_time;
  
  // Redirect back to the settings page
  header("Location: settings.php");
  exit();
}
?>
