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

  if(isset($_POST['property_save'])){
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

      if (isset($_FILES['image_path'])) {
        $image = $_FILES['image_path']['name'];
        $target = "../img/buildings/" . basename($image);
    
        if (move_uploaded_file($_FILES['image_path']['tmp_name'], $target)) {
            $property_obj->image_path = $_FILES['image_path']['name'];
        } else {
            // handle file upload error
            $msg = "Error uploading file";
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
        if ($property_obj->properties_add()) {
          header('Location: buildings.php');
          exit; // always exit after redirecting
        } else {
          // handle property add error
          $msg = "Error uploading file";
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
            <form action="add_building.php" id="regForm" method="post" enctype="multipart/form-data">
              <div class="col-12">
                <div class="tab">
                  <!-- Basic Details Step -->
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Basic Details</h4>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                        <label for="property_name">Building Name</label>
                        <input class="form-control form-control-sm req" type="text" id="property_name" name="property_name">                              
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <div class="col-lg-12">
                            <label for="property_description">Description of the Property</label>
                            <textarea class="form-control form-control-lg" id="property_description" name="property_description"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <div class="col-md-6">
                            <label for="landlord">Select Landlord</label>
                            <select class="form-control form-control-sm mb-3 req" id="landlord" name="landlord">
                              <option class="col-md-6" value="none" disabled selected>Select Landlord</option>
                                <?php
                                  // Connect to the database and retrieve the list of landlords
                                  $result = mysqli_query($conn, "SELECT id, last_name, first_name FROM landlord");
                                  while ($row = mysqli_fetch_assoc($result)) {
                                  
                                    echo "<option value='" . $row['id'] . "'>" . $row['last_name'] . "," .$row['first_name']."</option>";
                                  }
                                ?>
                            </select>
                          </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="">
                            <label for="num_of_floors">Number of Floors</label>
                            <input class="form-control form-control-sm req" type="number" id="num_of_floors" name="num_of_floors" min="1" max="100" onchange="generateFloorPlan()" <?php if(isset($_POST['num_of_floors'])){echo "value=".$_POST['num_of_floors'];}?>>
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
                          <select type="text" class="form-control form-control-sm req" name="region" id="region" placeholder="" > 
                            <option value="none">--Select--</option>
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
                        </div>
                        <div class="col-sm-3">
                          <label for="provinces">Provinces</label>
                          <select id="provinces" class="form-control form-control-sm req" id="provinces" name="provinces">
                            <option value="none">--Select--</option>
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
                        </div>
                        <div class="col-sm-3">
                          <label for="city">City</label>
                          <select id="city" class="form-control form-control-sm req" id="city" name="city" >
                            <option value="none">--Select--</option>
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
                        </div>
                      </div>
                      <div class="mar d-flex pt-3">
                        <div class="col-sm-5">
                          <label for="barangay">Barangay</label>
                          <select class="form-control form-control-sm req" name="barangay" id="barangay"> 
                            <option value="none">--Select--</option>
                            <?php
                                  require_once '../classes/reference.class.php';
                                  $ref_obj = new Reference();
                                  $ref = $ref_obj->get_brgy($citymunCode);
                                  foreach($ref as $row){
                              ?>
                                      <option value="<?=$row['brgyCode']?>"><?=$row['brgyDesc']?></option>
                              <?php
                                  }
                              ?>
                          </select>
                        </div>
                        <div class="col-sm-5">
                          <div class="form-group-row">
                            <label for="street">Street</label>
                            <input class="form-control form-control-sm req" type="text" id="street" name="street" value="">
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
                              <textarea class="form-control form-control-lg" id="features_description" name="features_description"></textarea>
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
                              echo "<div class='row p-3'>";
                              while ($row = mysqli_fetch_assoc($result)) {
                                $checked = in_array($row['id'], $selected_features) ? "checked" : "";
                                echo "
                                  <div class='col-sm-4 text-dark'>
                                    <input type='checkbox' class='checkmark req' id='feature" . $row['id'] . "' name='features[]' value='" . $row['id'] . "' $checked>" .
                                    "<label class='feature'  for='feature" . $row['id'] . "'>" . $row['feature_name'] . "</label><br>
                                  </div>
                                  ";
                              }
                              echo"</div>";
                            ?>
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
                        <div class="image-container w-100 pb-3" style="display: none;">
                          <img id="uploaded-image" src="../img/buildings/default-image.png" alt="Default Image" height="300px" width="870px">
                          <?php if (!empty($property_obj->image_path)) { ?>
                          <p class="mt-2 file-name">File name: <?php echo basename($property_obj->image_path); ?></p>
                          <?php } else { ?>
                            <p class="mt-2 ml-2 file-name text-break">No file selected yet</p>
                          <?php } ?>
                        </div>
                        <input class="form-control form-control-lg" type="file" id="image_path" name="image_path" accept=".jpg,.jpeg,.png">
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Initialize the form wizard
    var currentStep = 0;
    showStep(currentStep);

    // Function to show the current step of the form wizard
    function showStep(stepIndex) {
      var steps = document.getElementsByClassName("tab");
      steps[stepIndex].style.display = "block";

      // Get all the span tags with class "step"
      var stepSpans = document.getElementsByClassName("step");
      
      // Loop through all the span tags and remove the "active" class from them
      for (var i = 0; i < stepSpans.length; i++) {
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
      }
    
      // Function to move to the next or previous step of the form wizard
      function nextPrev(step) {
      var steps = document.getElementsByClassName("tab");
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

    
    // Function to validate the current step of the form wizard
    function validateStep(stepIndex) {
      var isValid = true;
      var form = document.getElementById("regForm");
      var formData = new FormData(form);
      switch (stepIndex) {
        case 0:
        var propertyName = formData.get('property_name');
        if (propertyName === '') {
            alert('Please enter a property name.');
            isValid = false;
        }
        var landlord = formData.get('landlord');
        if (landlord === '') {
            alert('Please select a landlord.');
            isValid = false;
        }
        var num_floor = formData.get('num_of_floors');
        if (num_floor < 1) {
            alert('Number of floors should be equal to or greater than 1.');
            isValid = false;
        }
        break;
        case 1:
        var region = formData.get('region');
        if (region === 'none') {
            alert('Please select a region.');
            isValid = false;
        }
        var provinces = formData.get('provinces');
        if (provinces === 'none') {
            alert('Please select a province.');
            isValid = false;
        }
        var city = formData.get('city');
        if (city === 'none') {
            alert('Please select a city.');
            isValid = false;
        }
        var barangay = formData.get('barangay');
        if (barangay === 'none') {
            alert('Please select a barangay.');
            isValid = false;
        }
        break;
        case 2:
        var features = formData.getAll('features[]');
        if (features.length === 0) {
          alert('Please select at least one feature.');
          isValid = false;
        }
        break;
        case 3:
        // Perform any validation needed for the last step, e.g., checking if an image has been selected
        var image = formData.get('image_path');
          if (!image) {
              alert('Please select an image.');
              isValid = false;
          }
        break;
        }
      return isValid;
    }

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
          col1.classList.add("col-md-3");
          col1.innerHTML = "Floor " + i;
          var col2 = document.createElement("div");
          col2.classList.add("col-md-6");

          // Add the floor plan image upload section
          var imageUpload = document.createElement("div");
          imageUpload.classList.add("form-group");
          imageUpload.innerHTML = `
            <label for="floor_plan_${i}">Floor Plan:</label>
            <input type="file" class="form-control form-control-sm" id="floor_plan_${i}" name="floor_plan[]">
            <div class="floor-plan-container float-right pb-3" style="display: none;">
              <img id="uploaded-floor-plan_${i}" src="../img/floor_plans/default-image.png" alt="Default Floor Plan" height="250px" width="250px">
            </div>
            <p class="mt-2 floor-plan-file-name_${i}"></p>
          `;
          col2.appendChild(imageUpload);

          row.appendChild(col1);
          row.appendChild(col2);
          floorPlan.appendChild(row);

          // Initialize the floor plan image upload script
          $(`#floor_plan_${i}`).on('change', function() {
            const input = this;

            if (input.files && input.files[0]) {
              const reader = new FileReader();

              reader.onload = function(e) {
                $(`#uploaded-floor-plan_${i}`).attr('src', e.target.result);
                $(`.floor-plan-container_${i}`).show();
                $(`.floor-plan-container_${i}`).css('display', 'block');

                // Set the file name in the <p> tag
                const fileName = input.files[0].name;
                $(`.floor-plan-file-name_${i}`).text('File name: ' + fileName);
              };

              reader.readAsDataURL(input.files[0]);
            } else {
              $(`#uploaded-floor-plan_${i}`).attr('src', '../img/floor_plans/default-image.png');
              $(`.floor-plan-container_${i}`).hide();
              $(`.floor-plan-container_${i}`).css('display', 'none');

              // Clear the file name in the <p> tag
              $(`.floor-plan-file-name_${i}`).empty();
            }
          });
        }

        // Remove the "d-none" class from the label element with "for" attribute set to "div"
        var divLabel = document.querySelector('label[for="div"]');
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
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                }  
            });
    });
    $('#city').on('change', function(){
            var formData = {
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

</body>

