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
    if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
        header('location: ../login/login.php');
    }

     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $property_obj = new Properties();

      //sanitize user inputs
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

      $image_paths = [];
      if (isset($_FILES['image_path'])) {
          $image_count = count($_FILES['image_path']['name']);

          for ($i = 0; $i < $image_count; $i++) {
              $image = $_FILES['image_path']['name'][$i];
              $target = "../img/buildings/" . basename($image);

              if (move_uploaded_file($_FILES['image_path']['tmp_name'][$i], $target)) {
                  $image_paths[$i] = $_FILES['image_path']['name'][$i];
              } else {
                  // handle file upload error
                  $msg = "Error uploading file";
              }
          }
      }
      if (!empty($image_paths)) {
          $property_obj->image_path = json_encode($image_paths);
      }

      $floor_plans = [];
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
      }
      if (!empty($floor_plans)) {
          $property_obj->floor_plan = json_encode($floor_plans);
      }


      // Add property to database
      if (validate_add_properties($_POST, $_FILES)) {
        if ($property_obj->properties_add()) {
          $_SESSION['added_properties'] = true;
          header('Location: buildings.php?add_success=1');
          exit; // always exit after redirecting
        } else {
          // handle property add error
          $msg = "Error uploading file";
        }
      }
  }

    require_once '../tools/variables.php';
    $page_title = 'RMS | Add Building';
    $properties = 'active';
    require_once '../includes/header.php';
?>
<head>
  <link rel="stylesheet" href="../css/form-wizard.css">
</head>
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
              <h3 class="font-weight-bolder">ADD BUILDING</h3>
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
            <form action="add_building.php" id="regForm" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
              <div class="col-12">
                <div class="tab">
                  <!-- Basic Details Step -->
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Basic Details</h4>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                        <label for="property_name">Building Name</label>
                        <input class="form-control form-control-sm req" type="text" id="property_name" name="property_name" required onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                        <div class="invalid-feedback">Please provide a valid property name (letters, spaces, and dashes only).</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <div class="col-lg-12">
                            <label for="property_description">Description of the Property</label>
                            <textarea class="form-control form-control-lg" id="property_description" name="property_description" oninput="updateDescriptionWithLineBreaks()" maxlength="500" onkeyup="handleKeyUp(event, this)" col="100" row="50"></textarea>
                            <div class="invalid-feedback">Invalid Description (letters, spaces, and dashes only).</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <div class="col-md-6">
                            <label for="landlord">Select Landlord</label>
                            <select title="--Select--" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false" data-size="5" data-liveSearchStyle="startsWith" name="landlord" id="landlord" required>
                            <?php
                            // Connect to the database and retrieve the list of landlords
                            $result = mysqli_query($conn, "SELECT id, last_name, first_name FROM landlord");
                            while ($row = mysqli_fetch_assoc($result)) {

                              echo "<option value='" . $row['id'] . "'>" . $row['last_name'] . "," .$row['first_name']."</option>";
                              }
                              ?>
                            </select>
                            <div class="invalid-feedback">Must select a Landlord</div>
                          </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="mt-4">
                            <label for="num_of_floors">Number of Floors</label>
                            <input class="form-control form-control-sm w-50 req" type="number" id="num_of_floors" name="num_of_floors" min="1" max="100" required onchange="generateFloorPlan()" <?php if(isset($_POST['num_of_floors'])){echo "value=".$_POST['num_of_floors'];}?>>
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
                    <div class="mar d-flex pt-3">
                      <div class="col-sm-6">
                        <div class="form-group-row">
                          <label for="street">Street</label>
                          <input class="form-control form-control-sm req" type="text" id="street" name="street" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                          <div class="invalid-feedback">Must provide a Street</div>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <label for="barangay">Barangay</label>
                        <select title="--Select--" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false" data-size="5" data-liveSearchStyle="startsWith" name="barangay" id="barangay" required>
                          <?php
                          require_once '../classes/reference.class.php';
                          $ref_obj = new Reference();
                          $ref = $ref_obj->get_barangay();
                          foreach ($ref as $row) {
                          ?>
                            <option value="<?=$row['brgyCode']?>" data-city="<?=$row['citymunCode']?>" data-province="<?=$row['provCode']?>" data-region="<?=$row['regCode']?>"><?=$row['brgyDesc']?></option>
                          <?php

                          }
                          ?>
                        </select>
                        <div class="invalid-feedback">Must select a Barangay</div>
                      </div>
                    </div>
                    <div class="mx-auto px-auto mt-5 mb-4 text-center text-secondary fs-7 font-weight-light" style="border-top: 1px solid #ccc;line-height: 0.1em;margin-inline: 76px;width: 570px;">
                      <span style="background:#fff;padding:0 10px;">Options may vary depending on the selected barangay</span>
                    </div>
                    <div class="mar d-flex pt-3">
                        <div class="col-sm-4">
                            <label for="city">City/Municipality</label>
                            <select class="form-control form-control-sm" id="city" name="city" required>
                                <option value="none">--Select--</option>
                                <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_city_by_brgy($brgyCode);
                                foreach ($ref as $row) {
                                ?>
                                  <option value="<?=$row['citymunCode']?>" selected><?=$row['citymunDesc']?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Must select a City</div>
                        </div>
                        <div class="col-sm-4">
                            <label for="provinces">Provinces</label>
                            <select class="form-control form-control-sm" id="provinces" name="provinces" required>
                                <option value="none">--Select--</option>
                                <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_province_by_city($citymunCode);
                                foreach ($ref as $row) {
                                ?>
                                  <option value="<?=$row['provCode']?>" selected><?=$row['provDesc']?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Must select Province</div>
                        </div>
                        <div class="col-sm-4">
                            <label for="region">Region</label>
                            <select type="text" class="form-control form-control-sm" name="region" id="region" required>
                                <option value="none">--Select--</option>
                                <?php
                                require_once '../classes/reference.class.php';
                                $ref_obj = new Reference();
                                $ref = $ref_obj->get_region_by_province($provCode);
                                foreach ($ref as $row) {
                                ?>
                                  <option value="<?=$row['regCode']?>" selected><?=$row['regDesc']?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Must select a Region</div>
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
                              <textarea class="form-control form-control-lg" id="features_description" name="features_description" oninput="updateDescriptionWithLineBreaks()" maxlength="500" onkeyup="handleKeyUp(event, this)" col="100" row="50"></textarea>
                              <div class="invalid-feedback">Invalid Description (letters, spaces, and dashes only).</div>
                          </div>
                      </div>
                    </div>
                    <div class="w-100">
                      <div class="form-group-row">
                        <div class="col d-flex">
                          <div class="col-lg-12">
                            <p>Check box if features are allowed: <span class="fs-5 req">*</span></p>
                              <?php
                              // Connect to the database and retrieve the list of features
                              $result = mysqli_query($conn, "SELECT id, feature_name FROM features");
                              
                              echo "<div class='row p-3'>";
                              while ($row = mysqli_fetch_assoc($result)) {
                                $checked = in_array($row['id'], $selected_features) ? "checked" : "";
                                echo "
                                  <div class='col-sm-4 text-dark'>
                                    <input type='checkbox' class='checkmark req' id='feature" . $row['id'] . "' name='features[]' value='" . $row['id'] . "' $checked required'><label class='feature' for='feature" . $row['id'] . "'>" . $row['feature_name'] . "</label><br>
                                  </div>
                                ";
                              }
                              echo"</div>";
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
                    <div class="pt-4 h-100">
                      <label for="image_path">Upload a picture of the property:</label>
                      <div class="image-container row g-3 pb-3 h-25" id="image-container" style="display: none;"></div>
                      <input class="form-control form-control-lg" type="file" id="image_path" name="image_path[]" multiple accept=".jpg,.jpeg,.png" max="6" required>
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

  <script defer src="../js/addbuilding.js"></script>

<script>
      // Function to generate a sample floor plan based on the number of floors provided
      function generateFloorPlan() {
        var numFloors = document.getElementById("num_of_floors").value;
        var floorPlan = document.getElementById("floor_plan");
        floorPlan.innerHTML = "";

        for (var i = 1; i <= numFloors; i++) {
          var row = document.createElement("div");
          row.classList.add("row", "pb-2");
          row.innerHTML = '';
          var col1 = document.createElement("div");
          col1.classList.add("col-md-6");
          col1.innerHTML = "Floor " + i;
          col1.innerHTML += `<div id="floor-plan-container_${i}" class="floor-plan-container pb-3 d-flex justify-content-center">
                        <img id="uploaded-floor-plan_${i}" src="../img/floor_plans/default-image.png" alt="Default Floor Plan" height="250px" width="250px">
                      </div>`;
          var col2 = document.createElement("div");
          col2.classList.add("col-md-6");


          // Add the floor plan image upload section
          var imageUpload = document.createElement("div");
          imageUpload.classList.add("form-group");
          imageUpload.innerHTML = `
            <label for="floor_plan_${i}">Floor Plan:</label>
            <input type="file" class="form-control form-control-sm" id="floor_plan_${i}" name="floor_plan[]" required>
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
        var divLabel = document.querySelector('label[for="div"]');
        divLabel.classList.remove('d-none');
      }
</script>
</body>