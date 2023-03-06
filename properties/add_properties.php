<?php
  require_once '../includes/dbconfig.php';
  require_once '../tools/functions.php';
  require_once '../classes/properties.class.php';

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

     if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['step'])){
      $properties_obj = new Properties();
        //sanitize user inputs
      $properties_obj->property_name = htmlentities($_POST['property_name']);
      $properties_obj->landlord_id = htmlentities($_POST['landlord']);
      $properties_obj->region = $_POST['region'];
      $properties_obj->provinces = $_POST['provinces'];
      $properties_obj->city = $_POST['city'];
      $properties_obj->barangay = $_POST['barangay'];
      $properties_obj->street = $_POST['street'];
      $properties_obj->property_description = htmlentities($_POST['property_description']);
      $properties_obj->features_description = htmlentities($_POST['features_description']);

      if (isset($_FILES['image_path'])) {
        $image = $_FILES['image_path']['name'];
        $target = "../img/" . basename($image);

        if (move_uploaded_file($_FILES['image_path']['tmp_name'], $target)) {
            $properties_obj->image_path = $_FILES['image_path']['name'];
        } else {
            // handle file upload error
            $msg = "Error uploading file";
        }
      }else{
        // handle missing file error
        $msg = "Missing file upload";
      }
         // Insert the features data into the property_features table
        if (isset($_POST['features'])) {
          $features = $_POST['features'];
          $selected_features = array();
          foreach ($features as $feature_id) {
              // Insert the feature into the property_features table
              $query = "INSERT INTO property_features (property_id, feature_id) VALUES ('$property_id', '$feature_id')";
              mysqli_query($conn, $query);

              // Push the feature ID into the selected features array
              $selected_features[] = $feature_id;
          }
          /* Join the selected features into a comma-separated string
          Encode the selected features array as a JSON string */
          $features_json = json_encode($selected_features);

          // Set the features property of the account object to the selected features JSON string
          $properties_obj->features = $features_json;
        }

        

              // Add product to database
           /*  if(validate_add_landlord($_POST)){ */
              if ($properties_obj->properties_add()) {
                header('Location: properties.php');                
                exit; // always exit after redirecting
            } else {
                // handle product add error
                $msg = "Error adding property";
            }
          
        }
       
        

        
          
    require_once '../tools/variables.php';
    $page_title = 'RMS | Add Property';
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
              <h3 class="font-weight-bolder">ADD PROPERTY</h3> 
            </div>
            <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="properties.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
            <div class="d-flex">
              <span class="step rounded pt-3 pb-2 text-center">Basic Details</span>
              <span class="step rounded pt-3 pb-2 text-center">Location</span>
              <span class="step rounded pt-3 pb-2 text-center">Features</span>
              <span class="step rounded pt-3 pb-2 text-center">Images</span>
            </div>
            <form action="add_properties.php" id="regForm" method="post" enctype="multipart/form-data">
              <div class="col-12">
                <div class="tab">
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Property Details</h4>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                        <label for="property_name">Property Name</label>
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

                    <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                            <label for="landlord">Select Landlord</label>
                            <select class="form-control form-control-sm mb-3 req" id="landlord" name="landlord">
                              <option class="col-md-6" value="" disabled selected>Select Landlord</option>
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
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 grid-margin">
                <div class="tab">
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Property Details</h4>
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
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Property Details</h4>
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
                                echo "<div class='row p-3'>";
                                while ($row = mysqli_fetch_assoc($result)) {
                                  echo "
                                    <div class='col-sm-3 text-dark fs-6 h-25'>
                                      <input type='checkbox' class='checkmark req' id='feature_" . $row['id'] . "' name='features[]' value='" . $row['id'] . "'>" .
                                      "<label class='feature'  for='feature_" . $row['id'] . "'>" . $row['feature_name'] . "</label><br>
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
                  <div class="row g-3">
                    <h4 class="card-title fw-bolder">Property Details</h4>
                      <div class="pt-4">
                        <label for="image_path">Upload a picture of the property:</label>
                        <input class="form-control form-control-lg" type="file" id="image_path" name="image_path">
                      </div>
                  </div>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                  <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
// Initialize the form wizard
var currentStep = 0;
showStep(currentStep);

// Function to show the current step of the form wizard
function showStep(stepIndex) {
  var steps = document.getElementsByClassName("tab");
  steps[stepIndex].style.display = "block";
  if (stepIndex == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (stepIndex == (steps.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Save";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
}

// Function to move to the next or previous step of the form wizard
function nextPrev(step) {
  var steps = document.getElementsByClassName("tab");
  if (step > 0 && !validateStep(currentStep)) {
    return false;
  }
  steps[currentStep].style.display = "none";
  currentStep += step;
  if (currentStep >= steps.length) {
    saveData();
    return false;
  }
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
      var street = formData.get('street');
      if (street === '') {
        alert('Please enter a street name.');
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
        }
        return isValid;
        }

        // Function to save the data in the database
        function saveData() {
        if (!validateStep(currentStep)) {
        return false;
        }
        var form = document.getElementById("regForm");
        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        alert('Data has been saved successfully!');
        window.location.href = "properties.php";
        }
        };
        xhr.open("POST", "add_properties.php", true);
        xhr.send(formData);
        }




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

