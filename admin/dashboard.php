<?php
  require_once '../includes/dbconfig.php';

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

    $current_month_name = date('F', strtotime('now'));
    $_SESSION['current_month_name'] = $current_month_name;    
    $current_month = date('m', strtotime('now'));
    $_SESSION['current_month'] = $current_month;
    $current_year = date('Y', strtotime('now'));
    $_SESSION['current_year'] = $current_year;


    // Count total tenants
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tenant");
    $row = mysqli_fetch_assoc($result);
    $totalTenants = $row['total'];

    // Count total landlords
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM landlord");
    $row = mysqli_fetch_assoc($result);
    $totalLandlords = $row['total'];

    // Count total properties
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM properties");
    $row = mysqli_fetch_assoc($result);
    $totalProperties = $row['total'];

    // Count total property units
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM property_units");
    $row = mysqli_fetch_assoc($result);
    $totalPropertyUnits = $row['total'];

    $currentMonth = date('d-m-Y');

    // Get the total income for the current month
    $result = mysqli_query($conn, "SELECT SUM(total_due) AS total_income FROM invoice WHERE status='Paid' AND MONTH(payment_date) = $current_month AND YEAR(payment_date) = $current_year");
    $row = mysqli_fetch_assoc($result);
    $totalIncome = $row['total_income'];

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


    require_once '../tools/variables.php';
    $page_title = 'RMS | Admin Dashboard';
    $dashboard = 'active';

    require_once '../includes/header.php';
    
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
          require_once '../includes/sidebar.php';
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
              <div class="row">
                <div class="col-md-6 order-md-2 grid-margin transparent d-flex">
                  <div class="row flex-row">
                    <div class="col-md-6 mb-4 stretch-card transparent">
                      <div class="card card-tale">
                        <div class="card-body white-text">
                          <span class="bx bx-user icons d-flex flex-row flex-wrap justify-content-between align-items-center mx-auto"><p class="fs-45 mb-2 ff fs-35 pl-5 fw-bolder"><?php echo $totalTenants; ?></p></span>
                          <p class="mb-1 pt-3 fw-bolder">Total Tenants </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4 stretch-card transparent">
                      <div class="card card-dark-blue">
                        <div class="card-body white-text">
                          <span class="bx bxs-user-rectangle icons d-flex flex-row flex-wrap justify-content-between align-items-center mx-auto"><p class="fs-45 mb-2 ff fs-35 pl-5 fw-bolder"><?php echo $totalLandlords; ?></p></span>
                          <p class="mb-1 pt-3 fw-bolder">Total Landlords </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                      <div class="card card-light-blue">
                        <div class="card-body white-text">
                          <span class="bx bx-building-house icons d-flex flex-row flex-wrap justify-content-between align-items-center mx-auto"><p class="fs-45 mb-2 ff fs-35 pl-5 fw-bolder"><?php echo $totalProperties; ?></p></span>
                          <p class="mb-1 pt-3 fw-bolder">Total Buildings </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                      <div class="card card-light-blue">
                        <div class="card-body white-text">
                          <span class="bx bx-home-alt-2 icons d-flex flex-row flex-wrap justify-content-between align-items-center mx-auto"><p class="fs-45 mb-2 ff fs-35 pl-5 fw-bolder"><?php echo $totalPropertyUnits; ?></p></span>
                          <p class="mb-1 pt-3 fw-bolder">Total Property Units </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 stretch-card mt-5">
                      <div class="card px-3 card-light-green">
                        <div class="card-body text-white mb-0" itemscope itemtype="https://schema.org/FinancialProduct">
                        <meta itemprop="name" content="Total Income for <?php echo $_SESSION['current_month_name']; ?>">
                          <p class="fs-5 mb-4 mt-2 fw-bolder">TOTAL INCOME</p>
                          <div class="row mx-1">
                            <h2 class="fw-bolder mx-2" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                            <span>
                                <p class="fs-45 mb-4 fs-35 fw-bolder" itemprop="price" content="<?php echo $totalIncome; ?>">
                                    <span itemprop="priceCurrency" content="PHP">
                                        &#8369; <?php echo !empty($totalIncome) ? $totalIncome : "0.00"; ?>
                                    </span>
                                </p>
                            </span>
                            </h2>
                            <p class="text-white fs-6 font-weight-500 mb-2">
                              Total income for the month of
                              <strong class="fs-6" itemprop="description"><?php echo $_SESSION['current_month_name']; ?></strong>
                            </p>
                          </div>
                          <div class="d-flex justify-content-end">
                            <button type="button" onclick="window.location.href='../reports/reports.php'" class="view-button">View</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 order-md-1 grid-margin">
                  <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="fw-bolder mb-0 fs-5">CALENDAR</p>
                        <div id='calendar'></div>
                    </div>
                  </div>
                </div>
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
    
</body>

