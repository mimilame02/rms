<?php
  require_once '../tools/variables.php';
  require_once '../includes/dbconfig.php';

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

        if(isset($_POST['save'])){

            //sanitize user inputs
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              $property_name = $_POST['property_name'];
              $landlord_id = $_POST['landlord'];
              $address = $_POST['address'];
              $city = $_POST['city'];
              $province = $_POST['province'];
              $zip_code = $_POST['zip_code'];
              $property_description = $_POST['property_description'];
              $features_description = $_POST['features_description'];
              $features = $_POST['features'];
              $property_picture = $_FILES['property_ picture'];
          
              // Perform data validation here
          
              // Insert the property data into the property table
              $query = "INSERT INTO property (property_name, landlord_id, address, city, province, zip_code, property_description, features_description)
                        VALUES ('$property_name', '$landlord_id', '$address', '$city', '$province', '$zip_code', '$property_description', '$features_description')";
              mysqli_query($conn, $query);
          
              // Get the id of the newly inserted property
              $property_id = mysqli_insert_id($conn);
          
              // Insert the features data into the property_features table
              if (isset($features)) {
                foreach ($features as $feature_id) {
                  $query = "INSERT INTO property_features (property_id, feature_id) VALUES ('$property_id', '$feature_id')";
                  mysqli_query($conn, $query);
                }
              }
          
              // Upload the property picture
              // Code to upload the property picture goes here
          
              // Redirect to the property list page
              header('Location: properties.php');
              exit;
            }  
          
    }

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
              <span class="step rounded pt-3 pb-2 text-center">Features</span>
              <span class="step rounded pt-3 pb-2 text-center">Images</span>
            </div>
            <form action="add_property.php" id="regForm" method="post">

                <div class="col-12">
                      <div class="tab">
                            <div class="row g-3">
                              <h4 class="card-title fw-bolder">Property Details</h4>
                              <div class="col-md-6">
                                <div class="form-group-row">
                                  <div class="col">
                                  <label for="property_name">Property Name</label>
                                  <input class="form-control form-control-sm" type="text" id="property_name" name="property_name" value="" required>                              
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
                                      <select class="form-control form-control-sm mb-3" id="landlord" name="landlord">
                                        <option class="col-md-6" value="" disabled selected>Select Landlord</option>
                                          <?php
                                            // Connect to the database and retrieve the list of landlords
                                            $result = mysqli_query($conn, "SELECT id, last_name, first_name FROM landlord");
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            
                                              echo "<option value='" . $row['id'] . "'>" . $row['last_name'] . "," .$row['first_name']."</option>";
                                            }
                                          ?>
                                      </select>
                                                            
                                        <label for="address">Address</label>
                                        <input class="form-control form-control-sm mb-3" type="text" id="address" name="address">
                                      <div class="mar d-flex">
                                        <div class="col-sm-5">
                                          <label for="city">City</label>
                                          <input class="form-control form-control-sm" type="text" id="city" name="city" value="">
                                          </div>
                                          <div class="col-sm-5">
                                              <label for="provinces">Province</label>
                                              <input class="form-control form-control-sm" type="text" id="provinces" name="provinces" value="">
                                            </select>
                                          </div>
                                          <div class="col-sm-5">
                                              <label for="zip_code">Zip Code</label>
                                              <input class="form-control form-control-sm" type="text" id="zip_code" name="zip_code" value="">
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
                                                  <input type='checkbox' class='checkmark' id='feature_" . $row['id'] . "' name='features[]' value='" . $row['id'] . "'>" .
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
                                  <label for="property_picture">Upload a picture of the property:</label>
                                  <input class="form-control form-control-lg" type="file" id="property_picture" name="property_picture">
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
  var currentTab = 0; // Current tab is set to be the first tab (0)
  showTab(currentTab); // Display the current tab

  function showTab(n) {
    // This function will display the specified tab of the form ...
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    // ... and fix the Previous/Next buttons:
    if (n == 0) {
      document.getElementById("prevBtn").style.display = "none";
    } else {
      document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
      document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
      document.getElementById("nextBtn").innerHTML = "Next";
    }
    // ... and run a function that displays the correct step indicator:
    fixStepIndicator(n)
  }

  function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form... :
    if (currentTab >= x.length) {
      //...the form gets submitted:
      document.getElementById("regForm").submit();
      return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
  }

  function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
      // If a field is empty...
      if (y[i].value == "") {
        // add an "invalid" class to the field:
        y[i].className += " invalid";
        // and set the current valid status to false:
        valid = false;
      }
    }
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
      document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid; // return the valid status
  }

  function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
      x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class to the current step:
    x[n].className += " active";
  }
</script>

</body>

