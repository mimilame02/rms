<?php

require_once '../includes/dbconfig.php';

// get the tenant id from the query string
$tenant_id = $_GET['id'];

// query the database to get the tenant's email address
$result = mysqli_query($conn, "SELECT email FROM tenant WHERE id=$tenant_id");
$row = mysqli_fetch_assoc($result);
$tenant_email = $row['email'];

// check if the email already exists in the account table
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM account WHERE email='$tenant_email'");
$row = mysqli_fetch_assoc($result);
$count = $row['count'];

// return the email only if it doesn't exist in the account table
if ($count == 0) {
  echo $tenant_email;
}

?>
