<?php
  require_once '../includes/dbconfig.php';

    //resume session here to fetch session values
    session_start();
    $user_id = $_SESSION['user_id'];
    $tenant_id = $_SESSION['tenant_id'];

    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'tenant'){
        header('location: ../login/login.php');
    }

    $current_month_name = date('F', strtotime('now'));
    $_SESSION['current_month_name'] = $current_month_name;    
    $current_month = date('m', strtotime('now'));
    $_SESSION['current_month'] = $current_month;
    $current_year = date('Y', strtotime('now'));
    $_SESSION['current_year'] = $current_year;

    // Count total pending tickets (Open)
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tickets WHERE status='Open'");
    $row = mysqli_fetch_assoc($result);
    $totalPendingTickets = $row['total'];

    // Count total closed tickets (Closed or Resolved)
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tickets WHERE status='Closed'");
    $row = mysqli_fetch_assoc($result);
    $totalClosedTickets = $row['total'];

    $currentMonth = date('d-m-Y');
    // Get the total income for the current month
    $tenant_id = mysqli_query($conn, "SELECT tenant_id FROM invoice WHERE id='tenant_id'");// Assuming you have tenant_id in session

    $result = mysqli_query($conn, "SELECT i.tenant_id, i.balance
    FROM invoice i
    JOIN tenant t ON t.id = i.tenant_id
    WHERE i.status='Paid' AND MONTH(i.payment_date) = $current_month AND YEAR(i.payment_date) = $current_year AND t.user_id = $user_id
    GROUP BY i.tenant_id");
    
    $totalBalance = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $totalBalance += $row['balance'];
    }
    


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
    $page_title = 'RMS | Dashboard';
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
            <div class="row">
                <div class="col-md-6 order-md-2 grid-margin transparent d-flex">
                  <div class="row flex-row">
                    <div class="col-md-6 mb-4 stretch-card transparent">
                      <div class="card card-dark-blue">
                        <div class="card-body white-text">
                          <span class="bx bx-comment-error icons d-flex flex-row flex-wrap justify-content-between align-items-center mx-auto"><p class="fs-45 mb-2 ff fs-35 fw-bolder"><?php echo $totalPendingTickets; ?></p></span>
                          <p class="mb-1 pt-3 fw-bolder">Pending Tickets </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4 stretch-card transparent">
                      <div class="card card-dark-blue">
                        <div class="card-body white-text">
                          <span class="bx bx-comment-check icons d-flex flex-row flex-wrap justify-content-between align-items-center mx-auto"><p class="fs-45 mb-2 ff fs-35 fw-bolder"><?php echo $totalClosedTickets; ?></p></span>
                          <p class="mb-1 pt-3 fw-bolder">Closed Tickets </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-4 mb-lg-0 stretch-card transparent">
                      <div class="card px-3 card-light-blue">
                        <div class="card-body white-text mb-0" itemscope itemtype="https://schema.org/FinancialProduct">
                          <meta itemprop="name" content="Total Balance for <?php echo $_SESSION['current_month_name']; ?>">
                            <h2 class="fw-bolder mx-2" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                            <span class="bx bx-wallet icons d-flex flex-row flex-wrap justify-content-between align-items-center mx-auto">
                                <p class="fs-45 mb-2 ff fs-35 fw-bolder" itemprop="price" content="<?php echo $totalBalance; ?>">
                                    <span itemprop="priceCurrency" content="PHP">
                                        &#8369; <?php echo !empty($totalBalance) ? $totalBalance : "0.00"; ?>
                                    </span>
                                </p>
                            </span>
                            </h2>
                            <p class="fs-5 mb-3 mt-2 fw-bolder">Balance</p>
                          <div class="d-flex justify-content-end">
                            <button type="button" onclick="window.location.href='tenant_invoice.php'" class="view-button">View</button>
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

