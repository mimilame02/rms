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
    $property_units_obj->property_unit_name = $_POST['property_unit_name'];
    $property_units_obj->property_id = $_POST['main_property'];
    $property_units_obj->unit_type_id = $_POST['unit_type'];
    $property_units_obj->monthly_rent = $_POST['monthly_rent'];
    $property_units_obj->unit_condition_id = $_POST['unit_condition'];
    $property_units_obj->num_bedrooms = $_POST['num_bedrooms'];
    $property_units_obj->num_bathrooms = $_POST['num_bathrooms'];
    $property_units_obj->max_capacity = $_POST['max_capacity'];
    $property_units_obj->available_for = $_POST['available_for'];
    $property_units_obj->status = 'Vacant';

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
            <div class="col-md-12 grid-margin">
              <div class="row">
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
            <form action="add_property_units.php" method="post" class="form-sample">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-group-row">
                    <div class="col">
                      <label for="property_unit_name">Property Name</label>
                      <input  class="form-control form-control-sm " placeholder="Property Unit Name" type="text" id="property_unit_name" name="property_unit_name" required>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group-row">
                    <div class="col">
                    <label for="monthly_rent">Monthly Rent Amount</label>
                      <input class="form-control form-control-sm" placeholder=""  type="number" id="monthly_rent" name="monthly_rent" required>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-row">
                    <div class="col">
                      <label for="main_property">Select Main Property</label>
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
                      <label for="unit_condition">Select Unit Condition</label>
                      <select class="form-control form-control-sm" placeholder="" id="unit_condition" name="unit_condition" required>
                      <option value="none">--Select--</option>
                      <?php
                                  require_once '../classes/reference.class.php';
                                  $ref_obj = new Reference();
                                  $ref = $ref_obj->get_unit_con($_POST['filter']);
                                  foreach($ref as $row){
                              ?>
                                      <option value="<?=$row['id']?>"><?=$row['condition_name']?></option>
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
                      <label for="unit_type">Select Type of Unit</label>
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
                <div class="card-d stretch-card" id="card_d-none" style="display:none;">
                  <div id="bedroom_fields" style="display:none;">
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <div class="col">
                          <label for="num_bedrooms">Number of Bedrooms</label>
                          <input type="number" class="form-control form-control-sm" id="num_bedrooms" name="num_bedrooms">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="bathroom_fields" style="display:none;">
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <div class="col">
                          <label for="num_bathrooms">Number of Bathrooms</label>
                          <input type="number" class="form-control form-control-sm" id="num_bathrooms" name="num_bathrooms">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="bedspace_fields" style="display:none;">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="max_capacity">Maximum Capacity</label>
                            <input type="number" class="form-control form-control-sm mb-3" id="max_capacity" name="max_capacity">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="available_for">Available For</label>
                            <select class="form-control form-control-sm" id="available_for" name="available_for">
                              <option value="none">--Select--</option>
                              <option value="all_girls">All Girls</option>
                              <option value="all_boys">All Boys</option>
                              <option value="mixed">Mixed</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="ps-6">
                <input type="submit" class="btn btn-success btn-sm" value="Save Unit" name="save" id="save">
              </div>
            </form> 
          </div>
        </div>
    </div>
  </div>
</div>

<script>
  var unitTypeDropdown = document.getElementById("unit_type");
  
  var bedroomFields = document.getElementById("bedroom_fields");
  var bathroomFields = document.getElementById("bathroom_fields");

  unitTypeDropdown.addEventListener("change", function() {
    if (this.value == "1") { // Change "1" to the ID of the option that should trigger the fields to appear
      bedroomFields.style.display = "block";
      bathroomFields.style.display = "block";
    } else {
      bedroomFields.style.display = "none";
      bathroomFields.style.display = "none";
    }
  });

  var card_dNone = document.getElementById("card_d-none");

  unitTypeDropdown.addEventListener("change", function() {
    if (this.value == "1" || this.value == "2") {
      card_dNone.style.display = "block";
    } else {
      card_dNone.style.display = "none";
    }
  });



  var bedspaceFields = document.getElementById("bedspace_fields");

  unitTypeDropdown.addEventListener("change", function() {
    if (this.value == "2") { // Change "1" to the ID of the option that should trigger the fields to appear
      bedspaceFields.style.display = "block";
    } else {
      bedspaceFields.style.display = "none";
    }
  });
  

  var availableForDropdown = document.getElementById("available_for");
  availableForDropdown.addEventListener("change", function() {
    var unitTypeDropdown = document.getElementById("unit_type");
    if (unitTypeDropdown.value == "2") {
      bedspaceFields.style.display = "block";
    } else {
      bedspaceFields.style.display = "none";
    }
  });
  
</script>

