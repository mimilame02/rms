<?php

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
    //if the above code is false then html below will be displayed

    require_once '../tools/variables.php';
    $page_title = 'RMS | Admin Dashboard';
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
      require_once '../includes/sidebar.php';
    ?>
  <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="row">
                  <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bolder">DASHBOARD</h3>
                    <h6 class="font-weight-normal mb-0">Welcome,<span class="text-primary">Pink!</span></h6>
                  </div>
                </div>
              </div>
              <div class="card-group px-4 ml-4 ">
                <div class="card w-50 mb-3 rounded mx-3">
                    <div class="card-body">
                        <div class="">
                            <div class="card-title float-right fs-2">0</div>
                            <i class='bx bx-user cart' style="font-size: 25px;"></i>
                                <div class="card-text py-3">Total Tenants</div>
                        </div>
                      </div>
                  </div>
                
                <div class="card w-50 mb-3 rounded mx-3">
                    <div class="card-body">
                        <div class="">
                            <div class="card-title float-right fs-2">0</div>
                            <i class='bx bxs-user-rectangle cart two' style="font-size: 25px;"></i>
                                <div class="card-text py-3">Total Landlords</div>
                          </div>
                      </div>
                </div>
                
                <div class="card w-50 mb-3 rounded mx-3">
                    <div class="card-body">
                        <div class="">
                            <div class="card-title float-right fs-2">0</div>
                            <i class='bx bx-building-house cart three' style="font-size: 25px;"></i>
                                <div class="card-text py-3">Total Property</div>
                          </div>
                      </div>
                </div>
                
                <div class="card w-50 mb-3 rounded mx-3">
                    <div class="card-body">
                        <div class="">
                            <div class="card-title float-right fs-2">0</div>
                            <i class='bx bx-home-alt-2 cart four' style="font-size: 25px;"></i>
                                <div class="card-text py-3">Total Property</div>
                          </div>
                      </div>
                </div>
                
              </div>

              <div class="card w-50 md-4  px-4 ml-4 rounded mx-3" style="left: 29px;">
                  <div class="card-body">
                      <div class="">
                          <div class="card-title">Calendar</div>
                          <div class="content">
                            <div id='calendar'></div>
                          </div>
                      </div>
                  </div>
                </div>
            </div>  
          </div>   
      </div>
</body>

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src='../fullcalendar/packages/core/main.js'></script>
    <script src='../fullcalendar/packages/interaction/main.js'></script>
    <script src='../fullcalendar/packages/daygrid/main.js'></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid' ],
      defaultDate: '2020-02-12',
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2020-02-01'
        },
        {
          title: 'Conference',
          start: '2020-02-11',
          end: '2020-02-13'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2023-02-28'
        }
      ]
    });

    calendar.render();
  });

    </script>

    <script src="../js/main.js"></script>