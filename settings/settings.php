<?php
  require_once '../tools/functions.php';
  require_once '../classes/database.php';
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



    require_once '../tools/variables.php';
    $page_title = 'RMS | Settings';
    $settings = 'active';

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
        <h3 class="font-weight-bolder">SETTINGS</h3> 
      </div>
      <div class="add-page-container">
      </div>
    </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
              <div class="settings">
                  <h5>General</h5>
<ul class="no-bullets settings-content">
  <li><i class="fas fa-clock"></i><a href="#" data-toggle="modal" data-target="#dateTimeModal">Date and Time</a></li>
  <li><i class="fas fa-language"></i><a href="#">Language</a></li>
  <li><i class="fas fa-user"></i><a href="#">Profile</a></li>
  <li><i class="fas fa-paint-brush"></i><a href="#">Theme</a></li>
</ul>
  
   
<h5>Billing</h5>
<ul class="no-bullets settings-content">
  <li><i class="fas fa-dollar-sign"></i><a href="#">Adjust Amount</a></li>
  <li><i class="fas fa-file-invoice-dollar"></i><a href="#">Add Bill Type</a></li>
</ul>
    
   
      <h5>Property</h5>
      <ul class="no-bullets settings-content">
      <li><i class="fas fa-clock"></i><a href="#" data-toggle="modal" data-target="#featureAndAmenitiesModal">Add Features and Amenities</a></li>
      </ul>
  
  <h5>Property Units</h5>
      <ul class="no-bullets settings-content">
        <li><i class="fas fa-building"></i><a href="#">Add Property Unit Condition</a></li>
        <li><i class="fas fa-home"></i><a href="#">Add Property Unit Type</a></li>
      </ul>
      <?php if ($_SESSION['user_type'] == 'admin') { ?>
                <h5>Manage User</h5>
                <ul class="no-bullets settings-content">
                  <li><i class="fas fa-user-plus"></i><a href="#">User Permission</a></li>
                </ul>
              <?php } ?>
    
  </ul>

              </div>
            </div>
  </div>
  </div>
  </div>
          <!-- this modal is for date -->
  <div class="modal fade" id="dateTimeModal" tabindex="-1" role="dialog" aria-labelledby="dateTimeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dateTimeModalLabel">Change Date and Time</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="dateTimeForm" action="change_date_time.php" method="post">
          <div class="form-group">
            <label for="custom_date">Custom Date:</label>
            <input type="date" class="form-control" id="custom_date" name="custom_date" required>
          </div>
          <div class="form-group">
            <label for="custom_time">Custom Time:</label>
            <input type="time" class="form-control" id="custom_time" name="custom_time" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('dateTimeForm').submit();">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- this modal is for features and amenities -->

<div class="modal fade" id="featureAndAmenitiesModal" tabindex="-1" role="dialog" aria-labelledby="featureAndAmenitiesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="featureAndAmenitiesModalLabel">Add Features And Amenities</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="featureAndAmenities" action="amenities.php" method="post">
          <input type="hidden" name="action" value="add">
          <label for="feature_name">Feature Name:</label>
          <input type="text" name="feature_name" id="feature_name" required>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('featureAndAmenities').submit();">Save changes</button>
      </div>
    </div>
  </div>
</div>



</body>