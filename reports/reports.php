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
    $page_title = 'RMS | Reports';
    $reports = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
?>
<body>
<div class="loading-screen">
  <img class="logo" src="../img/logo-edit.png" alt="logo">
  <?php echo $page_title; ?>
  <div class="loading-bar"></div>
</div>
<style>
  .table-container {
    position: relative;
    z-index: 1;
  }

  .tab-content {
    position: relative;
  }

  .tab-pane {
    display: none;
    position: absolute;
    top: 231px;
    width: 65%;
  }

  .tab-pane.active {
    display: block;
  }
</style>
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
        <h3 class="font-weight-bolder">REPORTS</h3> 
        <br>
      </div>

      <div class="add-tenant-container">
      </div>
    </div>
<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Paid Invoices</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Unpaid Invoices</a>
  </li>
</ul>
<div class="" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
    <!-- Paid Invoices Table -->
    <div class="table-container">
      <div class="table-responsive pt-3 fw-normal">
        <div class="card">
          <div class="card-body">
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Date of Payment</th>
                    <th>No. of Paid Invoices</th>
                    <th>Rent Due Date</th>
                    <th>Total Amount</th>
                    <?php if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){ ?>
                      <th>Action</th>
                    <?php } ?>
                </tr>
              </thead>
              <tbody>
              <?php
    // Fetch the invoice data
    $result = mysqli_query($conn, "SELECT YEAR(payment_date) as year, MONTH(payment_date) as month, COUNT(*) as num_invoices, SUM(amount_paid) as total_amount, invoice.rent_due_date
        FROM invoice 
        WHERE invoice.status = 'Paid'
        GROUP BY YEAR(payment_date), MONTH(payment_date)
        ORDER BY YEAR(payment_date) DESC, MONTH(payment_date) DESC");
    $i = 1;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $payment_date = strtotime($row['year'].'-'.$row['month'].'-01');
            $rent_due_date = strtotime($row['rent_due_date']);
            $date_diff = $rent_due_date - $payment_date;
            $advance_text = '';

            if ($date_diff > 0) {
                $days_diff = floor($date_diff / (60 * 60 * 24));
                if ($days_diff >= 30) {
                    $months_diff = floor($days_diff / 30);
                    $advance_text = "Paid in {$months_diff} month(s) advance";
                } else {
                    $advance_text = "Paid in {$days_diff} day(s) advance";
                }
            }

            echo '
            <tr>
                <td>'.$i.'</td>
                <td>'.date('F j, Y', $payment_date).'</td>
                <td>'.$row['num_invoices'].'</td>
                <td>'.(!empty($advance_text) ? $advance_text : date('F j, Y', $rent_due_date)).'</td>
                <td>'.$row['total_amount'].'</td>
                <td>
                    <button class="show3" onclick="redirectTo(\'monthly_invoice.php?year='.$row['year'].'&month='.$row['month'].'\')">Show All</button>
                </td>
            </tr>';
            $i++;
        }
    }
?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
    <!-- Unpaid Invoices Table -->
    <div class="table-container">
      <div class="table-responsive pt-3 fw-normal">
        <div class="card">
          <div class="card-body">
            <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                  <tr>
                    <th>#</th>
                    <th>Rent Due Date</th>
                    <th>No. of Unpaid Invoices</th>
                    <th>Total Due</th>
                    <?php if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){ ?>
                        <th>Action</th>
                      <?php } ?>
                  </tr>
              </thead>
              <tbody>
                <?php
                    // Fetch the invoice data
                    $result = mysqli_query($conn, "SELECT YEAR(rent_due_date) as year, MONTH(rent_due_date) as month, COUNT(*) as num_invoices, SUM(total_due) as total_amount 
                        FROM invoice 
                        WHERE invoice.status = 'Unpaid'
                        GROUP BY YEAR(rent_due_date), MONTH(rent_due_date)
                        ORDER BY YEAR(rent_due_date) DESC, MONTH(rent_due_date) DESC");
                    $i = 1;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
                            <tr>
                                <td>'.$i.'</td>
                                <td>'.date('F j, Y', strtotime($row['year'].'-'.$row['month'].'-01')).'</td>
                                <td>'.$row['num_invoices'].'</td>
                                <td>'.$row['total_amount'].'</td>
                                <td>
                                    <button class="show3" onclick="redirectTo(\'monthly_invoice.php?year='.$row['year'].'&month='.$row['month'].'\')">Show All</button>
                                </td>
                            </tr>';
                            $i++;
                        }
                    }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


<script>
function redirectTo(url) {
  window.location.href = url;
}
  
    $('#example').DataTable( {
  responsive: {
    breakpoints: [
      {name: 'bigdesktop', width: Infinity},
      {name: 'meddesktop', width: 1480},
      {name: 'smalldesktop', width: 1280},
      {name: 'medium', width: 1188},
      {name: 'tabletl', width: 1024},
      {name: 'btwtabllandp', width: 848},
      {name: 'tabletp', width: 768},
      {name: 'mobilel', width: 480},
      {name: 'mobilep', width: 320}
    ]
  }
} );
$('#example2').DataTable( {
  responsive: {
    breakpoints: [
      {name: 'bigdesktop', width: Infinity},
      {name: 'meddesktop', width: 1480},
      {name: 'smalldesktop', width: 1280},
      {name: 'medium', width: 1188},
      {name: 'tabletl', width: 1024},
      {name: 'btwtabllandp', width: 848},
      {name: 'tabletp', width: 768},
      {name: 'mobilel', width: 480},
      {name: 'mobilep', width: 320}
    ]
  }
} );
</script>