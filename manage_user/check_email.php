<?php
// include database connection code
require_once '../includes/dbconfig.php';

// get the email from the query string
$email = $_GET['email'];

// query the database to check if the email already exists in the account table
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM account WHERE email='$email'");
$row = mysqli_fetch_assoc($result);
$count = $row['count'];

// return a JSON response indicating whether the email exists or not
$response = array('exists' => ($count > 0));
echo json_encode($response);
?>
