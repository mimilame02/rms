
<?php

//resume session here to fetch session values
session_start();

require_once '../classes/leases.class.php';

/*
    if user is not login then redirect to login page,
    this is to prevent users from accessing pages that requires
    authentication such as the dashboard
*/
if (!isset($_SESSION['logged-in'])){
    header('location: ../login/login.php');
}

//if the above code is false then code and html below will be executed
$lease = new Leases;
if(isset($_GET['id'])){
    if($lease->lease_delete($_GET['id'])){
        //redirect user to lease page after saving
        header('location: leases.php?deleted=true');
        exit();
    }
}
?>

