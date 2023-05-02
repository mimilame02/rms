<?php
  require_once '../tools/functions.php';
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
  if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
      header('location: ../login/login.php');
  }

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $property_units_obj = new Property_Units();

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
    
    $property_units_obj->one_month_deposit = $_POST['one_month_deposit'];
    $property_units_obj->one_month_advance = $_POST['one_month_advance'];

    if(validate_add_property_unit($_POST)){
      if ($property_units_obj->property_unit_add()) {
          $_SESSION['added_property_units'] = true;
          header('Location: property_units.php?add_success=1');
          exit; // always exit after redirecting
      }
    }
  }



  $page_title = 'RMS | Add Property Units';
  $p_units = 'active';
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
            <form action="add_property_units.php" method="post" class="needs-validation" id="add-property-units-form" onsubmit="return validateForm(event);" novalidate>
              <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-row">
                        <div class="col">
                            <label for="main_property">Main Building</label>
                            <select title="--Select--" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false" data-size="5" data-liveSearchStyle="startsWith" name="main_property" id="main_property" required>
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
                            <div class="invalid-feedback">Must select the Main Building</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-row">
                        <div class="col">
                            <label for="unit_type">Type of Unit</label>
                            <select title="--Select--" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false" data-size="5" data-liveSearchStyle="startsWith" name="unit_type" id="unit_type" required>
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
                            <div class="invalid-feedback">Must select a Unit Type</div>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6">
                  <div class="form-group-row">
                    <div class="col">
                      <label for="monthly_rent">Monthly Rent Amount</label>
                      <input class="form-control form-control-sm" placeholder="" type="number" id="monthly_rent" name="monthly_rent" required value="<?php echo isset($_POST['monthly_rent']) ? $_POST['monthly_rent'] : 2500; ?>">
                    </div>
                  </div>
                  <div class="form-group-row pt-2">
                    <div class="col">
                      <label for="one_month_deposit">One Month Deposit Amount</label>
                      <input type="number" class="form-control form-control-sm" id="one_month_deposit" placeholder="enter amount" name="one_month_deposit" value="<?php echo isset($_POST['one_month_deposit']) ? $_POST['one_month_deposit'] : 2500; ?>">
                    </div>
                  </div>
                  <div class="form-group-row pt-2">
                    <div class="col">
                      <label for="one_month_advance">One Month Advance Amount</label>
                      <input type="number" class="form-control form-control-sm" id="one_month_advance" placeholder="enter amount" name="one_month_advance" value="<?php echo isset($_POST['one_month_advance']) ? $_POST['one_month_advance'] : 2500; ?>">
                    </div>
                  </div>
                  <div class="pt-4">
                    <div class="card-d row g-3">
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col-md-12">
                            <label for="unit_no">Unit No.</label>
                            <input class="form-control form-control-sm" placeholder="Unit No." type="text" id="unit_no" name="unit_no" required>
                            <div class="invalid-feedback">Only greater than or equal to 1</div>
                          </div>
                        </div>
                        <div class="form-group-row pt-2">
                          <div class="col-md-12">
                            <label for="floor_level">Floor Level</label>
                            <input class="form-control form-control-sm" placeholder="Floor Level" type="number" id="floor_level" name="floor_level" min="1" max="100" value="<?php echo  isset($_POST['floor_level']) ? $_POST['floor_level'] : 1; ?>">
                            <div class="invalid-feedback">Only greater than or equal to 1</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="row g-3">
                          <div class="form-group-row pt-2">
                            <div class="col-md-12">
                              <label for="status">Status</label>
                              <select class="form-control form-control-sm" id="status" name="status">
                                <option value="Vacant" selected>Vacant</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Unavailable">Unavailable</option>
                              </select>
                              <div class="invalid-feedback">Must select a Status</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex pt-3">
                    <div id="room_fields">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="p-2">
                            <label for="num_rooms" class="">Number of Rooms</label>
                            <input type="number" class="form-control form-control-sm" id="num_rooms" name="num_rooms" min="1" max="100" value="<?php echo  isset($_POST['num_rooms']) ? $_POST['num_rooms'] : 1; ?>">
                            <div class="invalid-feedback">Only greater than or equal to 1</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="bathroom_fields">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="p-2">
                            <label for="num_bathrooms" class="">Number of Bathrooms</label>
                            <input type="number" class="form-control form-control-sm" id="num_bathrooms" name="num_bathrooms" min="1" max="100" value="<?php echo  isset($_POST['num_bathrooms']) ? $_POST['num_bathrooms'] : 1; ?>">
                            <div class="invalid-feedback">Only greater than or equal to 1</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="unit_condition">Unit Condition</label>
                        <select class="form-control selectpicker" data-live-search="true" data-dropup-auto="false" data-size="5" data-liveSearchStyle="startsWith" id="unit_condition" name="unit_condition" onchange="updateUnitTypePicture()">
                          <option value="none">--Select--</option>
                          <?php
                          require_once '../classes/reference.class.php';
                          $ref_obj = new Reference();
                          $ref = $ref_obj->get_unit_con();
                          foreach($ref as $row){
                          ?>
                          <option value="<?=$row['id']?>" data-img="<?=$row['unit_type_picture']?>"><?=$row['condition_name']?></option>
                          <?php
                          // Set the value of $row['unit_type_picture']
                          $row['unit_type_picture'] = json_decode($row['unit_type_picture'])[0];
                          }
                          ?>
                        </select>
                        <div class="invalid-feedback">Must select a Unit Condition</div>
                        <div class="image-container mb-3" style="margin-inline: 15%;width: 70%;">
                        <img id="unit_type_picture" src="../img/unit_conditions/<?php echo isset($ref[0]['unit_type_picture']) && $ref[0]['unit_type_picture'] !== '' ? $ref[0]['unit_type_picture'] : 'default-image.png'; ?>" alt="Unit Condition Picture" height="150px" width="100%">
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
                              <a href="#" data-toggle="modal" data-target="#featureAndAmenitiesModal">Add Feature</a><div><br></div>
                              <div class="invalid-feedback">
                                Please select at least one feature.
                              </div>
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
          <form id="featureAndAmenities" action=" amenities.php" method="POST">
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    // Set deposit and advance fields to match monthly rent
    $("#monthly_rent").on("change", function() {
      var monthlyRent = parseFloat($(this).val());
      $("#one_month_deposit, #one_month_advance").val(monthlyRent);
    }).trigger("change"); // Set the initial values of the deposit and advance fields based on the default value of the monthly_rent field
  });

  function updateUnitTypePicture() {
    const unitConditionSelect = document.getElementById('unit_condition');
    const unitTypePictureImg = document.getElementById('unit_type_picture');

    const selectedOption = unitConditionSelect.options[unitConditionSelect.selectedIndex];
    const unitTypePicture = selectedOption.dataset.img;

    unitTypePictureImg.src = unitTypePicture ? "../img/unit_conditions/" + unitTypePicture : "../img/unit_conditions/default-image.png";
  }

  document.getElementById('unit_condition').addEventListener('change', updateUnitTypePicture);


</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const mainPropertySelect = document.getElementById("main_property");
    updateDisabledFields(mainPropertySelect);

    mainPropertySelect.addEventListener("change", function () {
      updateDisabledFields(this);
    });
  });

  function updateDisabledFields(mainPropertySelect) {
    const isMainPropertySelected = mainPropertySelect.value !== "none";
    const fieldsToDisable = [
      "unit_type",
      "monthly_rent",
      "one_month_deposit",
      "one_month_advance",
      "unit_no",
      "floor_level",
      "status",
      "num_rooms",
      "num_bathrooms",
      "unit_condition"
    ];

    fieldsToDisable.forEach((fieldId) => {
      const field = document.getElementById(fieldId);
      field.disabled = !isMainPropertySelected;
    });
  }
</script>

<script>
  document.getElementById("add-property-units-form").addEventListener("submit", function (event) {
  if (!validateForm()) {
    event.preventDefault();
  }
});
const form = document.getElementById("add-property-units-form");
const elements = form.elements;

function validateForm() {
  let isValid = true;

  // Validation rules for each input field go here

  if (!validateSelect(elements["main_property"].value)) {
    updateValidInputClass(elements["main_property"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["main_property"], true);
  }
  if (!validateSelect(elements["unit_condition"].value)) {
    updateValidInputClass(elements["unit_condition"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["unit_condition"], true);
  }

  if (!validateSelect(elements["unit_type"].value)) {
    updateValidInputClass(elements["unit_type"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["unit_type"], true);
  }

  // Continue with other validation rules

  if (!validateInput(elements["num_rooms"].value, "positiveInteger")) {
    updateValidInputClass(elements["num_rooms"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["num_rooms"], true);
  }

  const featureInputs = form.querySelectorAll('input[name="features[]"]');
let atLeastOneFeatureSelected = false;

featureInputs.forEach((input) => {
  input.addEventListener("change", function () {
    atLeastOneFeatureSelected = Array.from(featureInputs).some((input) => input.checked);
    updateValidInputClass(input, atLeastOneFeatureSelected);
  });
});

form.addEventListener('submit', function(event) {
  if (!atLeastOneFeatureSelected) {
    console.log('Feature selection validation failed');
    event.preventDefault();
  }
});


  if (!validateRent(elements["monthly_rent"].value, "positiveInteger")) {
    updateValidInputClass(elements["monthly_rent"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["monthly_rent"], true);
  }

  if (!validateRent(elements["one_month_deposit"].value, "positiveInteger")) {
    updateValidInputClass(elements["one_month_deposit"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["one_month_deposit"], true);
  }

  if (!validateRent(elements
  ["one_month_advance"].value, "positiveInteger")) {
    updateValidInputClass(elements["one_month_advance"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["one_month_advance"], true);
  }

  form.classList.add('was-validated');


  return isValid;
}

function validateInput(value, type) {
  if (type === "positiveInteger") {
    return !isNaN(value) && parseInt(value) > 0;
  }
}
function validateRent(value, type) {
  if (type === "positiveInteger") {
    return !isNaN(value) && parseInt(value) > 2500;
  }
}

function updateValidInputClass(input, isValid) {
  if (isValid) {
    input.classList.add("is-valid");
    input.classList.remove("is-invalid");
  } else {
    input.classList.remove("is-valid");
    input.classList.add("is-invalid");
  }
}

function validateSelect(value) {
  return value !== "";
}

// Add event listeners for input fields
elements["main_property"].addEventListener("change", function () {
  updateValidInputClass(this, validateSelect(this.value));
});

elements["unit_condition"].addEventListener("change", function () {
  updateValidInputClass(this, validateSelect(this.value));
});

elements["unit_type"].addEventListener("change", function () {
  updateValidInputClass(this, validateSelect(this.value));
});

elements["num_rooms"].addEventListener("input", function () {
  updateValidInputClass(this, validateInput(this.value, "positiveInteger"));
});

const featureInputs = form.querySelectorAll('input[name="features[]"]');
featureInputs.forEach((input) => {
  input.addEventListener("change", function () {
    const atLeastOneFeatureSelected = Array.from(featureInputs).some((input) => input.checked);
    updateValidInputClass(input, atLeastOneFeatureSelected);
  });
});

</script>
<script>
document.getElementById('add-property-units-form').addEventListener('submit', function (event) {
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
        this.submit(); // submit the form if the user confirms
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