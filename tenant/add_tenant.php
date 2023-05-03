<?php
  require_once '../tools/functions.php';
  require_once '../classes/tenants.class.php';

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
  

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $tenant_obj = new Tenant();
      //sanitize user inputs
      $tenant_obj->first_name = htmlentities($_POST['first_name']);
      $tenant_obj->middle_name = htmlentities($_POST['middle_name']);
      $tenant_obj->last_name = htmlentities($_POST['last_name']);
      $tenant_obj->email = htmlentities($_POST['email']);
      $tenant_obj->contact_no = htmlentities($_POST['contact_no']);
      $tenant_obj->relationship_status = $_POST['relationship_status'];
      $tenant_obj->type_of_household = $_POST['type_of_household'];
      $tenant_obj->previous_address = htmlentities($_POST['previous_address']);
      $tenant_obj->region = $_POST['region'];
      $tenant_obj->provinces = $_POST['provinces'];
      $tenant_obj->city = $_POST['city'];
      $tenant_obj->sex = $_POST['sex'];
      $tenant_obj->date_of_birth = htmlentities($_POST['date_of_birth']);
      if (isset($_POST['has_pet'])) {
        $has_pet = $_POST['has_pet'];
        if ($has_pet === 'No') {
          // If the user selects "No" for owning a pet, set the values of number_of_pets and type_of_pet to "0" and "None" respectively
          $tenant_obj->has_pet = 'No';
          $tenant_obj->number_of_pets = 0;
          $tenant_obj->type_of_pet = 'None';
        } else {
          $tenant_obj->has_pet = 'Yes';
          $tenant_obj->number_of_pets = htmlentities($_POST['number_of_pets']);
          $tenant_obj->type_of_pet = htmlentities($_POST['type_of_pet']);
        }
      }
      if (isset($_POST['is_smoking'])) {
        $tenant_obj->is_smoking = $_POST['is_smoking'];
      }
      if (isset($_POST['has_vehicle'])) {
        $tenant_obj->has_vehicle = is_array($_POST['has_vehicle']) ? $_POST['has_vehicle'] : array($_POST['has_vehicle']);
        // Convert has_vehicle array to JSON
        // For has_vehicle, encode the array as JSON
        $tenant_obj->has_vehicle = json_encode($_POST['has_vehicle']);
      }
      $tenant_obj->vehicle_specification = htmlentities($_POST['vehicle_specification']);
      $tenant_obj->spouse_first_name = htmlentities($_POST['spouse_first_name']);
      $tenant_obj->spouse_last_name = htmlentities($_POST['spouse_last_name']);
      $tenant_obj->spouse_email = htmlentities($_POST['spouse_email']);
      $tenant_obj->spouse_num = htmlentities($_POST['spouse_num']);

      if (isset($_POST['occupants']) && isset($_POST['occupants_relations'])) {
        // Extract the occupants and occupants_relations arrays from $_POST
        $tenant_obj->occupants = json_encode($_POST['occupants']);
        $tenant_obj->occupants_relations = json_encode($_POST['occupants_relations']);
    
        // Iterate over the occupants array and print each occupant's name
        foreach ($_POST['occupants'] as $occupant) {
            echo $occupant . '<br>';
        }
    
        // Iterate over the occupants_relations array and print each occupant's relation
        foreach ($_POST['occupants_relations'] as $relation) {
            echo $relation . '<br>';
        }
      }
    
      $tenant_obj->profile_img = 'default.png';
      $tenant_obj->emergency_contact_person = htmlentities($_POST['emergency_contact_person']);
      $tenant_obj->emergency_contact_number = htmlentities($_POST['emergency_contact_number']);

      if (validate_tenants($_POST)) {
        if ($tenant_obj->tenants_add()) {
          //redirect user to landing page after saving
          $_SESSION['added_tenants'] = true;
          header('location: tenants.php?add_success=1');
          exit; // always exit after redirecting
        }
      }
  }
        require_once '../tools/variables.php';
      $page_title = 'RMS | Add Tenant';
      $tenant = 'active';
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
              <h3 class="font-weight-bolder">ADD TENANT</h3> 
            </div>
            <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="tenants.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
          </div>
          <form action="add_tenant.php" id="addTenantForm" method="post" class="needs-validation" novalidate>
            <div class="card">
              <div class="card-body">
                <h3 class="table-title fw-bolder pb-4">Tenant Details</h3>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="first_name">First Name</label>
                        <input class="form-control form-control-sm " placeholder="First name" type="text" id="first_name" name="first_name" pattern="[A-Za-z\s-]*" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })" required>
                        <div class="invalid-feedback">
                          Please provide a valid first name (letters, spaces, and dashes only).
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="middle_name">Middle Name</label>
                        <input class="form-control form-control-sm" type="text" id="middle_name" pattern="[A-Za-z\s-]*" placeholder="Middle name" name="middle_name" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                        <div class="invalid-feedback">Please provide a valid middle name (letters, spaces, and dashes only).</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <label for="last_name">Last Name</label>
                          <input class="form-control form-control-sm" placeholder="Last name" type="text" id="last_name" name="last_name" pattern="[A-Za-z\s-]*" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })" required>
                          <div class="invalid-feedback">Please provide a valid last name (letters, spaces, and dashes only).</div>
                        </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="date_of_birth">Date of Birth</label>
                        <input class="form-control form-control-sm" type="date" id="date_of_birth" name="date_of_birth" required>
                        <div class="invalid-feedback">Please provide a valid date of birth (age must be 18 or above).</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="email">Email</label>
                        <input class="form-control form-control-sm" placeholder="Email" type="email" id="email" name="email" required>
                        <div class="invalid-feedback">Please provide a valid email address.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <label for="contact_no">Contact No.</label><br>
                          <div class="px-3 row g-3">
                            <input class="form-control form-control-sm" type="tel" id="contact_no" name="contact_no" required>
                            <div class="invalid-feedback">Please provide a valid contact number.</div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="previous_address">Previous Address</label>
                        <input class="form-control form-control-sm" placeholder="House No., Building No. Street No."  type="text" id="previous_address" name="previous_address" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })" required>
                        <div class="invalid-feedback">Please provide a valid previous address.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="d-flex">
                      <div class="col-md-4">
                        <label for="region">Region</label>
                        <select type="text" class="form-control selectpicker" data-live-search="true" data-size="9" data-dropup-auto="false" name="region" id="region" required> 
                          <option value="None">--Select--</option>
                          <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_region();
                                foreach($ref as $row){
                            ?>
                                    <option value="<?=$row['regCode']?>"><?=$row['regDesc']?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <div class="invalid-feedback">Must select a Region</div>
                      </div>
                      <div class="col-md-4 pl-0">
                        <label for="provinces">Provinces</label>
                        <select type="text" id="provinces" class="form-control selectpicker" data-live-search="true" data-size="9" data-dropup-auto="false" name="provinces" required>
                        <option value="None">--Select--</option>
                        <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_province($regCode);
                                foreach($ref as $row){
                            ?>
                                    <option value="<?=$row['provCode']?>"><?=$row['provDesc']?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <div class="invalid-feedback">Must select Province</div>
                      </div>
                      <div class="col-md-4 pl-0">
                        <label for="city">City</label>
                        <select type="text" class="form-control selectpicker" data-live-search="true" data-size="9" id="city" data-dropup-auto="false" name="city" required>
                        <option value="None">--Select--</option>
                        <?php
                            require_once '../classes/reference.class.php';
                            $ref_obj = new Reference();
                            $ref = $ref_obj->get_City($provCode);
                            foreach($ref as $row){
                        ?>
                                <option value="<?=$row['citymunCode']?>"><?=$row['citymunDesc']?></option>
                        <?php
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback">Must select Province</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                          <label for="sex" class="form-label">Sex</label>
                          <select class="form-control" id="sex" placeholder="" name="sex" required>
                              <option value="None">--Select--</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="col d-flex">
                    <div class="col-5 pr-3 fs2">
                      <label for="has_pet">Do Tenant own a pet?</label><br>
                      <input type="radio" id="has_pet_yes" name="has_pet" value="Yes">
                      <label for="has_pet_yes">Yes</label>
                      <input type="radio" id="has_pet_no" name="has_pet" value="No">
                      <label for="has_pet_no">No</label>
                    </div>
                    <div class="col-4 pl-1 fs1 fs2">
                      <label for="type_of_pet">Pet Type:</label>
                      <input class="form-control form-control-sm fs1" type="text" id="type_of_pet" name="type_of_pet" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                    </div>
                    <div class="col-3 px-1 fs1 fs2">
                      <label for="number_of_pets">No. of Pets</label>
                      <input class="form-control form-control-sm fs1" type="number" id="number_of_pets" name="number_of_pets" min="0">
                    </div>
                    <div class="invalid-feedback" id="pets_feedback">Please provide the number and type of pets.</div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="relationship_status">Civil Status</label>
                        <select class="form-control form-control-sm" id="relationship_status" name="relationship_status" required>
                          <option name="relationship_status" value="None">--Select--</option>
                          <option name="relationship_status" value="Single">Single</option>
                          <option name="relationship_status" value="Divorced">Divorced</option>
                          <option name="relationship_status" value="Married">Married</option>
                          <option name="relationship_status" value="Widowed">Widowed</option>
                        </select>
                        <div class="invalid-feedback">Please select a valid relationship status.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="is_smoking">Do Tenant Smoke?</label><br>
                        <input type="radio" id="is_smoking_yes" name="is_smoking" value="Yes">
                        <label for="is_smoking_yes">Yes</label>
                        <input type="radio" id="is_smoking_no" name="is_smoking" value="No">
                        <label for="is_smoking_no">No</label>
                        <div class="invalid-feedback" id="smoking_feedback">Please select if the tenant smokes.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="type_of_household">Type of Household</label>
                        <select class="form-control form-control-sm" id="type_of_household" name="type_of_household" required>
                        <option name="type_of_household" value="None">--Select--</option>
                          <option name="type_of_household" value="One Person">One Person</option>
                          <option name="type_of_household" value="Couple">Couple</option>
                          <option name="type_of_household" value="Single Parent">Single Parent</option>
                          <option name="type_of_household" value="Family">Family</option>
                          <option name="type_of_household" value="Extended Family">Extended Family</option>
                        </select>
                        <div class="invalid-feedback">Please select a valid household type.</div>
                      </div>
              
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="has_vehicle">Please check if tenant own any of the vehicles:</label><br>
                        <div class="row">
                          <div class='col-sm-12 text-dark'>
                            <input type="checkbox" name="has_vehicle[]" id="car" value="Car">
                            <label for="car">Car</label><br>
                            <input type="checkbox" name="has_vehicle[]" id="motorcycle" value="Motorcycle">
                            <label for="motorcycle">Motorcycle</label><br>
                            <input type="checkbox" name="has_vehicle[]" id="others" value="Others">
                            <label for="others">Other</label><br>
                            <div class="d-flex col-sm-12">
                              <label for="vehicle_specification" hidden>If other, please specify:</label><br>
                              <input class="form-control form-control-sm" type="text" name="vehicle_specification" id="vehicle_specification" style="display:none;" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })"><br>
                            </div>
                          </div>
                          <div class="invalid-feedback" id="vehicles_feedback">Please specify the vehicle type.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12" id="spouse_fields" style="display: none;">
                    <div class="row g-3">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col d-flex">
                            <h3 class="table-title pt-2">Spouse Details</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="spouse_first_name">First Name</label>
                            <input class="form-control form-control-sm" type="text" id="spouse_first_name" name="spouse_first_name" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                            <div class="invalid-feedback">Please provide a valid first name (letters, spaces, and dashes only).</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="spouse_first_name">Last Name</label>
                            <input class="form-control form-control-sm" type="text" id="spouse_last_name" name="spouse_last_name" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                            <div class="invalid-feedback">Please provide a valid last name (letters, spaces, and dashes only).</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="spouse_email">Email</label>
                            <input class="form-control form-control-sm" type="email" id="spouse_email" name="spouse_email">
                            <div class="invalid-feedback" id="spouse_email">Please provide a valid email address.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col"> 
                            <label for="spouse_num">Contact No.</label>
                            <div class="px-3 row g-3">
                              <input class="form-control form-control-sm" type="text" id="spouse_num" name="spouse_num">
                              <div class="invalid-feedback">Please provide a valid contact number.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12" id="other_occupants_fields" style="display: none;">
                    <hr>
                    <div class="form-group-row">
                      <div class="col d-flex">
                        <h3 class="table-title">Other Occupants</h3>
                        <button id="add_occupant" class="btn btn-success btn-rounded btn-icon ml-auto"  type="button"><i class="fas fa-plus"></i></button>
                      </div>
                    </div>
                    <div class="occupant-container">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="form-group-row">
                            <div class="col">
                              <label for="occupants">Full Name/s</label>
                              <input class="form-control form-control-sm" id="occupants" name="occupants[]" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                              <div class="invalid-feedback">Please provide a valid name (letters, spaces, and dashes only).</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group-row">
                            <div class="col">
                              <label for="occupants_relations">Relationship to Tenant</label><span class="req"> *</span>
                              <input class="form-control form-control-sm" type="text" id="occupants_relations" name="occupants_relations[]" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                              <div class="invalid-feedback">Please remove any special characters, numbers, or symbols.</div>
                            </div>
                          </div>
                        </div>  
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <hr>
                    <div class="form-group-row">
                      <div class="col">
                        <h3 class="table-title">Emergency Contact Person Details</h3>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 d-flex">
                    <div class="form-group-row w-50">
                      <div class="col">
                        <label for="emergency_contact_person">Full Name</label>
                        <input class="form-control form-control-sm" type="text" id="emergency_contact_person" name="emergency_contact_person" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })" required>
                        <div class="invalid-feedback">Please provide a valid name (letters, spaces, and dashes only).</div>
                      </div>
                    </div>
                    <div class="form-group-row w-50">
                      <div class="col">
                        <label for="emergency_contact_number">Contact No.</label>
                        <div class="px-3 row g-3">
                          <input class="form-control form-control-sm" type="text" id="emergency_contact_number" name="emergency_contact_number" required>
                          <div class="invalid-feedback">Please provide a valid contact number.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="pt-3">
                    <input type="submit" class="btn btn-success btn-sm" value="Save Tenant" name="Save" id="save">
                  </div>
                </div>
              </div>
            </div>
          </form>




<script>
  document.addEventListener('DOMContentLoaded', function() {
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const currentDate = new Date();
    const maxDate = new Date(currentDate.getFullYear() - 18, currentDate.getMonth(), currentDate.getDate());
    
    dateOfBirthInput.max = maxDate.toISOString().substr(0, 10);
  });
</script>

<script>
    // Add an event listener to the "has_pet" radio buttons
    const hasPetRadioButtons = document.getElementsByName("has_pet");
    hasPetRadioButtons.forEach((radioButton) => {
      radioButton.addEventListener("click", function() {
        if (this.value === "No") {
          document.getElementById("number_of_pets").value = "0";
          document.getElementById("type_of_pet").value = "None";
          document.getElementById("number_of_pets").disabled = true;
          document.getElementById("type_of_pet").disabled = true;
        } else {
          document.getElementById("number_of_pets").value = '';
          document.getElementById("type_of_pet").value = '';
          document.getElementById("number_of_pets").disabled = false;
          document.getElementById("type_of_pet").disabled = false;
        }
      });
    });

    // Script to show/hide "other_vehicle_type" input field
    var vehicleTypeCheckboxes = document.querySelectorAll('input[name="has_vehicle[]"]');
    var otherVehicleTypeInput = document.querySelector('input[name="vehicle_specification"]');
    var otherVehicleTypeLabel = document.querySelector('label[for="vehicle_specification"]');

    vehicleTypeCheckboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        if (checkbox.value === 'Others' && checkbox.checked) {
          otherVehicleTypeInput.style.display = 'block';
          otherVehicleTypeLabel.hidden = false;
        } else {
          otherVehicleTypeInput.style.display = 'none';
          otherVehicleTypeLabel.hidden = true;
        }
      });
    });

    $(document).ready(function() {
      $('#other_occupants_fields').hide();
      $('#spouse_fields').hide();

      function updateDisplay() {
        var typeOfHousehold = $('#type_of_household').val();
        var relationshipStatus = $('#relationship_status').val();

        if (relationshipStatus === 'Married' || relationshipStatus === 'Married' && typeOfHousehold === 'Couple' ) {
          $('#spouse_fields').show();
          $('#other_occupants_fields').hide();

          if (typeOfHousehold === 'Single Parent' || typeOfHousehold === 'Family' || typeOfHousehold === 'Extended Family' || !typeOfHousehold) {
            $('#other_occupants_fields').show();
          } else {
            $('#other_occupants_fields').hide();
          }
        } else {
          $('#spouse_fields').hide();

          if (typeOfHousehold === 'Single Parent' || typeOfHousehold === 'Family' || typeOfHousehold === 'Extended Family') {
            $('#other_occupants_fields').show();
          } else {
            $('#other_occupants_fields').hide();
          }
        }
      }

      $('#type_of_household').on('change', updateDisplay);
      $('#relationship_status').on('change', updateDisplay);
    });
    
    $('#add_occupant').on('click', function() {
        var newOccupant = `
          <div class="row">
            <div class="col-md-6">
              <div class="form-group-row">
                <div class="col mt-2">
                  <input class="form-control form-control-sm" id="occupants" name="occupants[]" pattern="[A-Za-z\s-]*" onkeyup="this.value = this.value.replace(/\\b\\w/g, function(l){ return l.toUpperCase(); })">
                  <div class="invalid-feedback">Please enter a valid full name.</div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-row">
                <div class="col mt-2">
                  <input class="form-control form-control-sm" id="occupants_relations" type="text" name="occupants_relations[]" pattern="[A-Za-z\s-]*" onkeyup="this.value = this.value.replace(/\\b\\w/g, function(l){ return l.toUpperCase(); })">
                  <div class="invalid-feedback">Please remove any special characters, numbers, or symbols.</div>
                </div>
              </div>
            </div>  
          </div>
        `;
        $('.occupant-container').append(newOccupant);

        // Add event listeners for the newly added occupant fields
        const occupantsInputs = document.querySelectorAll('input[name="occupants[]"]');
        const occupantsRelationsInputs = document.querySelectorAll('input[name="occupants_relations[]"]');

        function validateRel2Tenant(inputValue) {
          if (inputValue) {
            return inputValue.trim() !== '';
          }
          return true;
        }

        occupantsInputs.forEach(input => {
          input.addEventListener('input', () => {
            updateValidInputClass(input, validateFName(input.value));
          });
        });

        occupantsRelationsInputs.forEach(input => {
          input.addEventListener('input', () => {
            updateValidInputClass(input, validateRel2Tenant(input.value));
          });
        });
      });
</script>

<script>
  $(document).ready(function() {
      $('#region').on('change', function(){
        var formData = {
          filter: $("#region").val(),
          action: 'provinces',
        };
        $.ajax({
          type: "POST",
          url: '../includes/address.php',
          data: formData,
          success: function(result)
          {
            console.log(formData);
            console.log(result);
            $('#provinces').html(result);
            $('#provinces').selectpicker('refresh'); // Add this line
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
          }  
        });
      });

      $('#provinces').on('change', function(){
        var formData = {
          filter: $("#provinces").val(),
          action: 'city',
        };
        $.ajax({
          type: "POST",
          url: '../includes/address.php',
          data: formData,
          success: function(result)
          {
            console.log(formData);
            console.log(result);
            $('#city').html(result);
            $('#city').selectpicker('refresh'); // Add this line
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
          }  
        });
      });
    });
</script>

<script>
    var contactInput = document.querySelector("#contact_no");
    var spouseNumInput = document.querySelector("#spouse_num");
    var emergencyContactInput = document.querySelector("#emergency_contact_number");

    const contactIti = getItiInstance(contactInput);
    const spouseIti = getItiInstance(spouseNumInput);
    const emergencyContactIti = getItiInstance(emergencyContactInput);

    // Set the formatted number as the input value
    contactInput.value = contactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    spouseNumInput.value = spouseIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    emergencyContactInput.value = emergencyContactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);

    function getItiInstance(inputElement) {
      return window.intlTelInput(inputElement, {
        separateDialCode: true,
        initialCountry: "ph",
        geoIpLookup: function (success, failure) {
          $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
            var countryCode = (resp && resp.country) ? resp.country : "ph";
            success(countryCode);
          });
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.21/js/utils.js",
      });
    }
    
</script>

<script>
  const form = document.getElementById('addTenantForm');
  const firstNameInput = document.getElementById('first_name');
  const middleNameInput = document.getElementById('middle_name');
  const lastNameInput = document.getElementById('last_name');
  const dateOfBirthInput = document.getElementById('date_of_birth');
  const previousAddressInput = document.getElementById('previous_address');
  const emailInput = document.getElementById('email');

  const regionSelect = document.getElementById('region');
  const provinceSelect = document.getElementById('provinces');
  const citySelect = document.getElementById('city');
  const sexSelect = document.getElementById('sex');
  const civilSelect = document.getElementById('relationship_status');
  const householdSelect = document.getElementById('type_of_household');
  const emergencyFNameInput = document.getElementById('emergency_contact_person');
  const otherFNameInput = document.getElementById('occupants');
  const occuRelInput = document.getElementById('occupants_relations');

  const spouseFNameInput = document.getElementById('spouse_first_name');
  const spouseLNameInput = document.getElementById('spouse_last_name');
  const spouseEmailInput = document.getElementById('spouse_email');

  const carCheckbox = document.getElementById('car');
  const motorcycleCheckbox = document.getElementById('motorcycle');
  const otherCheckbox = document.getElementById('others');
  const vehicleSpecificationInput = document.getElementById('vehicle_specification');

  const smokingYesRadio = document.getElementById('is_smoking_yes');
  const smokingNoRadio = document.getElementById('is_smoking_no');

  const petYesRadio = document.getElementById('has_pet_yes');
  const petNoRadio = document.getElementById('has_pet_no');
  const numberOfPetsInput = document.getElementById('number_of_pets');
  const typeOfPetInput = document.getElementById('type_of_pet');

</script>

<script>

  function validateName(name) {
    const namePattern = /^[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ-]+$/;
    return namePattern.test(name);
  }
  function validateFName(fname) {
    const namePattern = /^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/;
    return namePattern.test(fname);
  }
  function validateAddress(inputValue) {
    // Check if the input contains only letters and digits using a regular expression
    return /^[0-9]*\s*[a-zA-Z0-9\s,.'()\[\]`{|}~-]+$/.test(inputValue);
  }
  function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9.!#$%&’()*+/=?^_`{|}~\[\]-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return emailPattern.test(email);
  }
  function validateSelect(value) {
    return value !== ""; // Check if a value has been selected
  }

  function validateDateOfBirth(dateOfBirth) {
    const currentDate = new Date();
    const dob = new Date(dateOfBirth);
    const ageDifference = currentDate - dob;
    const ageDate = new Date(ageDifference);
    const age = Math.abs(ageDate.getUTCFullYear() - 1970);
    return age >= 18;
  }

  function validatePhone(itiInstance) {
    return itiInstance.isValidNumber();
  }

  function updateInvalidEmailFeedback(inputElement) {
    const feedbackElement = inputElement.parentNode.querySelector('.invalid-feedback');

    if (!validateEmail(inputElement.value)) {
      feedbackElement.innerHTML = "Please provide a valid email address.";
    } else {
      feedbackElement.innerHTML = "";
    }
  }

  function updateValidInputClass(input, isValid) {
    if (isValid) {
      input.classList.add('is-valid');
      input.classList.remove('is-invalid');
    } else {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
    }
  }

  function validateVehicles() {
    if (otherCheckbox.checked) {
      return vehicleSpecificationInput.value.trim() !== '';
    }
    return true;
  }

  function validateRel2Tenant() {
    if (otherCheckbox.checked) {
      return vehicleSpecificationInput.value.trim() !== '';
    }
    return true;
  }

  function validateSmoking() {
    return smokingYesRadio.checked || smokingNoRadio.checked;
  }

  function validatePets() {
    if (petYesRadio.checked) {
      return numberOfPetsInput.value > 0 && typeOfPetInput.value.trim() !== '';
    } else if (petNoRadio.checked) {
      return numberOfPetsInput.disabled && typeOfPetInput.disabled;
    }
    return false;
  }

</script>

<script>

  firstNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  middleNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  lastNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  spouseFNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  spouseLNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  otherFNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateFName(this.value));
  });
  occuRelInput.addEventListener('input', function () {
    updateValidInputClass(this, validateFName(this.value));
  });
  emergencyFNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateFName(this.value));
  });

  dateOfBirthInput.addEventListener('input', function () {
    updateValidInputClass(this, validateDateOfBirth(this.value));
  });

  contactInput.addEventListener('input', function () {
    updateValidInputClass(this, validatePhone(contactIti));
    contactInput.value = contactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
  });

  spouseNumInput.addEventListener('input', function () {
    updateValidInputClass(this, validatePhone(spouseIti));
    spouseNumInput.value = spouseIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
  });

  emergencyContactInput.addEventListener('input', function () {
    updateValidInputClass(this, validatePhone(emergencyContactIti));
    emergencyContactInput.value = emergencyContactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
  });

  previousAddressInput.addEventListener('input', function () {
    updateValidInputClass(this, validateAddress(this.value))
  });

  emailInput.addEventListener('input', function() {
    updateValidInputClass(this, validateEmail(this.value));
    updateInvalidEmailFeedback(emailInput);
  });

  spouseEmailInput.addEventListener('input', function() {
    updateValidInputClass(this, validateEmail(this.value));
    updateInvalidEmailFeedback(spouseEmailInput);
  });

  regionSelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  provinceSelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  citySelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  sexSelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });
  civilSelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  householdSelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  smokingYesRadio.addEventListener('input', () => {
    updateValidInputClass(smokingYesRadio, validateSmoking());
  });

  smokingNoRadio.addEventListener('input', () => {
    updateValidInputClass(smokingNoRadio, validateSmoking());
  });

  petYesRadio.addEventListener('change', () => {
    updateValidInputClass(numberOfPetsInput, validatePets());
    updateValidInputClass(typeOfPetInput, validatePets());
  });

  petNoRadio.addEventListener('change', () => {
    updateValidInputClass(numberOfPetsInput, validatePets());
    updateValidInputClass(typeOfPetInput, validatePets());
  });

  numberOfPetsInput.addEventListener('input', () => {
    updateValidInputClass(numberOfPetsInput, validatePets());
    updateValidInputClass(typeOfPetInput, validatePets());
  });

  typeOfPetInput.addEventListener('input', () => {
    updateValidInputClass(typeOfPetInput, validatePets());
  });

  carCheckbox.addEventListener('input', () => {
    updateValidInputClass(carCheckbox, validateVehicles());
  });

  motorcycleCheckbox.addEventListener('input', () => {
    updateValidInputClass(motorcycleCheckbox, validateVehicles());
  });

  otherCheckbox.addEventListener('input', () => {
    updateValidInputClass(otherCheckbox, validateVehicles());
  });

  vehicleSpecificationInput.addEventListener('input', () => {
    updateValidInputClass(vehicleSpecificationInput, validateVehicles());
  });

</script>

<script>

  function validateForm() {
    let isValid = true;

    if (!validateName(firstNameInput.value)) {
      firstNameInput.classList.add('is-invalid');
      isValid = false;
    } else {
      firstNameInput.classList.remove('is-invalid');
    }

    if (!validateName(lastNameInput.value)) {
      lastNameInput.classList.add('is-invalid');
      isValid = false;
    } else {
      lastNameInput.classList.remove('is-invalid');
    }

    if (!validateDateOfBirth(dateOfBirthInput.value)) {
      dateOfBirthInput.classList.add('is-invalid');
      isValid = false;
    } else {
      dateOfBirthInput.classList.remove('is-invalid');
    }

    if (!validatePhone(contactIti)) {
      contactInput.classList.add('is-invalid');
      isValid = false;
    } else {
      contactInput.classList.remove('is-invalid');
    }

    if (!validatePhone(emergencyContactIti)) {
      emergencyContactInput.classList.add('is-invalid');
      isValid = false;
    } else {
      emergencyContactInput.classList.remove('is-invalid');
    }
    if (!validateAddress(previousAddressInput.value)) {
      previousAddressInput.classList.add('is-invalid');
      isValid = false;
    } else {
      previousAddressInput.classList.remove('is-invalid');
    }
    if (!validateEmail(emailInput.value)) {
      emailInput.classList.add('is-invalid');
      updateInvalidEmailFeedback(emailInput);
      isValid = false;
    } else {
      emailInput.classList.remove('is-invalid');
    }

    if (!validateSelect(regionSelect.value)) {
      regionSelect.classList.add('is-invalid');
      isValid = false;
    } else {
      regionSelect.classList.remove('is-invalid');
    }

    if (!validateSelect(provinceSelect.value)) {
      provinceSelect.classList.add('is-invalid');
      isValid = false;
    } else {
      provinceSelect.classList.remove('is-invalid');
    }

    if (!validateSelect(citySelect.value)) {
      citySelect.classList.add('is-invalid');
      isValid = false;
    } else {
      citySelect.classList.remove('is-invalid');
    }

    if (!validateSelect(sexSelect.value)) {
      sexSelect.classList.add('is-invalid');
      isValid = false;
    } else {
      sexSelect.classList.remove('is-invalid');
    }
    if (!validateSelect(civilSelect.value)) {
      civilSelect.classList.add('is-invalid');
      isValid = false;
    } else {
      civilSelect.classList.remove('is-invalid');
    }

    if (!validateSelect(householdSelect.value)) {
      householdSelect.classList.add('is-invalid');
      isValid = false;
    } else {
      householdSelect.classList.remove('is-invalid');
    }
    if (!validateFName(emergencyFNameInput.value)) {
      emergencyFNameInput.classList.add('is-invalid');
      isValid = false;
    } else {
      emergencyFNameInput.classList.remove('is-invalid');
    }


    return isValid;
  }

</script>

<script>

  document.getElementById('save').addEventListener('click', function (event) {
    event.preventDefault();

    if (validateForm()) {
      Swal.fire({
        title: 'Are you sure you want to save the record?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Save'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // submit the form if the user confirms
        }
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please fix the errors in the form!'
      });
    }
  });

</script>
