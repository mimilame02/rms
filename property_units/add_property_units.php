<?php
  require_once '../tools/variables.php';
  require_once '../includes/dbconfig.php';
  require_once '../classes/property_units.class.php';

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

  $property_units_obj = new Property_Units();

  if(isset($_POST['save'])){
    $property_units_obj->property_id = $_POST['main_property'];
    $property_units_obj->unit_type_id = $_POST['unit_type'];
    $property_units_obj->unit_no = $_POST['unit_no'];
    $property_units_obj->num_rooms = $_POST['num_rooms'];
    $property_units_obj->num_bathrooms = $_POST['num_bathrooms'];
    $property_units_obj->unit_condition_id = $_POST['unit_condition'];
    $property_units_obj->floor_level = $_POST['floor_level'];
    $property_units_obj->status = $_POST['status'];


    if ($property_units_obj != null) {
      // Convert features[] to JSON
      if (isset($_POST['pu_features'])) {
        $property_units_obj->pu_features = is_array($_POST['pu_features']) ? $_POST['pu_features'] : array($_POST['pu_features']);
        // For features[], encode the array as JSON
        $property_units_obj->pu_features = json_encode($_POST['pu_features']);
      }
    }
    
    

    $property_units_obj->monthly_rent = isset($_POST['monthly_rent']) ? $_POST['monthly_rent'] : 2500;
    
    if (isset($_POST['monthly_rent'])) {
      $property_units_obj->one_month_deposit = $_POST['one_month_deposit'];
      $property_units_obj->one_month_advance = $_POST['one_month_advance'];
    }

  
    if ($property_units_obj->property_unit_add()) {
        header('Location: property_units.php');
        exit; // always exit after redirecting
    } else {
        // handle product add error
        $msg = "Error adding property unit";
    }
  }

  

  $page_title = 'RMS | Add Property Units';
  $p_units = 'active';
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
        <div class="col-md-12 pb-2">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">ADD PROPERTY UNITS</h3>
          </div>
          <div class="add-page-container">
            <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="property_units.php" class='bx bx-caret-left'>Back</a>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <div class="form-group-row">
                <div class="col">
                  <h4 class="table-title pt-3">PROPERTY UNIT DETAILS</h4>
                  <p class="table-title pb-3">Please fill all the required fields before saving the data</p>
                </div>
            </div>
            <form action="add_property_units.php" method="post" class="form-sample" id="add-property-units-form">
              <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-row">
                        <div class="col">
                            <label for="main_property">Main Property</label><span class="req"> *</span>
                            <select class="form-control form-control-sm" placeholder="" id="main_property" name="main_property" required>
                                <option value="none">--Select--</option>
                                <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_main_pro($_POST['filter']);
                                foreach($ref as $row){
                                ?>
                                <option value="<?=$row['id']?>"><?=$row['property_name']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-row">
                        <div class="col">
                            <label for="unit_type">Type of Unit</label><span class="req"> *</span>
                            <select class="form-control form-control-sm" placeholder="" id="unit_type" name="unit_type" required>
                                <option value="none">--Select--</option>
                                <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_unit_type($_POST['filter']);
                                foreach($ref as $row){
                                ?>
                                <option value="<?=$row['id']?>"><?=$row['type_name']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6">
                  <div class="form-group-row">
                    <div class="col">
                      <label for="monthly_rent">Monthly Rent Amount</label><span class="req"> *</span>
                      <input class="form-control form-control-sm" placeholder="" type="number" id="monthly_rent" name="monthly_rent" required value="<?php echo isset($_POST['monthly_rent']) ? $_POST['monthly_rent'] : 2500; ?>">
                    </div>
                  </div>
                  <div class="form-group-row pt-2">
                    <div class="col">
                      <label for="one_month_deposit">One Month Deposit Amount</label><span class="req"> *</span>
                      <input type="number" class="form-control form-control-sm" id="one_month_deposit" placeholder="enter amount" name="one_month_deposit" value="<?php echo isset($_POST['one_month_deposit']) ? $_POST['one_month_deposit'] : 2500; ?>">
                    </div>
                  </div>
                  <div class="form-group-row pt-2">
                    <div class="col">
                      <label for="one_month_advance">One Month Advance Amount</label><span class="req"> *</span>
                      <input type="number" class="form-control form-control-sm" id="one_month_advance" placeholder="enter amount" name="one_month_advance" value="<?php echo isset($_POST['one_month_advance']) ? $_POST['one_month_advance'] : 2500; ?>">
                    </div>
                  </div>
                  <div class="pt-4">
                    <div class="card-d row g-3">
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col-md-12">
                            <label for="unit_no">Unit No.</label><span class="req"> *</span>
                            <input class="form-control form-control-sm" placeholder="Unit No." type="text" id="unit_no" name="unit_no" required>
                          </div>
                        </div>
                        <div class="form-group-row pt-2">
                          <div class="col-md-12">
                            <label for="floor_level">Floor Level</label>
                            <input class="form-control form-control-sm" placeholder="Floor Level" type="number" id="floor_level" name="floor_level" min="1" max="100" value="<?php echo  isset($_POST['floor_level']) ? $_POST['floor_level'] : 1; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="row g-3">
                          <div class="form-group-row pt-2">
                            <div class="col-md-12">
                              <label for="status">Status</label><span class="req"> *</span>
                              <select class="form-control form-control-sm" id="status" name="status">
                                <option value="Vacant" selected>Vacant</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Unavailable">Unavailable</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex pt-3 pl-5">
                    <div id="room_fields">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="p-2">
                            <label for="num_rooms" class="fs-7">Number of Rooms</label>
                            <input type="number" class="form-control form-control-sm" id="num_rooms" name="num_rooms" min="1" max="100" value="<?php echo  isset($_POST['num_rooms']) ? $_POST['num_rooms'] : 1; ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="bathroom_fields">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="p-2">
                            <label for="num_bathrooms" class="fs-7">Number of Bathrooms</label>
                            <input type="number" class="form-control form-control-sm" id="num_bathrooms" name="num_bathrooms" min="1" max="100" value="<?php echo  isset($_POST['num_bathrooms']) ? $_POST['num_bathrooms'] : 1; ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="unit_condition">Unit Condition</label><span class="req"> *</span>
                        <select class="form-control form-control-sm" placeholder="" id="unit_condition" name="unit_condition" required>
                          <option value="none">--Select--</option>
                          <?php
                          require_once '../classes/reference.class.php';
                          $ref_obj = new Reference();
                          $ref = $ref_obj->get_unit_con();
                          foreach($ref as $row){
                          ?>
                          <option value="<?=$row['id']?>"><?=$row['condition_name']?></option>
                          <?php
                          }
                          ?>
                        </select>
                        <div class="image-container mx-auto pb-3">
                          <img id="unit_type_picture" src="<?php 
                          require_once '../classes/reference.class.php';
                          $ref_obj = new Reference();
                          $ref = $ref_obj->get_unit_type_picture($unit_condition_id);
                          if (isset($unit_condition_id)) {
                            echo '../img/' .$unit_condition_id;
                          } ?>" alt="Unit Condition Picture" height="250px" width="250px">
                        </div>
                      </div>
                    </div>
                    <div class="w-100 pt-5">
                      <div class="form-group-row">
                        <div class="col d-flex">
                          <div class="col-lg-12">
                            <p>Check box if features are allowed:</p>
                            <div class="p-3">
                              <?php
                                // Connect to the database and retrieve the list of features
                                $result = mysqli_query($conn, "SELECT id, feature_name FROM features");
                                $selected_features = isset($property_units_obj->pu_features) ? json_decode($property_units_obj->pu_features) : array();
                                while ($row = mysqli_fetch_assoc($result)) {
                                  $checked = in_array($row['id'], $selected_features) ? "checked" : "";
                                  echo "<div class='col-sm-12 text-dark mb-1'>";
                                  echo "<input type='checkbox' class='checkmark req' id='feature" . $row['id'] . "' name='pu_features[]' value='" . $row['id'] . "' $checked>" . "<label class='pl-2 features'  for='feature" . $row['id'] . "'>" . $row['feature_name'] . "</label><br>";
                                  echo "</div>";
                                }
                              ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>               
              </div>
              <div class="pt-4">
                <input type="submit" class="btn btn-success btn-sm" value="Save Unit" name="save" id="save">
              </div>
            </form> 
          </div>
        </div>
    </div>
  </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
  // Update uploaded image when unit condition is selected
  $('#unit_condition').on('change', function() {
    var unitConditionId = $(this).val();
    var imageUrl = '<?php echo $property_units_obj->get_unit_type_picture(":unit_condition_id"); ?>'.replace(':unit_condition_id', unitConditionId);
    $('#uploaded-image').attr('src', imageUrl);
    $('.image-container').show();
  });

  // Set deposit and advance fields to match monthly rent
  $("#monthly_rent").on("change", function() {
    var monthlyRent = parseFloat($(this).val());
    $("#one_month_deposit, #one_month_advance").val(monthlyRent);
  }).trigger("change"); // Set the initial values of the deposit and advance fields based on the default value of the monthly_rent field
});



var selectElem = document.getElementById("status");
selectElem.addEventListener("change", function() {
  var selectedOption = selectElem.options[selectElem.selectedIndex];
  var className = "";
  if (selectedOption.value === "Vacant") {
    className = "text-success";
  } else if (selectedOption.value === "Occupied") {
    className = "text-danger";
  } else if (selectedOption.value === "Unavailable") {
    className = "text-secondary";
  }
  selectElem.className = "form-control form-control-sm " + className;
});

// Find the select element
var selectElem = document.getElementById('unit_condition');

// Find the image element
var imgElem = document.getElementById('unit_type_picture');

// Check if the img tag has a valid src attribute and carries a unit condition ID
var unitConditionId = get_unit_con();
if (imgElem.getAttribute('src') !== null && imgElem.getAttribute('src') !== '' && unitConditionId !== null) {
  // Fetch the image and show the container when the image is loaded
  imgElem.onload = function() {
    // Add the col-md-6 class to the select element
    selectElem.classList.add('col-md-6');

    // Remove the d-none class from the image container
    var imageContainer = document.querySelector('.image-container');
    imageContainer.classList.remove('d-none');
  }

  // Call the get_unit_type_picture() function to get the URL of the image
  var imageUrl = '<?php echo $ref_obj->get_unit_type_picture("' + unitConditionId + '"); ?>';

  // Set the img src attribute to the image URL
  imgElem.setAttribute('src', '../img/' + imageUrl);
}



</script>
