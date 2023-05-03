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
  $property_units_obj = new Property_Units;
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $property_units_obj->id = $_POST['property_unit-id'];
    $property_units_obj->property_id = $_POST['main_property'];
    $property_units_obj->unit_type_id = $_POST['unit_type'];
    $property_units_obj->unit_no = $_POST['unit_no'];
    $property_units_obj->num_rooms = $_POST['num_rooms'];
    $property_units_obj->num_bathrooms = $_POST['num_bathrooms'];
    $property_units_obj->unit_condition_id = $_POST['unit_condition'];
    $property_units_obj->floor_level = $_POST['floor_level'];
    $property_units_obj->status = $_POST['status'];
    $property_units_obj->monthly_rent = $_POST['monthly_rent'];
    $property_units_obj->one_month_deposit = $_POST['one_month_deposit'];
    $property_units_obj->one_month_advance = $_POST['one_month_advance'];


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
      if ($property_units_obj->property_unit_edit()) {
          $_SESSION['edited_property_units'] = true;
          header('Location: property_units.php?add_success=1');
          exit; // always exit after redirecting
      }
    }
  }else{
    if ($property_units_obj->property_unit_fetch($_GET['id'])){ 
        $data = $property_units_obj->property_unit_fetch($_GET['id']);
        $property_units_obj->id = $data['id'];
        $property_units_obj->property_id = $data['property_id'];
        $property_units_obj->unit_type_id = $data['unit_type_id'];
        $property_units_obj->unit_no = $data['unit_no'];
        $property_units_obj->num_rooms = $data['num_rooms'];
        $property_units_obj->num_bathrooms = $data['num_bathrooms'];
        $property_units_obj->unit_condition_id = $data['unit_condition_id'];
        $property_units_obj->floor_level = $data['floor_level'];
        $property_units_obj->status = $data['status'];
        $property_units_obj->pu_features = json_decode($data['pu_features'], true);
        $property_units_obj->monthly_rent = $data['monthly_rent'];
        $property_units_obj->one_month_deposit = $data['one_month_deposit'];
        $property_units_obj->one_month_advance = $data['one_month_advance'];

    }
  }

  
  $page_title = 'RMS | Edit Property Units';
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
            <h3 class="font-weight-bold">EDIT PROPERTY UNITS</h3>
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
            <form action="edit_property_units.php" method="post" class="needs-validation" id="add-property-units-form" onsubmit="return validateForm(event);" novalidate>
              <input type="text" hidden name="property_unit-id" value="<?php echo $property_units_obj->id ?>">

              <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group-row">
                        <div class="col">
                            <label for="main_property">Main Building</label>
                            <select class="form-control form-control-sm" placeholder="" id="main_property" name="main_property" required>
                                <option value="none">--Select--</option>
                                <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_main_pro($_POST['filter']);
                                foreach($ref as $row){
                                ?>
                                <option value="<?=$row['id']?>"<?=($property_units_obj->property_id == $row['id']) ? 'selected' : ''?>><?=$row['property_name']?></option>
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
                            <select class="form-control form-control-sm" placeholder="" id="unit_type" name="unit_type" required>
                                <option value="none">--Select--</option>
                                <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_unit_type($_POST['filter']);
                                foreach($ref as $row){
                                ?>
                               <option value="<?=$row['id']?>" <?=($property_units_obj->unit_type_id == $row['id']) ? 'selected' : ''?>><?=$row['type_name']?></option>
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
                      <input type="number" class="form-control form-control-sm" id="monthly_rent" name="monthly_rent" step="0.01" value="<?php if(isset($_POST['monthly_rent'])) { echo $_POST['monthly_rent']; } else { echo $property_units_obj->monthly_rent; } ?>" required>
                    </div>
                  </div>
                  <div class="form-group-row pt-2">
                    <div class="col">
                      <label for="one_month_deposit">One Month Deposit Amount</label>
                      <input type="number" class="form-control form-control-sm" id="one_month_deposit" name="one_month_deposit" step="0.01" value="<?php if(isset($_POST['one_month_deposit'])) { echo $_POST['one_month_deposit']; } else { echo $property_units_obj->one_month_deposit; } ?>" required>
                    </div>
                  </div>
                  <div class="form-group-row pt-2">
                    <div class="col">
                      <label for="one_month_advance">One Month Advance Amount</label>
                      <input type="number" class="form-control form-control-sm" id="one_month_advance" name="one_month_advance" step="0.01" value="<?php if(isset($_POST['one_month_advance'])) { echo $_POST['one_month_advance']; } else { echo $property_units_obj->one_month_advance; } ?>" required>
                    </div>
                  </div>
                  <div class="pt-4">
                    <div class="card-d row g-3">
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col-md-12">
                            <label for="unit_no">Unit No.</label>
                            <input class="form-control form-control-sm" placeholder="Unit No." type="text" id="unit_no" name="unit_no" required value="<?php echo $property_units_obj->unit_no; ?>">
                            <div class="invalid-feedback">Only greater than or equal to 1</div>
                          </div>
                        </div>
                        <div class="form-group-row pt-2">
                          <div class="col-md-12">
                            <label for="floor_level">Floor Level</label>
                            <input class="form-control form-control-sm" placeholder="Floor Level" type="number" id="floor_level" name="floor_level" min="1" max="100" value="<?php echo $property_units_obj->floor_level; ?>">
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
                              <option value="Vacant" <?=($property_units_obj->status == 'Vacant') ? 'selected' : ''?>>Vacant</option>
                              <option value="Occupied" <?=($property_units_obj->status == 'Occupied') ? 'selected' : ''?>>Occupied</option>
                              <option value="Unavailable" <?=($property_units_obj->status == 'Unavailable') ? 'selected' : ''?>>Unavailable</option>
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
                            <input type="number" class="form-control form-control-sm" id="num_rooms" name="num_rooms" min="1" max="100" value="<?php echo $property_units_obj->num_rooms; ?>">
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
                            <input type="number" class="form-control form-control-sm" id="num_bathrooms" name="num_bathrooms" min="1" max="100" value="<?php echo $property_units_obj->num_bathrooms; ?>">
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
                      <select class="form-control form-control-sm" placeholder="" id="unit_condition" name="unit_condition" onchange="updateUnitConditionPicture()">
                        <option value="none">--Select--</option>
                        <?php
                        require_once '../classes/reference.class.php';
                        $ref_obj = new Reference();
                        $ref = $ref_obj->get_unit_con();
                        foreach($ref as $row){
                        ?>
                       <option value="<?=$row['id']?>" data-img="<?=$row['unit_type_picture']?>" <?=($property_units_obj->unit_condition_id == $row['id']) ? 'selected' : ''?>><?=$row['condition_name']?></option>
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
                                $selected_features = isset($property_units_obj->pu_features) ? $property_units_obj->pu_features : array();
                                while ($row = mysqli_fetch_assoc($result)) {
                                  echo "<div class='col-sm-12 text-dark mb-1'>";
                                  echo "<input type='checkbox' class='checkmark req' id='feature" . $row['id'] . "' name='pu_features[]' value='" . $row['id'] . "'";
                                  if (isset($_POST['pu_features']) && in_array($row['id'], $_POST['pu_features'])) {
                                    echo ' checked';
                                  } elseif (is_array($selected_features) && in_array($row['id'], $selected_features)) {
                                    echo ' checked';
                                  }
                                  echo ">" . "<label class='pl-2 features'  for='feature" . $row['id'] . "'>" . $row['feature_name'] . "</label><br>";
                                  echo "</div>";
                                }
                              ?>
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
  // Set deposit and advance fields to match monthly rent
  $("#monthly_rent").on("change", function() {
    var monthlyRent = parseFloat($(this).val());

    if (!$('#one_month_deposit').val()) {
      $("#one_month_deposit").val(monthlyRent);
    }
    
    if (!$('#one_month_advance').val()) {
      $("#one_month_advance").val(monthlyRent);
    }
  }).trigger("change"); // Set the initial values of the deposit and advance fields based on the default value of the monthly_rent field
});

</script>
<script>
function updateUnitConditionPicture() {
  var unitConditionSelect = document.getElementById('unit_condition');
  var unitConditionImg = document.getElementById('unit_type_picture');
  var selectedOption = unitConditionSelect.options[unitConditionSelect.selectedIndex];
  var imgSrc = selectedOption.getAttribute('data-img');

  unitConditionImg.src = "../img/unit_conditions/" + (imgSrc ? imgSrc : 'default-image.png');
}

// Call the function on page load to set the initial image
document.addEventListener("DOMContentLoaded", updateUnitConditionPicture);
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


  if (!validateInput(elements["monthly_rent"].value, "positiveInteger")) {
    updateValidInputClass(elements["monthly_rent"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["monthly_rent"], true);
  }

  if (!validateInput(elements["one_month_deposit"].value, "positiveInteger")) {
    updateValidInputClass(elements["one_month_deposit"], false);
    isValid = false;
  } else {
    updateValidInputClass(elements["one_month_deposit"], true);
  }

  if (!validateInput(elements
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