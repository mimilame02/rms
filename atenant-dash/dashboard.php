<?php

    //resume session here to fetch session values
    session_start();
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */

    //if the above code is false then html below will be displayed

  $current_month = date('F', strtotime('now'));
  $_SESSION['current_month'] = $current_month;


    require_once '../tools/variables.php';
    $page_title = 'RMS | Dashboard';
    $dashboard = 'active';

    require_once '../includes/header.php';
    
?>
<body>
  <div class="container-scroller">
      <?php
        require_once '../includes/navbar.php';
      ?>
    <div class="container-fluid page-body-wrapper">
      <?php
          require_once 'tenant_sidebar.php';
        ?>
      <div class="main-panel">
            <div class="content-wrapper">
              <div class="row">
                <div class="col-md-12 grid-margin">
                <div class="row">
                  <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">DASHBOARD</h3>
                    <h6 class="font-weight-normal mb-0"><?php echo "<div class='text-capitalize'> Welcome, {$_SESSION['username']}!</div>" ?></h6>
                  </div>
                </div>
              </div>       
              <div class="add-tenant-container">
                <div class="add-tenant-container">
                </div>
              </div>
            </div>         
              
         
        </div>
      </div>
    </div>
  </div>
    




</body>

