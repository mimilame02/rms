<?php
  require_once '../includes/dbconfig.php';
  require_once '../tools/functions.php';
  require_once '../classes/buildings.class.php';

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



  $property_obj = new Properties;

  if(isset($_POST['property_save'])){

      //sanitize user inputs
      $property_obj->id = htmlentities($_POST['property-id']);
      $property_obj->property_name = htmlentities($_POST['property_name']);
      $property_obj->property_description = htmlentities($_POST['property_description']);
      $property_obj->num_of_floors = htmlentities($_POST['num_of_floors']);
      $property_obj->landlord_id = htmlentities($_POST['landlord']);
      $property_obj->region = htmlentities($_POST['region']);
      $property_obj->provinces = htmlentities($_POST['provinces']);
      $property_obj->city = htmlentities($_POST['city']);
      $property_obj->barangay = htmlentities($_POST['barangay']);
      $property_obj->street = htmlentities($_POST['street']);
      $property_obj->features_description = htmlentities($_POST['features_description']);

      // Convert features[] to JSON
      if (isset($_POST['features'])) {
        $property_obj->features = is_array($_POST['features']) ? $_POST['features'] : array($_POST['features']);
        // For features[], encode the array as JSON
        $property_obj->features = json_encode($_POST['features']);
      }

      if (isset($_FILES['image_path'])) {
          $image_count = count($_FILES['image_path']['name']);
      
          for ($i = 0; $i < $image_count; $i++) {
              $image = $_FILES['image_path']['name'][$i];
              $target = "../img/buildings/" . basename($image);
      
              if (move_uploaded_file($_FILES['image_path']['tmp_name'][$i], $target)) {
                  $property_obj->image_path[$i] = $_FILES['image_path']['name'][$i];
              } else {
                  // handle file upload error
                  $msg = "Error uploading file";
              }
          }
      }
     
      if (isset($_FILES['floor_plan'])) {
        // Loop through each uploaded floor plan
        foreach($_FILES['floor_plan']['name'] as $key=>$name) {
          // Check if file was uploaded successfully
          if ($_FILES['floor_plan']['error'][$key] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['floor_plan']['tmp_name'][$key])) {
            // Generate a unique file name to prevent conflicts
            $newFileName = uniqid('', true) . '_' . $name;
            $target = "../img/floor_plans/" . $newFileName;
      
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['floor_plan']['tmp_name'][$key], $target)) {
              // Add the file name to the floor_plans array
              $floor_plans[] = $newFileName;
            } else {
              // Handle file upload error
              $msg = "Error uploading file";
            }
          }
        }
      
      
        // Save the floor plans to the database
        if (!empty($floor_plans)) {
          $property_obj->floor_plan = json_encode($floor_plans);
        }
      }
 
      // Add property to database
      if(validate_add_properties($_POST)){
        if ($property_obj->properties_add()) {
          $_SESSION['edited_properties'] = true;
          header('Location: buildings.php?add_success=1');
          exit; // always exit after redirecting
        } else {
          // handle property add error
          $msg = "Error uploading file";
        }
      }
  } else {
    if ($property_obj->properties_fetch($_GET['id'])){
      $data = $property_obj->properties_fetch($_GET['id']);
      $property_obj->id = $data['id'];
      $property_obj->property_name = $data['property_name'];
      $property_obj->property_description = $data['property_description'];
      $property_obj->num_of_floors = $data['num_of_floors'];
      $property_obj->landlord_id = $data['landlord_id'];
      $property_obj->region = $data['region'];
      $property_obj->provinces = $data['provinces'];
      $property_obj->city = $data['city'];
      $property_obj->barangay = $data['barangay'];
      $property_obj->street = $data['street'];
      $property_obj->features_description = $data['features_description'];
      $property_obj->features = json_decode($data['features'], true);
      $property_obj->image_path = $data['image_path'];
      $property_obj->floor_plan = json_decode($data['floor_plan'], true);

    }
  }
  

    
    require_once '../tools/variables.php';
    $page_title = 'RMS | Edit Building';
    $properties = 'active';
    require_once '../includes/header.php';
?>
<head>
  <link rel="stylesheet" href="../css/form-wizard.css">
</head>
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
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bolder">EDIT BUILDING</h3> 
            </div>
            <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="buildings.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
            <div class="d-flex">
              <!-- Basic Details Step -->
              <span class="step rounded pt-3 pb-2 text-center">Basic Details</span>
              <!-- Location Step -->
              <span class="step rounded pt-3 pb-2 text-center">Location</span>
              <!-- Features Step -->
              <span class="step rounded pt-3 pb-2 text-center">Features</span>
              <!-- Images Step -->
              <span class="step rounded pt-3 pb-2 text-center">Images</span>
            </div>
            <form action="edit_building.php" id="regForm" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
              <input type="text" hidden name="property-id" value="<?php echo $property_obj->id; ?>">
              <div class="col-12">
                <div class="tab">
                  <!-- Basic Details Step -->
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Basic Details</h4>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <label for="property_name">Building Name</label>
                          <input class="form-control form-control-sm req" type="text" id="property_name" name="property_name" value="<?php if(isset($_POST['property_name'])) { echo $_POST['property_name']; } else { echo $property_obj->property_name; }?>" required oninput="updateValidation(this)">
                          <div class="invalid-feedback">Please provide a valid property name (letters, spaces, and dashes only).</div> 
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <div class="col-lg-12">
                            <label for="property_description">Description of the Property</label>
                            <textarea class="form-control form-control-lg" id="property_description" name="property_description" oninput="updateDescriptionWithLineBreaks()"><?php if(isset($_POST['property_description'])) { echo $_POST['property_description']; } else { echo $property_obj->property_description; }?></textarea>
                            <div class="invalid-feedback">Invalid Description (letters, spaces, and dashes only).</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <div class="col-md-6">
                            <label for="landlord">Select Landlord</label>
                            <select class="form-control form-control-sm form-select mb-3 req" id="landlord" name="landlord" required oninput="updateValidation(this)">
                              <option class="col-md-6" value="none" disabled <?php if (!isset($_POST['landlord']) && !isset($property_obj->landlord_id)) echo 'selected'; ?>>Select Landlord</option>
                              <?php
                                // Connect to the database and retrieve the list of landlords
                                $result = mysqli_query($conn, "SELECT id, last_name, first_name FROM landlord");
                                while ($row = mysqli_fetch_assoc($result)) {
                                  $selected = '';
                                  if (isset($_POST['landlord']) && $_POST['landlord'] == $row['id']) {
                                    $selected = 'selected';
                                  } elseif (isset($property_obj->landlord_id) && $property_obj->landlord_id == $row['id']) {
                                    $selected = 'selected';
                                  }
                                  echo "<option value='" . $row['id'] . "' " . $selected . ">" . $row['last_name'] . "," . $row['first_name'] . "</option>";
                                }
                              ?>
                            </select>
                            <div class="invalid-feedback">Must select a Landlord</div>
                          </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="">
                            <label for="num_of_floors">Number of Floors</label>
                            <input class="form-control form-control-sm req" type="number" id="num_of_floors" name="num_of_floors" min="1" max="100" required onchange="generateFloorPlan()" oninput="updateValidation(this)" <?php if(isset($_POST['num_of_floors'])){echo "value=".$_POST['num_of_floors'];} else { echo $property_obj->num_of_floors; }?> required oninput="updateValidation(this)">
                            <div class="invalid-feedback">Only greater than or equal to 0</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 grid-margin">
                <div class="tab">
                  <!-- Location Step -->
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Location Details</h4>
                    <div class="w-100">
                      <div class="mar d-flex">
                        <div class="col-sm-3">
                          <label for="region">Region</label>
                          <select type="text" class="form-control form-control-sm req" name="region" id="region" required oninput="updateValidation(this)"> 
                            <option value="none"<?php if(isset($_POST['region'])) { if ($_POST['region'] == 'None') echo ' selected="selected"'; } elseif ($property_obj->region == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                            <?php
                                  require_once '../classes/reference.class.php';
                                  $ref_obj = new Reference();
                                  $ref = $ref_obj->get_region();
                                  foreach($ref as $row){
                              ?>
                                      <option value="<?=$row['regCode']?>" <?php if(isset($_POST['region'])) { if ($_POST['region'] == $row['regCode']) echo ' selected="selected"'; } elseif ($property_obj->region == $row['regCode']) echo ' selected="selected"'; ?>><?=$row['regDesc']?></option>
                              <?php
                                  }
                              ?>
                          </select>
                          <div class="invalid-feedback">Must select a Region</div>
                        </div>
                        <div class="col-sm-3">
                          <label for="provinces">Provinces</label>
                          <select type="text" id="provinces" class="form-control form-control-sm" name="provinces" required oninput="updateValidation(this)">
                            <option value="None" <?php if(isset($_POST['provinces'])) { if ($_POST['provinces'] == 'None') echo ' selected="selected"'; } elseif ($property_obj->provinces == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                                <?php
                                    require_once '../classes/reference.class.php';
                                    $ref_obj = new Reference();
                                    $ref = $ref_obj->get_provinced();
                                    foreach($ref as $row){
                                ?>
                                        <option value="<?=$row['provCode']?>" <?php if(isset($_POST['provinces'])) { if ($_POST['provinces'] == $row['provCode']) echo ' selected="selected"'; } elseif ($property_obj->provinces == $row['provCode']) echo ' selected="selected"'; ?>><?=$row['provDesc']?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <div class="invalid-feedback">Must select a Province</div>
                        </div>
                        <div class="col-sm-3">
                          <label for="city">City</label>
                          <select id="city" class="form-control form-control-sm req" id="city" name="city" required oninput="updateValidation(this)">
                          <option value="None" <?php if(isset($_POST['city'])) { if ($_POST['city'] == 'None') echo ' selected="selected"'; } elseif ($property_obj->city == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                            <?php
                                  require_once '../classes/reference.class.php';
                                  $ref_obj = new Reference();
                                  $ref = $ref_obj->get_Citys();
                                  foreach($ref as $row){
                              ?>
                                  <option value="<?=$row['citymunCode']?>" <?php if(isset($_POST['city'])) { if ($_POST['city'] == $row['citymunCode']) echo ' selected="selected"'; } elseif ($property_obj->city == $row['citymunCode']) echo ' selected="selected"'; ?>><?=$row['citymunDesc']?></option>
                              <?php
                                  }
                                  ?>
                          </select>
                          <div class="invalid-feedback">Must select a City</div>
                        </div>
                      </div>
                      <div class="mar d-flex pt-3">
                        <div class="col-sm-5">
                          <label for="barangay">Barangay</label>
                          <select class="form-control form-control-sm req" name="barangay" id="barangay" required oninput="updateValidation(this)"> 
                            <option value="none"<?php if(isset($_POST['barangay'])) { if ($_POST['barangay'] == 'None') echo ' selected="selected"'; } elseif ($property_obj->barangay == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                            <?php
                                  require_once '../classes/reference.class.php';
                                  $ref_obj = new Reference();
                                  $ref = $ref_obj->get_brgay();
                                  foreach($ref as $row){
                              ?>
                                      <option value="<?=$row['brgyCode']?>" <?php if(isset($_POST['barangay'])) { if ($_POST['barangay'] == $row['brgyCode']) echo ' selected="selected"'; } elseif ($property_obj->barangay == $row['brgyCode']) echo ' selected="selected"'; ?>><?=$row['brgyDesc']?></option>
                              <?php
                                  }
                              ?>
                          </select>
                          <div class="invalid-feedback">Must select a Barangay</div>
                        </div>
                        <div class="col-sm-5">
                          <div class="form-group-row">
                            <label for="street">Street</label>
                            <input class="form-control form-control-sm req" type="text" id="street" name="street" value="<?php if(isset($_POST['street'])) { echo $_POST['street']; } else { echo $property_obj->street; }?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 grid-margin">
                <div class="tab">
                  <!-- Features Step -->
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Feature Details</h4>
                    <div class="w-100">
                      <div class="form-group">
                        <div class="col d-flex">
                          <div class="col-lg-12">
                              <label for="features_description">Description of the Features</label>
                              <textarea class="form-control form-control-lg" id="features_description" name="features_description" oninput="updateDescriptionWithLineBreaks()"><?php if(isset($_POST['features_description'])) { echo $_POST['features_description']; } else { echo $property_obj->features_description; }?></textarea>
                          </div>
                      </div>
                    </div>
                    <div class="w-100">
                      <div class="form-group-row">
                        <div class="col d-flex">
                          <div class="col-lg-12">
                            <p>Check box if features are allowed:</p>
                            <?php
                                // Connect to the database and retrieve the list of features
                                $result = mysqli_query($conn, "SELECT id, feature_name FROM features");
                                $selected_features = isset($property_obj->features) ? json_decode($property_obj->features) : array();
                                $invalid_class = isset($_POST['save']) && !validate_features($_POST) ? ' is-invalid' : '';
                                echo "<div class='row p-3'>";
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $checked = in_array($row['id'], $selected_features) ? "checked" : "";
                                    echo "
                                        <div class='col-sm-4 text-dark'>
                                            <input type='checkbox' class='checkmark req" . $invalid_class . "' id='feature" . $row['id'] . "' name='features[]' value='" . $row['id'] . "' $checked>" .
                                            "<label class='feature'  for='feature" . $row['id'] . "'>" . $row['feature_name'] . "</label><br>
                                        </div>
                                    ";
                                }
                                echo "</div>";
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
              <div class="col-12 grid-margin">
                <div class="tab">
                  <!-- Images Step -->
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Image Details</h4>
                    <div class="pt-4">
                      <label for="image_path">Upload a picture of the property:</label>
                      <div class="image-container row g-3 pb-3 h-25 custom-scrollbar" id="image-container">
                        <?php
                        if (isset($property_obj->image_path) && !empty($property_obj->image_path)) {
                          $imagePaths = explode(',', $property_obj->image_path);
                          foreach ($imagePaths as $imagePath) {
                        ?>
                        <div class="uploaded-image col-auto">
                          <img src="../img/<?php echo $imagePath; ?>">
                          <div class="remove-image" onclick="removeImage(this.parentNode)">X</div>
                        </div>
                        <?php
                          }
                        }
                        ?>
                      </div>
                      <input class="form-control form-control-lg" type="file" id="image_path" name="image_path" accept=".jpg,.jpeg,.png" value="<?php echo isset($property_obj->image_path) ? $property_obj->image_path : '' ?>">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group-row">
                      <div class="col mt-3">
                        <label for="div" class="form-label pt-3 pb-4 d-none">Please select image according to the number of floors you have input previously</label>
                        <div id="floor_plan"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                  <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Next</button>
                  <button type="submit" class="btn btn-success" id="saveBtn" name="property_save" style="display:none;">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
<!-- SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  

<script>
  document.getElementById("nextBtn").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      nextPrev(1);
    }
  });

  // Initialize the form wizard
  const currentStep = 0;
  showStep(currentStep);

  // Function to show the current step of the form wizard
  function showStep(stepIndex) {
    const steps = document.getElementsByClassName("tab");
    steps[stepIndex].style.display = "block";

    // Get all the span tags with class "step"
    const stepSpans = document.getElementsByClassName("step");
    
    // Loop through all the span tags and remove the "active" class from them
    for (const i = 0; i < stepSpans.length; i++) {
      stepSpans[i].classList.remove("active");
    }
      
    // Add the "active" class to the current step's span tag
    stepSpans[stepIndex].classList.add("active");

      if (stepIndex == 0) {
          document.getElementById("prevBtn").style.display = "none";
      } else {
          document.getElementById("prevBtn").style.display = "inline";
      }
      if (stepIndex == (steps.length - 1)) {
          document.getElementById("nextBtn").style.display = "none";
          document.getElementById("saveBtn").style.display = "inline";
      } else {
          document.getElementById("nextBtn").style.display = "inline";
          document.getElementById("saveBtn").style.display = "none";
      }

      if (stepIndex == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }

    }
  
    // Function to move to the next or previous step of the form wizard
    function nextPrev(step) {
      const steps = document.getElementsByClassName("tab");
      if (step > 0 && !validateStep(currentStep)) {
        return false;
      }

      // Check if it's the last step before submitting the form
      if (currentStep == (steps.length - 1) && step == 1) {
        saveData();
        return false;
      }
      
      steps[currentStep].style.display = "none";
      currentStep += step;
      showStep(currentStep);
    }

    // Add an event listener to the "Save" button
    document.getElementById("saveBtn").addEventListener("click", function(event) {
      event.preventDefault();
      saveData();
    });
  
  // Function to validate the current step of the form wizard
  function validateStep(stepIndex) {
  const isValid = true;
  const form = document.getElementById("regForm");
  const formData = new FormData(form);

  // Reset all custom validation messages
  form.querySelectorAll("input, select").forEach((input) => {
    input.setCustomValidity("");
  });

  switch (stepIndex) {
    case 0:
      const propertyName = formData.get("property_name");
      if (propertyName === "") {
        form.elements["property_name"].setCustomValidity("Please enter a property name.");
        isValid = false;
      }
      const landlord = formData.get("landlord");
      if (landlord === "none") {
        form.elements["landlord"].setCustomValidity("Please select a landlord.");
        isValid = false;
      }
      const num_floor = formData.get("num_of_floors");
      if (num_floor < 1) {
        form.elements["num_of_floors"].setCustomValidity(
          "Number of floors should be equal to or greater than 1."
        );
        isValid = false;
      }
      break;
    case 1:
      const region = form.elements["region"];
      if (region.value === "none") {
        region.setCustomValidity("Please select a region.");
        isValid = false;
      }
      const provinces = form.elements["provinces"];
      if (provinces.value === "none") {
        provinces.setCustomValidity("Please select a province.");
        isValid = false;
      }
      const city = form.elements["city"];
      if (city.value === "none") {
        city.setCustomValidity("Please select a city.");
        isValid = false;
      }
      const barangay = form.elements["barangay"];
      if (barangay.value === "none") {
        barangay.setCustomValidity("Please select a barangay.");
        isValid = false;
      }
      break;
    case 2:
      const features = formData.getAll("features[]");
      if (features.length === 0) {
        form.elements["features[]"].forEach((feature) => {
          feature.setCustomValidity("Please select at least one feature.");
        });
        isValid = false;
      }
      break;
    case 3:
      // Validate the main image
      const image = formData.get("image_path");
      if (!image) {
        form.elements["image_path"].setCustomValidity("Please select an image.");
        isValid = false;
      }

      // Validate floor plan images
      const numFloors = parseInt(form.elements["num_of_floors"].value);
      for (const i = 1; i <= numFloors; i++) {
        const floorPlan = formData.get(`floor_plan_${i}`);
        if (!floorPlan) {
          form.elements[`floor_plan_${i}`].setCustomValidity(
            "Please upload a floor plan image."
          );
          isValid = false;
        }
      }
      break;
  }

  if (!isValid) {
    form.classList.add("was-validated");
  } else {
    form.classList.remove("was-validated");
  }

  return isValid;
  }

  function updateValidation(input) {
    if (input.checkValidity()) {
      input.classList.remove("is-invalid");
      input.classList.add("is-valid");
    } else {
      input.classList.remove("is-valid");
      input.classList.add("is-invalid");
    }
  }

  function updateDescriptionWithLineBreaks() {
    const textareaP = document.getElementById('property_description');
    textareaP.value = addLineBreaks(textareaP.value);
    const textareaF = document.getElementById('features_description');
    textareaF.value = addLineBreaks(textareaF.value);
  }

</script>


<script>
    // Function to generate a sample floor plan based on the number of floors provided
    function generateFloorPlan() {
        const numFloors = document.getElementById("num_of_floors").value;
        const floorPlan = document.getElementById("floor_plan");
        floorPlan.innerHTML = "";

        for (const i = 1; i <= numFloors; i++) {
          const row = document.createElement("div");
          row.classList.add("row", "pb-2");
          row.innerHTML = '';
          const col1 = document.createElement("div");
          col1.classList.add("col-md-3");
          col1.innerHTML = "Floor " + i;
          const col2 = document.createElement("div");
          col2.classList.add("col-md-6");
          col2.innerHTML = `<div id="floor-plan-container_${i}" class="floor-plan-container float-right pb-3">
              <img id="uploaded-floor-plan_${i}" src="${isset(property_obj.floor_plan) ? '../img/' + property_obj.floor_plan_${i} : 'default-image.jpg'}" alt="Default Floor Plan" height="250px" width="250px">
            </div>`;

          // Add the floor plan image upload section
          const imageUpload = document.createElement("div");
          imageUpload.classList.add("form-group");
          imageUpload.innerHTML = `
            <label for="floor_plan_${i}">Floor Plan:</label>
            <input type="file" class="form-control form-control-sm" id="floor_plan_${i}" name="floor_plan[]" value="${isset(property_obj.floor_plan) ? property_obj.floor_plan_${i} : ''}" required oninput="updateValidation(this)">
            <p class="mt-2 floor-plan-file-name_${i}"></p>
          `;
          col2.appendChild(imageUpload);

          row.appendChild(col1);
          row.appendChild(col2);
          floorPlan.appendChild(row);

          // Initialize the floor plan image upload script
          (function (i) {
            document.getElementById(`floor_plan_${i}`).addEventListener("change", function () {
              const input = this;

              if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                  document.getElementById(`uploaded-floor-plan_${i}`).src = e.target.result;
                  document.querySelector(`.floor-plan-container_${i}`).style.display = "block";

                  // Set the file name in the <p> tag
                  const fileName = input.files[0].name;
                  document.querySelector(`.floor-plan-file-name_${i}`).textContent = "File name: " + fileName;
                };

                reader.readAsDataURL(input.files[0]);
              } else {
                document.getElementById(`uploaded-floor-plan_${i}`).src = "../img/floor_plans/default-image.png";
                document.querySelector(`.floor-plan-container_${i}`).style.display = "none";

                // Clear the file name in the <p> tag
                document.querySelector(`.floor-plan-file-name_${i}`).textContent = "";
              }
            });
          })(i);
        }

        // Remove the "d-none" class from the label element with "for" attribute set to "div"
        const divLabel = document.querySelector('label[for="div"]');
        divLabel.classList.remove('d-none');
      }


    $(document).ready(function() {                
    $('#image_path').on('change', function() {
      const input = this;
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
          $('#uploaded-image').attr('src', e.target.result);
          $('.image-container').show();
          $('#image_path').addClass('col-md-12');
          $('.image-container').css('display', 'block');

          // Set the file name in the <p> tag
          const fileName = input.files[0].name;
          $('.file-name').text('File name: ' + fileName);
          $('.file-name').addClass('col-md-12');
        };


        reader.readAsDataURL(input.files[0]);
      } else {
        $('#uploaded-image').attr('src', '../img/buildings/default-image.png');
        $('.image-container').hide();
        $('#image_path').removeClass('col-md-12');
        $('.image-container').css('display', 'none');

        // Clear the file name in the <p> tag
        $('.file-name').empty();
      }
    });
  });


</script>

<script>
  $('#region').on('change', function(){
      const formData = {
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
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
              alert("Status: " + textStatus); alert("Error: " + errorThrown); 
          }  
      });
  });
  $('#provinces').on('change', function(){
          const formData = {
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
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("Status: " + textStatus); alert("Error: " + errorThrown); 
              }  
          });
  });
  $('#city').on('change', function(){
          const formData = {
              filter: $("#city").val(),
              action: 'barangay',
          };
          $.ajax({
              type: "POST",
              url: '../includes/address.php',
              data: formData,
              success: function(result)
              {
                  console.log(formData);
                  console.log(result);
                  $('#barangay').html(result);
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("Status: " + textStatus); alert("Error: " + errorThrown); 
              }  
          });
  });

</script>

<script>
		const input = document.getElementById('image_path');
    const preview = document.getElementById('image-container');

    input.addEventListener('change', () => {
      const files = input.files;
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        createPreviewImage(file);
      }
    });

    function createPreviewImage(file) {
      if (preview.childElementCount >= 6) {
        alert("You can upload up to 6 images only.");
        return;
      }
      
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        const previewImage = document.createElement('div');
        previewImage.classList.add('uploaded-image', 'col-auto');
        previewImage.innerHTML = `
          <img src="${reader.result}">
          <div class="remove-image" onclick="removeImage(this.parentNode)">X</div>
        `;
        
        // Append the image to the last row
        preview.appendChild(previewImage);
      };
    }

    function removeImage(previewImage) {
      const input = document.getElementById('image_path');
      const files = Array.from(input.files);
      const index = Array.from(preview.children).indexOf(previewImage);
      if (index !== -1) {
        files.splice(index, 1);
        const newFileList = new DataTransfer();
        files.forEach(file => newFileList.items.add(file));
        input.files = newFileList.files;
      }
      previewImage.remove();
    }

</script>