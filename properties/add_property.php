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
              header('Location: property.php');
              exit;
            }  
          
    }

    $page_title = 'RMS | Add Property';
    $tenant = 'active';
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
                            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bolder">ADD PROPERTY</h3> 
                            </div>
                            <div class="add-page-container">
                                <div class="col-md-2 d-flex justify-align-between float-right">
                                    <a href="properties.php" class='bx bx-caret-left'>Back</a>
                                </div>
                            </div>
                            <form action="add_property.php" method="POST" id="property" class="property" enctype="multipart/form-data">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <h3>
                                                    Basic Details
                                                </h3>
                                                <fieldset>
                                                    <div class="col-md-6">
                                                        <div class="form-group-row">
                                                            <div class="col">
                                                            <label for="property_name">Property Name</label>
                                                            <input class="form-control form-control-sm" type="text" id="property_name" name="property_name" value="" required>                              
                                                            </div>
                                                        </div>
                                                        <div class="form-group-row">
                                                            <div class="col">
                                                                <label for="landlord">Select Landlord</label>
                                                                <select class="form-control form-control-sm" id="landlord" name="landlord">
                                                                    <option class="col-md-6" value="" disabled selected>Select Landlord</option>
                                                                    <?php
                                                                        // Connect to the database and retrieve the list of landlords
                                                                        $result = mysqli_query($conn, "SELECT id, last_name, first_name FROM landlord");
                                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                                        
                                                                        echo "<option value='" . $row['id'] . "'>" . $row['last_name'] . "," .$row['first_name']."</option>";
                                                                        }
                                                                    ?>
                                                                </select>
                                                            <div class="col">
                                                                <div class="col-lg-12">
                                                                    <label for="property_description">Description of the Property</label>
                                                                    <textarea class="form-control form-control-lg" id="property_description" name="property_description" col="100" row="20"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                                                        
                                                                    <label for="address">Address</label>
                                                                    <input class="form-control form-control-sm" type="text" id="address" name="address">
                                                                    <div class="col d-flex">
                                                                        <div class="col-sm-5">
                                                                        <label for="city">City</label>
                                                                        <input class="form-control form-control-sm" type="text" id="city" name="city" value="">
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label for="provinces">Province</label>
                                                                            <input class="form-control form-control-sm" type="text" id="provinces" name="provinces" value="">
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <label for="zip_code">Zip Code</label>
                                                                            <input class="form-control form-control-sm" type="text" id="zip_code" name="zip_code" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                <h3>
                                                    Features
                                                </h3>
                                                <fieldset>
                                                    <div class="form-radio">
                                                        <label for="job" class="label-radio">Description of Features</label>
                                                        <textarea class="form-control form-control-lg" id="features_description" name="features_description" col="100" row="20"></textarea>
                                                        <div class="form-flex">
                                                                <div class="col-md-6">
                                                                    <div class="form-group-row w-100">
                                                                        <div class="col">
                                                                        <p>Check box if features are allowed:</p>
                                                                            <?php
                                                                            // Connect to the database and retrieve the list of features
                                                                            $result = mysqli_query($conn, "SELECT id, feature_name FROM features");
                                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                                echo "
                                                                                <div class='d-flex col-sm-12'>
                                                                                    <input type='checkbox' id='feature_" . $row['id'] . "' name='features[]' value='" . $row['id'] . "'>" .
                                                                                    "<label for='feature_" . $row['id'] . "'>" . $row['feature_name'] . "</label><br>
                                                                                </div>
                                                                                ";
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                <h3>
                                                    Images
                                                </h3>
                                                <fieldset>
                                                    <div class="form-row form-input-flex">
                                                            <div>
                                                            <label for="property_picture">Upload a picture of the property:</label>
                                                            <input class="form-control form-control-lg" type="file" id="property_picture" name="property_picture">
                                                            </div>
                                                            <div class="ps-6">
                                                            <input type="submit" class="btn btn-success btn-sm" value="Save Property" name="save" id="save">
                                                            </div>
                                                    </div>
                                                </fieldset>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- JS -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="vendor/jquery-validation/dist/additional-methods.min.js"></script>
        <script src="vendor/jquery-steps/jquery.steps.min.js"></script>
        <script src="js/main.js"></script>
</body>


    
