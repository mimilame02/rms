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
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
        header('location: ../login/login.php');
    }

    if(isset($_POST['save'])){
        $account_obj = new Tenant();
        //sanitize user inputs
        $account_obj->first_name = htmlentities($_POST['first_name']);
        $account_obj->last_name = htmlentities($_POST['last_name']);
        $account_obj->email = htmlentities($_POST['email']);
        $account_obj->contact_no = htmlentities($_POST['contact_no']);
        $account_obj->relationship_status = htmlentities($_POST['relationship_status']);
        $account_obj->type_of_household = htmlentities($_POST['type_of_household']);
        $account_obj->previous_address = htmlentities($_POST['previous_address']);
        $account_obj->city = htmlentities($_POST['city']);
        $account_obj->provinces = htmlentities($_POST['provinces']);
        $account_obj->zip_code = htmlentities($_POST['zip_code']);
        $account_obj->sex = ' ';
        if (isset($_POST['sex'])) {
          $sex = $_POST['sex'];
        } else {
          $sex = 'Female';
        }
        $account_obj->date_of_birth = htmlentities($_POST['date_of_birth']);
        $account_obj->has_pet = ' ';
        if (isset($_POST['has_pet'])) {
          $has_pet = $_POST['has_pet'];
          if ($has_pet === 'No') {
            // If the user selects "No" for owning a pet, set the values of number_of_pets and type_of_pet to "0" and "None" respectively
            $account_obj->number_of_pets = 0;
            $account_obj->type_of_pet = 'None';
          } else {
            $account_obj->number_of_pets = isset($_POST['number_of_pets']) ? $_POST['number_of_pets'] : null;
            $account_obj->type_of_pet = isset($_POST['type_of_pet']) ? $_POST['type_of_pet'] : null;
          }
        } else {
          $has_pet = 'No';
          $account_obj->number_of_pets = 0;
          $account_obj->type_of_pet = 'None';
        }
        $account_obj->number_of_pets = htmlentities($_POST['number_of_pets']);
        $account_obj->type_of_pet = htmlentities($_POST['type_of_pet']);
        $account_obj->is_smoking = ' ';
        if (isset($_POST['is_smoking'])) {
          $is_smoking = $_POST['is_smoking'];
        } else {
          $is_smoking = 'No';
        }

        $account_obj->has_vehicle = '';
          if (isset($_POST['has_vehicle']) && is_array($_POST['has_vehicle'])) {
              $has_vehicle = implode(',', $_POST['has_vehicle']);
          }


        $account_obj->occupants = htmlentities($_POST['occupants']);
        $account_obj->co_applicant_first_name = htmlentities($_POST['co_applicant_first_name']);
        $account_obj->co_applicant_last_name = htmlentities($_POST['co_applicant_last_name']);
        $account_obj->co_applicant_email = htmlentities($_POST['co_applicant_email']);
        $account_obj->co_applicant_contact_no = htmlentities($_POST['co_applicant_number']);
        
        $account_obj->emergency_contact_person = htmlentities($_POST['emergency_fname']);
        $account_obj->emergency_contact_number = htmlentities($_POST['emergency_contact']);
        if(validate_add_tenants($_POST)){
          if($account_obj->tenants_add()){  
              //redirect user to landing page after saving
              header('location: tenants.php');
          }
      }
    }

  require_once '../tools/variables.php';
    $page_title = 'RMS | Add Tenant';
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
          <h3 class="font-weight-bolder">ADD TENANT</h3> 
        </div>
        <div class="add-page-container">
          <div class="col-md-2 d-flex justify-align-between float-right">
            <a href="tenants.php" class='bx bx-caret-left'>Back</a>
          </div>
        </div>
      </div>
      <form action="add_tenant.php" method="post">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title fw-bolder">Tenant Details</h4>
                      <div class="row g-3">
                          <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                                <label for="first_name">First Name</label>
                                <input class="form-control form-control-sm" type="text" id="first_name" name="first_name" value="" >                              
                              </div>
                            </div>
                          </div>
                          <?php
                          if(isset($_POST['save']) && !validate_first_name($_POST)){
                          ?>
                            <span class="text-danger">First name is invalid. Trailing spaces will be ignored.</span>
                          <?php
                              }
                          ?>
                          <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                              <label for="previous_address">Previous Address</label>
                              <input class="form-control form-control-sm" type="text" id="previous_address" name="previous_address" value="">
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                              <label for="last_name">Last Name</label>
                              <input class="form-control form-control-sm" type="text" id="last_name" name="last_name" value="" >
                              </div>
                            </div>
                          </div>
                          <?php
                          if(isset($_POST['save']) && !validate_last_name($_POST)){
                          ?>
                            <span class="text-danger">Last name is invalid. Trailing spaces will be ignored.</span>
                          <?php
                              }
                          ?>
                            <div class="col-md-6">
                              <div class="">
                                <div class="col d-flex">
                                  <div class="col-sm-5">
                                    <label for="city">City</label>
                                    <select class="form-control form-control-sm" type="text" id="city" name="city" value="">
                                    <option value="none">--Select--</option>
                                    <?php
                                    require_once '../classes/reference.class.php';
                                    $ref_obj = new Reference();
                                    $ref = $ref_obj->get_City($_POST['filter']);
                                    foreach($ref as $row){
                                ?>
                                        <option value="<?=$row['citymunCode']?>"><?=$row['citymunDesc']?></option>
                                <?php
                                    }
                                    ?>
                                    </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="provinces">Province</label>
                                        <select class="form-control form-control-sm" type="text" id="provinces" name="provinces" value="">
                                        <option value="none">--Select--</option>
                                        <?php
                                            require_once '../classes/reference.class.php';
                                            $ref_obj = new Reference();
                                             $ref = $ref_obj->get_province($_POST['filter']);
                                             foreach($ref as $row){
                                                      ?>
                                                 <option value="<?=$row['provCode']?>"><?=$row['provDesc']?></option>
                                                     <?php
                                                       }
                                              ?>
                                      </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="zip_code">Zip Code</label>
                                        <input class="form-control form-control-sm" type="text" id="zip_code" name="zip_code" value="">
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                                <label for="email">Email</label>
                                <input class="form-control form-control-sm" placeholder="Email" type="text" id="email" name="email">
                              </div>
                            </div>
                            <?php
                            if(isset($_POST['save']) && !validate_email($_POST)){
                            ?>
                              <span class="text-danger">Email is invalid. Trailing spaces will be ignored.</span>
                            <?php
                                }
                            ?>
                            </div>
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col">
                                <label for="sex">Sex</label><br>
                                <input type="radio" id="sex" name="sex" value="Male">
                                <label for="male">Male</label>
                                <input type="radio" id="sex" name="sex" value=" ">
                                <label for="female">Female</label>
                                </div>
                                <div class="col">
                                  <label for="date_of_birth">Date of Birth</label>
                                  <div class="col-md-12">
                                  <input class="form-control form-control-sm" type="date" id="date_of_birth" name="date_of_birth" value="">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                <label for="contact_no">Contact Number</label>
                                <input class="form-control form-control-sm" type="text" id="contact_no" name="contact_no" value="" >
                                </div>
                              </div>
                            </div>
                            <?php
                            if(isset($_POST['save']) && !validate_contact_num($_POST)){
                            ?>
                              <span class="text-danger">Contact Number is invalid. Trailing spaces will be ignored.</span>
                            <?php
                                }
                            ?>
                            <div class="col-md-6">
                        <div class="row">
                        <div class="col">
                        <label for="has_pet">Do Tenant own a pet?</label><br>
                        <input type="radio" id="has_pet_yes" name="has_pet" value="Yes">
                        <label for="has_pet_yes">Yes</label>
                        <input type="radio" id="has_pet_no" name="has_pet" value="No">
                      <label for="has_pet_no">No</label>
                      </div>
                    <div class="col">
                               <label for="number_of_pets">No. of Pets</label>
                    <div class="col-sm-12">
                        <input class="form-control form-control-sm" type="number" name="number_of_pets" id="number_of_pets">
                            </div>
                         </div>
                  <div class="col">
                      <label for="type_of_pet">Pet Type:</label>
                    <div class="col-sm-12">
                 <input class="form-control form-control-md" type="text" id="type_of_pet" name="type_of_pet">
                    </div>
                      </div>
                        </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="relationship_status">Relationship Status</label>
                                  <select class="form-control form-control-sm" id="relationship_status" name="relationship_status" >
                                    <option name="relationship_status" value="None">--Select--</option>
                                    <option name="relationship_status" value="single">Single</option>
                                    <option name="relationship_status" value="in a relationship">In a relationship</option>
                                    <option name="relationship_status" value="married">Married</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="is_smoking">Do Tenant Smoke?</label><br>
                                  <input type="radio" id="is_smoking" name="is_smoking" value="Yes">
                                  <label for="yes">Yes</label>
                                  <input type="radio" id="is_smoking" name="is_smoking" value=" ">
                                  <label for="no">No</label>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="type_of_household">Type of Household</label>
                                  <select class="form-control form-control-sm" id="type_of_household" name="type_of_household" >
                                  <option value="None">--Select--</option>
                                    <option name="type_of_household" value="one person">One Person</option>
                                    <option name="type_of_household" value="couple">Couple</option>
                                    <option name="type_of_household" value="single parent">Single Parent</option>
                                    <option name="type_of_household" value="family">Family</option>
                                    <option name="type_of_household" value="extended family">Extended Family</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="has_vehicle">Please check if tenant own any of the vehicles:</label><br>
                                  <input type="checkbox" name="has_vehicle" value="car">Car<br>
                                  <input type="checkbox" name="has_vehicle" value="motorcycle">Motorcycle<br>
                                  <input type="checkbox" name="has_vehicle" value="others">Others<br>
                                  <div class="d-flex col-sm-12">
                                    <label for="other_vehicle" hidden>If other, please specify:</label><br>
                                    <input class="form-control form-control-sm" type="text" name="other_vehicle_type" placeholder="" style="display:none;" ><br>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="form-group-row">
                                <div class="col">
                                  <h3 class="table-title">Other Occupants</h3>
                                </div>
                              </div>
                              <div class="col-md-12 d-flex">
                                <div class="form-group-row w-100">
                                  <div class="col">
                                    <label for="occupants">Full Name/s</label>
                                    <textarea class="form-control form-control-lg" id="occupants" name="occupants" cols="100" rows="5"  ></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group-row">
                                <div class="col d-flex">
                                  <h3 class="table-title">Co-Applicant Details</h3>
                                    <input type="hidden" id="status" name="status" value="Primary">
                                    <button class="btn btn-success col-sm-3 ml-5 p-0" type="button" id="set_to_primary"><i class="bx bx-plus btn-icon-prepend"></i> Set to Primary</button>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-12 d-flex">
                              <div class="form-group-row w-50">
                                <div class="col">
                                  <label for="co_fname">First Name</label>
                                  <input class="form-control form-control-sm" type="text" id="co_applicant_first_name" name="co_applicant_first_name" >
                                </div>
                              </div>
                              <div class="form-group-row w-50">
                                <div class="col">
                                  <label for="co_email">Email</label>
                                  <input class="form-control form-control-sm" type="email" id="co_applicant_email" name="co_applicant_email" >
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12 d-flex">
                              <div class="form-group-row w-50">
                                  <div class="col">
                                    <label for="co_lname">Last Name</label>
                                    <input class="form-control form-control-sm" type="text" id="co_applicant_last_name" name="co_applicant_last_name" >
                                  </div>
                              </div>
                              <div class="form-group-row w-50">
                                <div class="col"> 
                                  <label for="co_num">Contact No.</label>
                                  <input class="form-control form-control-sm" type="text" id="co_applicant_number" name="co_applicant_number" >
                                  </div>
                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="form-group-row">
                                <div class="col">
                                  <h3 class="table-title">Emergency Contact Person Details</h3>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12 d-flex">
                              <div class="form-group-row w-50">
                                <div class="col">
                                  <label for="emergency_fname">Full Name</label>
                                  <input class="form-control form-control-sm" type="text" id="emergency_fname" name="emergency_fname" >
                                </div>
                              </div>
                              <div class="form-group-row w-50">
                                <div class="col">
                                  <label for="emergency_contact">Contact No.</label>
                                  <input class="form-control form-control-sm" type="text" id="emergency_contact" name="emergency_contact" >
                                </div>
                              </div>
                            </div>
                          <div class="ps-6">
                            <input type="submit" class="btn btn-success btn-sm" value="Save Tenant" name="save" id="save">
                          </div>
                      </div>
                  </div>
                </div>
            </div>
          </div>
        </div>

          <script>

              document.getElementById("set_to_primary").addEventListener("click", function(){
              document.getElementById("status").value = "Primary";
            });

            // Add an event listener to the "has_pet" radio buttons
             const hasPetRadioButtons = document.getElementsByName("has_pet");
              hasPetRadioButtons.forEach((radioButton) => {
              radioButton.addEventListener("click", function() {
               if (this.value === "No") {
             // If the user selects "No" for owning a pet, set the values of number_of_pets and type_of_pet to "0" and "None" respectively
              document.getElementById("number_of_pets").value = "0";
              document.getElementById("type_of_pet").value = "None";
      }
    });
  });

            // Script to show/hide "other_vehicle_type" input field
            var vehicleTypeCheckboxes = document.querySelectorAll('input[name="has_vehicle"]');
            var otherVehicleTypeInput = document.querySelector('input[name="other_vehicle_type"]');
            var otherVehicleTypeLabel = document.querySelector('label[for="other_vehicle"]');

            vehicleTypeCheckboxes.forEach(function(checkbox) {
              checkbox.addEventListener('change', function() {
                if (checkbox.value === 'others' && checkbox.checked) {
                  otherVehicleTypeInput.style.display = 'block';
                  otherVehicleTypeLabel.hidden = false;
                } else {
                  otherVehicleTypeInput.style.display = 'none';
                  otherVehicleTypeLabel.hidden = true;
                }
              });
            });
          </script>
      </form>
