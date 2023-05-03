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
  <li><a class="link" href="#" data-toggle="modal" data-target="#dateTimeModal"><i class="fas fa-clock mr-3"></i>Date and Time</a></li>
  <li><a class="link" href="../admin/profile.php"><i class="fas fa-user mr-3"></i>Profile</a></li>
  <li><a class="link" href="#"><i class="fas fa-paint-brush mr-3"></i>Theme</a></li>
</ul>
  
   
<h5>Billing</h5>
<ul class="no-bullets settings-content">
  <li><a class="link" href="#" ><i class="fas fa-dollar-sign mr-3"></i>Adjust Amount</a></li>
  <li><a class="link" href="#" data-toggle="modal" data-target="#addBillTypeModal"><i class="fas fa-file-invoice-dollar mr-3"></i>Add Bill Type</a></li>
</ul>
    
   
      <h5>Property</h5>
      <ul class="no-bullets settings-content">
      <li><a class="link" href="#" data-toggle="modal" data-target="#featureAndAmenitiesModal"><i class="fas fa-clock mr-3"></i>Add Features and Amenities</a></li>
      </ul>
  
  <h5>Property Units</h5>
      <ul class="no-bullets settings-content">
        <li><a class="link" href="#" data-toggle="modal" data-target="#addUnitConditionModal"><i class="fas fa-building mr-3"></i>Add Property Unit Condition</a></li>
        <li><a class="link" href="#"  data-toggle="modal" data-target="#addUnitTypeModal"><i class="fas fa-home mr-3"></i>Add Property Unit Type</a></li>
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
        <div class="form-group">
          <input type="hidden" name="action" value="add">
          <label for="feature_name">Feature Name:</label>
          <input type="text" class="form-control" name="feature_name" id="feature_name" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('featureAndAmenities').submit();">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- this modal is for bill type -->
<div class="modal fade" id="addBillTypeModal" tabindex="-1" role="dialog" aria-labelledby="addBillTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBillTypeModalLabel">Add Bill Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="billTypeForm" action="process_add_bill_type.php" method="POST">
          <div class="form-group">
            <label for="bill_type_name">Bill Type Name:</label>
            <input type="text" class="form-control" id="bill_type_name" name="bill_type_name" required>
          </div>
          <div class="form-group">
            <label for="bill_type_amount">Amount:</label>
            <input type="number" class="form-control" id="bill_type_amount" name="bill_type_amount" step="0.01" min="0" required>
            </div>
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="document.getElementById('billTypeForm').submit();">Save changes</button>
      </div>
      </div>
    </div>
  </div>

  <!-- this modal is for unit type -->
  <div class="modal fade" id="addUnitTypeModal" tabindex="-1" role="dialog" aria-labelledby="addUnitTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUnitTypeModalLabel">Add Unit Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addUnitTypeForm" method="POST" action="process_add_unit_type.php">
          <div class="form-group">
            <label for="unit_type_name">Unit Type Name:</label>
            <input type="text" class="form-control" id="unit_type_name" name="unit_type_name" required>
          </div>
          </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" onclick="document.getElementById('addUnitTypeForm').submit();">Save changes</button>
      </div>
          </div>
      </div>
    </div>
  </div>
</div>


<!-- this modal is for unit condition -->
<div class="modal fade" id="addUnitConditionModal" tabindex="-1" role="dialog" aria-labelledby="addUnitConditionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUnitConditionModalLabel">Add Unit Condition</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addUnitConditionForm" action="process_add_unit_condition.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="condition_name">Condition Name:</label>
            <input type="text" class="form-control" id="condition_name" name="condition_name" required>
          </div>
          <div class="form-group">
            <label for="unit_type_picture">Unit Type Picture:</label>
            <input type="file" class="form-control-file" id="unit_type_picture" name="unit_type_picture" required>
          </div>
          <input type="hidden" id="unit_type_id" name="unit_type_id" value="1"><!-- replace 1 with dynamic unit type id -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="addUnitConditionForm" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</body>