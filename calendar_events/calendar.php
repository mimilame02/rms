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
    $page_title = 'RMS | Calendar Events';
    $calendar_events = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';

    // Fetch year and month from the URL
    $year = isset($_GET['year']) ? intval($_GET['year']) : 0;
    $month = isset($_GET['month']) ? intval($_GET['month']) : 0;

    // Fetch the events data for the given month
    $sql = "SELECT id, title, start, end, description FROM events WHERE YEAR(start) = ? AND MONTH(start) = ?";

    // Prepare and bind the statement
    $query = $conn->prepare($sql);
    $query->bind_param("ii", $year, $month);

    // Execute the statement
    $query->execute();

    // Bind the result variables
    $query->bind_result($id, $title, $start, $end, $description);

    // Initialize the events array
    $events = array();

    // Fetch the data
    while ($query->fetch()) {
      $event = array(
        'id' => $id,
        'title' => $title,
        'start' => $start,
        'end' => $end,
        'description' => $description
      );

      // Add the event to the events array
      $events[] = $event;
    }

    // Close the statement
    $query->close();

    // Convert the events array to a JSON string
    $events_json = json_encode($events);
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
          <div class="row mt-4">
            <div class="card card-light-blue">
              <p class="fw-bolder mt-3 mb-0 fs-3 ml-3">CALENDAR</p>
              <div class="card-body">
                <div class="fs-5 mb-2 mx-2">
                  <div id='calendar'></div>
                </div>
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
      var events = <?php echo $events_json; ?>;

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: events
      });

      calendar.render();
    });
</script>
