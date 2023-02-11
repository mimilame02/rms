<?php
  require_once '../includes/dbconfig.php';


  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the DELETE query
    $sql = "DELETE FROM landlord WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the tenant list page if the DELETE query was successful
        header('location: landlords.php');
        exit;
    }
}
?>