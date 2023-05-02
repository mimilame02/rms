<?php

session_start();
require_once '../includes/dbconfig.php';

// Retrieve form data
$bill_type_name = $_POST['bill_type_name'];
$bill_type_amount = $_POST['bill_type_amount'];

// Validate form data (e.g. check if required fields are not empty)

// Insert data into database
$sql = "INSERT INTO bill_types (name, amount) VALUES ('$bill_type_name', $bill_type_amount)";

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
