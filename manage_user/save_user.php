<?php
session_start();
require_once '../includes/dbconfig.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
    header('location: ../login/login.php');
}
var_dump($_POST);
if(isset($_POST['save_user'])) {
  $type = $_POST['type'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $tenant_id = $_POST['tenant_name'];
  $landlord_id = $_POST['landlord_name'];

  if ($type == 'landlord') {
    $landlord_id = $_POST['landlord_name'];
    $result = mysqli_query($conn, "SELECT * FROM landlord WHERE id = $landlord_id");
    $row = mysqli_fetch_assoc($result);
    $username = $row['first_name'] . ' ' . $row['last_name'];
    $sql = "INSERT INTO account (username, email, password, type) VALUES ('$username', '$email', '$password', '$type')";


  } elseif ($type == 'tenant') {
    $tenant_id = $_POST['tenant_name'];
    $result = mysqli_query($conn, "SELECT * FROM tenant WHERE id = $tenant_id");
    $row = mysqli_fetch_assoc($result);
    $username = $row['first_name'] . '  ' . $row['last_name'];
    $sql = "INSERT INTO account (username, email, password, type) VALUES ('$username', '$email', '$password', '$type')";


  } else {
    $username = $_POST['name'];
    $sql = "INSERT INTO account (username, email, password, type) VALUES ('$username', '$email', '$password', '$type')";
  }
  // Use password_hash() function to hash the password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Use prepared statements to prevent SQL injection attacks
  $stmt = mysqli_prepare($conn, "INSERT INTO account (username, email, password, type) VALUES (?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $type);
 
  if(mysqli_query($conn, $sql)) {
    $account_id = mysqli_insert_id($conn); // Get the id of the newly inserted row in the 'account' table
  
    if($type == 'landlord') {
      // Update the user_id for the corresponding landlord
      mysqli_query($conn, "UPDATE landlord SET user_id = $account_id WHERE id = $landlord_id");
    } elseif($type == 'tenant') {
      // Update the user_id for the corresponding tenant
      mysqli_query($conn, "UPDATE tenant SET user_id = $account_id WHERE id = $tenant_id");
    }
  
    echo "User created successfully.";
    header('location: manage_user.php');
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  
}  

mysqli_close($conn);



require_once '../includes/header.php';
require_once '../tools/variables.php';
$page_title = 'RMS | Save Users';
$manage_users = 'active';
?>
