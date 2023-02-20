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
    $page_title = 'RMS | Calendar Events';
    $tenant = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
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
          <div class="row mt-4">
            <div class="card card-light-blue">
              <p class="fw-bolder mb-0">CALENDAR</p>
              <div class="card-body">
                  <div id='calendar'></div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'events.php'
            });
            calendar.render();
        });
        
        $('#calendar').fullCalendar({
          // other options...
          eventClick: function(calEvent, jsEvent, view) {
            // Handle event click here...
          }
        });

        $('#add-event-btn').click(function() {
          // Prompt the user for the event title and start/end dates/times
          var title = prompt("Event Title:");
          var start = moment(prompt("Start Date/Time (YYYY-MM-DD HH:mm):"));
          var end = moment(prompt("End Date/Time (YYYY-MM-DD HH:mm):"));
        
          // Create a new event object
          var event = {
            title: title,
            start: start,
            end: end
          };
        
          // Add the event to the calendar
          $('#calendar').fullCalendar('renderEvent', event, true);
        });
    </script>

</body>