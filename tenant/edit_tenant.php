<?php
  require_once '../tools/functions.php';
  require_once '../classes/tenants.class.php';
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
  
      // sanitize user inputs
      $id = htmlentities($_POST['id']);
      $first_name = htmlentities($_POST['first_name']);
      $last_name = htmlentities($_POST['last_name']);
      $email = htmlentities($_POST['email']);
      $contact_no = htmlentities($_POST['contact_no']);
      $relationship_status = htmlentities($_POST['relationship_status']);
      $type_of_household = htmlentities($_POST['type_of_household']);
      $previous_address = htmlentities($_POST['previous_address']);
      $city = htmlentities($_POST['city']);
      $provinces = htmlentities($_POST['provinces']);
      $zip_code = htmlentities($_POST['zip_code']);
      $sex = htmlentities($_POST['sex']);
      $date_of_birth = htmlentities($_POST['date_of_birth']);
      $has_pet = htmlentities($_POST['has_pet']);
      $number_of_pets = htmlentities($_POST['number_of_pets']);
      $type_of_pet = htmlentities($_POST['type_of_pet']);
      $is_smoking = htmlentities($_POST['is_smoking']);
    
      $vehicle_types = '';
      if (isset($_POST['vehicle_type']) && is_array($_POST['vehicle_type'])) {
        $vehicle_types = implode(',', $_POST['vehicle_type']);
      }
    
      $occupants = htmlentities($_POST['occupants']);
      $co_applicant_first_name = htmlentities($_POST['co_applicant_first_name']);
      $co_applicant_last_name = htmlentities($_POST['co_applicant_last_name']);
      $co_applicant_email = htmlentities($_POST['co_applicant_email']);
      $co_applicant_contact_no = htmlentities($_POST['co_applicant_contact_no']);
      $emergency_contact_person = htmlentities($_POST['emergency_contact_person']);
      $emergency_contact_number = htmlentities($_POST['emergency_contact_number']);
    
      // check if tenant record exists
      $check_tenant_query = "SELECT * FROM tenant WHERE id = '$id'";
      $check_tenant_result = mysqli_query($conn, $check_tenant_query);
      $tenant = mysqli_fetch_assoc($check_tenant_result);
    
      if ($tenant) {
        // tenant record exists, perform update
        $update_query = "UPDATE tenant SET 
          tenant_id = '$id'
          first_name = '$first_name',
          last_name = '$last_name',
          email = '$email',
          contact_no = '$contact_no',
          relationship_status = '$relationship_status',
          type_of_household = '$type_of_household',
          previous_address = '$previous_address',
          city = '$city',
          provinces = '$provinces',
          zip_code = '$zip_code',
          sex = '$sex',
          date_of_birth = '$date_of_birth',
          has_pet = '$has_pet',
          number_of_pets = '$number_of_pets',
          type_of_pet = '$type_of_pet',
          is_smoking = '$is_smoking',
          has_vehicle = '$vehicle_types',
          occupants = '$occupants',
          co_applicant_first_name = '$co_applicant_first_name',
          co_applicant_last_name = '$co_applicant_last_name',
          co_applicant_email = '$co_applicant_email',
          co_applicant_contact_no = '$co_applicant_contact_no',
          emergency_contact_person = '$emergency_contact_person',
          emergency_contact_number = '$emergency_contact_number'
          WHERE id = $id";
          $update_result = mysqli_query($conn, $update_query);

          if ($update_result) {
            header("Location: tenants.php");
            exit;
          } else {
            echo "Error updating record: " . mysqli_error($conn);
          }
        }
      }

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
    <form action="edit_tenant.php" method="POST">
        <input type="text" hidden name="id" value="<?php echo $id->id; ?>">
        <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title fw-bolder">Tenant Details</h4>
                    <form class="form-sample">
                      <div class="row g-3">
                          <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                                <label for="first_name">First Name</label>
                                <input  class="form-control form-control-sm " placeholder="First name" type="text" id="first_name" name="first_name" value="" required ="">
                              </div>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                              <label for="previous_address">Previous Address</label>
                                <input class="form-control form-control-sm" placeholder="House No., Building No."  type="text" id="previous_address" name="previous_address" value="" required ="">
                              </div>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                                <label for="last_name">Last Name</label>
                                <input class="form-control form-control-sm" placeholder="Last name" type="text" id="last_name" name="last_name" value="" required ="">
                              </div>
                            </div>
                          </div>

                            <div class="col-md-6">
                              <div class="">
                                <div class="col d-flex">
                                  <div class="col-sm-5">
                                      <label for="inputCity">City</label>
                                      <input type="text" class="form-control form-control-sm" id="inputCity" name="city" value="">
                                    </div>
                                    <div class="col-sm-4">
                                      <label for="inputState">State</label>
                                      <select id="inputState" class="form-control form-control-sm" id="inputState" name="provinces" value="">
                                        <option selected>Choose...</option>
                                        <option>...</option>
                                      </select>
                                    </div>
                                    <div class="col-sm-3">
                                      <label for="inputZip">Zip</label>
                                      <input type="text" class="form-control form-control-sm" id="inputZip" name="zip_code" value="">
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                                <label for="email">Email</label>
                                <input  class="form-control form-control-sm" placeholder="Email" type="text" id="email" name="email" value="" required ="">
                              </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col">
                                  <label for="sex">Gender</label><br>
                                  <label for="male">Male</label>
                                  <input type="radio" id="male" name="sex" value="Male">
                                  <label for="female">Female</label>
                                  <input type="radio" id="female" name="sex" value="Female" >
                                </div>
                                <div class="col">
                                  <label for="date_of_birth">Date of Birth</label>
                                  <div class="col-md-12">
                                    <input class="form-control form-control-md" type="date" id="date_of_birth" name="date_of_birth" value="" required ="">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="contact_no">Contact No.</label>
                                  <input class="form-control form-control-sm" type="text" id="contact_no" name="contact_no" value="" required ="">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col">
                                  <label for="has_pet">Do Tenant own a pet?</label><br>
                                  <input type="radio" id="has_pet" name="has_pet" value="yes">
                                  <label for="yes">Yes</label>
                                  <input type="radio" id="has_pet" name="has_pet" value="no">
                                  <label for="no">No</label>
                                </div>
                                <div class="col">
                                  <label for="number_of_pets">No. of Pets</label>
                                  <div class="col-sm-12">
                                    <input class="form-control form-control-sm" type="number" name="number_of_pets" value="" required ="">
                                  </div>
                                </div>
                                <div class="col">
                                  <label for="type_of_pet">Pet Type:</label>
                                  <div class="col-sm-12">
                                  <input class="form-control form-control-md" type="text" id="type_of_pet" name="type_of_pet" value="" required ="">
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="relationship_status">Relationship Status</label>
                                  <select class="form-control form-control-sm" id="relationship_status" name="relationship_status" required>
                                    <option value="None">--Select--</option>
                                    <option value="single">Single</option>
                                    <option value="in a relationship">In a relationship</option>
                                    <option value="married">Married</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="is_smoking">Do Tenant Smoke?</label><br>
                                  <input type="radio" id="is_smoking" name="is_smoking" value="yes">
                                  <label for="yes">Yes</label>
                                  <input type="radio" id="is_smoking" name="is_smoking" value="no">
                                  <label for="no">No</label>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="type_of_household">Type of Household</label>
                                  <select class="form-control form-control-sm" id="type_of_household" name="type_of_household" required>
                                  <option value="None">--Select--</option>
                                    <option value="one person">One Person</option>
                                    <option value="couple">Couple</option>
                                    <option value="single parent">Single Parent</option>
                                    <option value="family">Family</option>
                                    <option value="extended family">Extended Family</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group-row">
                                <div class="col">
                                  <label for="has_vehicle">Please check if tenant own any of the vehicles:</label><br>
                                  <input type="checkbox" name="vehicle_type[]" value="car">Car<br>
                                  <input type="checkbox" name="vehicle_type[]" value="motorcycle">Motorcycle<br>
                                  <input type="checkbox" name="vehicle_type[]" value="others">Others<br>
                                  <div class="d-flex col-sm-12">
                                    <label for="other_vehicle" hidden>If other, please specify:</label><br>
                                    <input class="form-control form-control-sm" type="text" name="other_vehicle_type" placeholder="" style="display:none;" value="" required =""><br>
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
                                    <textarea class="form-control form-control-lg" id="occupants" name="occupants" cols="100" rows="5"  value="" required =""></textarea>
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
                                  <input class="form-control form-control-sm" type="text" id="co_fname" name="co_fname" value="" required ="">
                                </div>
                              </div>
                              <div class="form-group-row w-50">
                                <div class="col">
                                  <label for="co_email">Email</label>
                                  <input class="form-control form-control-sm" type="email" id="co_email" name="co_email" value="" required ="">
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12 d-flex">
                              <div class="form-group-row w-50">
                                  <div class="col">
                                    <label for="co_lname">Last Name</label>
                                    <input class="form-control form-control-sm" type="text" id="co_lname" name="co_lname" value="" required ="">
                                  </div>
                              </div>
                              <div class="form-group-row w-50">
                                <div class="col"> 
                                  <label for="co_num">Contact No.</label>
                                  <input class="form-control form-control-sm" type="text" id="co_num" name="co_num" value="" required ="">
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
                                  <input class="form-control form-control-sm" type="text" id="emergency_fname" name="emergency_fname" value="" required ="">
                                </div>
                              </div>
                              <div class="form-group-row w-50">
                                <div class="col">
                                  <label for="emergency_num">Contact No.</label>
                                  <input class="form-control form-control-sm" type="text" id="emergency_num" name="emergency_num" value="" required ="">
                                </div>
                              </div>
                            </div>
                          <div class="ps-6">
                            <input type="submit" class="btn btn-success btn-sm" value="Save Tenant" name="save" id="save">
                          </div>
                      </div>
                    </form> 
                  </div>
                </div>
            </div>
            </div>
        </div>

        <script>

            document.getElementById("set_to_primary").addEventListener("click", function(){
            document.getElementById("status").value = "Primary";
            });

            // Script to show/hide "other_vehicle_type" input field
            var vehicleTypeCheckboxes = document.querySelectorAll('input[name="vehicle_type[]"]');
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
