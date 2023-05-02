<?php

require_once '../includes/dbconfig.php';

// get the landlord id from the query string
$landlord_id = $_GET['id'];

// query the database to get the landlord's email address
$result = mysqli_query($conn, "SELECT email FROM landlord WHERE id=$landlord_id");
$row = mysqli_fetch_assoc($result);
$landlord_email = $row['email'];

// check if the email already exists in the account table
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM account WHERE email='$landlord_email'");
$row = mysqli_fetch_assoc($result);
$count = $row['count'];

// return the email only if it doesn't exist in the account table
if ($count == 0) {
  echo $landlord_email;
}

?>
