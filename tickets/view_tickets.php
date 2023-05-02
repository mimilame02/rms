<?php

    //resume session here to fetch session values
    session_start();
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
        header('location: ../login/login.php');
    }
  
    //if the above code is false then html below will be displayed

    require_once '../tools/variables.php';
    $page_title = 'RMS | Landlords';
    $landlord = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
?>
<body>
<div class="loading-screen">
  <img class="logo" src="../img/logo-edit.png" alt="logo">
  <?php echo $page_title; ?>
  <div class="loading-bar"></div>
</div>
  <div class="container-scroller">
    <?php
      require_once '../includes/navbar.php';
    ?>
    <div class="container-fluid page-body-wrapper">
    <?php
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'landlord') {
                require_once '../alandlord-dash/landlord_sidebar.php';
            } elseif ($_SESSION['user_type'] == 'admin') {
                require_once '../includes/sidebar.php';
            }
            // Add more conditions for other user types if needed
        } else {
            // Redirect to login or show a default sidebar if the user type is not set
        }
    ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bolder">VIEW TICKET</h3> 
            </div>
            <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="tickets.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col">
                            <h3 class="table-title fw-bolder pb-3">Ticket Details</h3>
                          </div>
                        </div>
                      <div class="col-md-6">
                       <div class="row">
                         <div class="col">
                      <?php
                      $id = $_GET['id'];
                      $sql = "SELECT * FROM tickets WHERE id = $id";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result); 

                       echo "<span class='view_label'>Raised by:</span> <span class='view_result'>" . htmlentities($row['raised_by']) . "</span><br>";
                        echo "<span class='view_label'>Subject:</span> <span class='view_result'>" . htmlentities($row['subject']) . "</span><br>";
                        echo "<span class='view_label'>Date Created:</span> <span class='view_result'>" . htmlentities($row['date_created']) . "</span><br>";
                        echo "<span class='view_label'>Status:</span> <span class='view_result'>" . htmlentities($row['status']) . "</span><br>";
                        echo "<span class='view_label'>Messages/Task/Description:</span> <span class='view_result'>" . htmlentities($row['messages']) . "</span><br>";
                        
      ?>
                    </div>
                        </div>
                      </div>