<?php

require_once '../includes/dbconfig.php';
require_once '../classes/account.class.php';


    //resume session here to fetch session values
    session_start();
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
        header('location: ../login/login.php');
    }

    require_once '../includes/header.php';
    
?>
<body>
<div class="container-scroller">
  <?php
    require_once '../includes/navbar.php';
  ?>
  <div class="container-fluid page-body-wrapper">
    <?php
        require_once '../includes/sidebar.php';
      ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bolder">PROFILE</h3> 
          </div>
        </div>
        <div class="row">
        <div class="add-page-container">
              
            </div>
            </div>
        <div class="row">
        <div class="col-xl-4">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <!-- Profile picture image-->
                    <img class="img-account-profile rounded-circle mb-2" alt="">
                    <!-- Profile picture help block-->
                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                    <!-- Profile picture upload button-->
                    <button class="btn btn-primary" type="button">Upload new image</button>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <form method="POST">
                        <!-- Form Group (username)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="username">Username </label>
                            <input class="form-control" id="username" type="text" placeholder="Enter your username" value="">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="email">Email </label>
                            <input class="form-control" id="email" type="email" placeholder="Enter your email " value="">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="password">New Password</label>
                            <input class="form-control" id="password" type="password" placeholder="Enter your new password" value="">
                        </div>
                            <div class="d-flex justify-content-end"><div class="d-flex justify-content-end">
                        <!-- Save changes button-->
                        <button class="btn btn-primary ml-auto" type="submit" name="save_changes">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>