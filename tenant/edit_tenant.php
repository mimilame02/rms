<?php
  require_once '../tools/functions.php';
  require_once '../classes/tenants.class.php';
  //require_once '../includes/dbconfig.php';

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
    $account_obj = new Tenant;
    if(isset($_POST['save'])){
      //sanitize user inputs
      $account_obj->id = $_POST['tenant-id'];
      $account_obj->first_name = htmlentities($_POST['first_name']);
      $account_obj->middle_name = htmlentities($_POST['middle_name']);
      $account_obj->last_name = htmlentities($_POST['last_name']);
      $account_obj->email = htmlentities($_POST['email']);
      $account_obj->contact_no = htmlentities($_POST['contact_no']);
      $account_obj->relationship_status = htmlentities($_POST['relationship_status']);
      $account_obj->type_of_household = htmlentities($_POST['type_of_household']);
      $account_obj->previous_address = htmlentities($_POST['previous_address']);
      $account_obj->region = htmlentities($_POST['region']);
      $account_obj->provinces = htmlentities($_POST['provinces']);
      $account_obj->city = htmlentities($_POST['city']);
      $account_obj->sex = $_POST['sex'];
      $account_obj->date_of_birth = htmlentities($_POST['date_of_birth']);
      if (isset($_POST['has_pet'])) {
        $has_pet = $_POST['has_pet'];
        if ($has_pet === 'No') {
          // If the user selects "No" for owning a pet, set the values of number_of_pets and type_of_pet to "0" and "None" respectively
          $account_obj->has_pet = 'No';
          $account_obj->number_of_pets = 0;
          $account_obj->type_of_pet = 'None';
        } else {
          $account_obj->has_pet = 'Yes';
          $account_obj->number_of_pets = htmlentities($_POST['number_of_pets']);
          $account_obj->type_of_pet = htmlentities($_POST['type_of_pet']);
        }
      }
      if (isset($_POST['is_smoking'])) {
        $account_obj->is_smoking = $_POST['is_smoking'];
      }
      if (isset($_POST['has_vehicle'])) {
        $account_obj->has_vehicle = $_POST['has_vehicle'];
      }
      $account_obj->vehicle_specification = htmlentities($_POST['vehicle_specification']);
      $account_obj->occupants = htmlentities($_POST['occupants']);
      $account_obj->spouse_first_name = htmlentities($_POST['spouse_first_name']);
      $account_obj->spouse_last_name = htmlentities($_POST['spouse_last_name']);
      $account_obj->spouse_email = htmlentities($_POST['spouse_email']);
      $account_obj->spouse_num = htmlentities($_POST['spouse_num']);
      $account_obj->co_applicant_first_name = htmlentities($_POST['co_applicant_first_name']);
      $account_obj->co_applicant_last_name = htmlentities($_POST['co_applicant_mid_name']);
      $account_obj->co_applicant_last_name = htmlentities($_POST['co_applicant_last_name']);
      $account_obj->co_applicant_email = htmlentities($_POST['co_applicant_email']);
      $account_obj->co_applicant_contact_no = htmlentities($_POST['co_applicant_contact_no']);
      $account_obj->emergency_contact_person = htmlentities($_POST['emergency_contact_person']);
      $account_obj->emergency_contact_number = htmlentities($_POST['emergency_contact_number']);
      if(validate_tenants($_POST)){
        if($account_obj->tenants_edit()){  
            //redirect user to landing page after saving
            exit; // always exit after redirecting
        } else {
            // handle product add error
            $msg = "Error adding landlord";
        }
      }
      }else{
        if ($account_obj->tenant_fetch($_GET['id'])){
          $data = $account_obj->tenant_fetch($_GET['id']);
          $account_obj->id = $data['id'];
          $account_obj->first_name = $data['first_name'];
          $account_obj->middle_name = $data['middle_name'];
          $account_obj->last_name = $data['last_name'];
          $account_obj->email = $data['email'];
          $account_obj->contact_no = $data['contact_no'];
          $account_obj->relationship_status = $data['relationship_status'];
          $account_obj->type_of_household = $data['type_of_household'];
          $account_obj->previous_address = $data['previous_address'];
          $account_obj->region = $data['region'];
          $account_obj->provinces = $data['provinces'];
          $account_obj->city = $data['city'];
          $account_obj->sex = $data['sex'];
          $account_obj->date_of_birth = $data['date_of_birth'];
          $account_obj->has_pet = $data['has_pet'];
          $account_obj->number_of_pets = $data['number_of_pets'];
          $account_obj->type_of_pet = $data['type_of_pet'];
          $account_obj->is_smoking = $data['is_smoking'];
          $account_obj->has_vehicle = $data['has_vehicle'];
          $account_obj->vehicle_specification = $data['vehicle_specification'];
          $account_obj->occupants = $data['occupants'];
          $account_obj->spouse_first_name = $data['spouse_first_name'];
          $account_obj->spouse_last_name = $data['spouse_last_name'];
          $account_obj->spouse_email = $data['spouse_email'];
          $account_obj->spouse_num = $data['spouse_num'];
          $account_obj->co_applicant_first_name = $data['co_applicant_first_name'];
          $account_obj->co_applicant_last_name = $data['co_applicant_mid_name'];
          $account_obj->co_applicant_last_name = $data['co_applicant_last_name'];
          $account_obj->co_applicant_email = $data['co_applicant_email'];
          $account_obj->co_applicant_contact_no = $data['co_applicant_contact_no'];
          $account_obj->emergency_contact_person = $data['emergency_contact_person'];
          $account_obj->emergency_contact_number = $data['emergency_contact_number'];

          }
        }
        require_once '../tools/variables.php';
      $page_title = 'RMS | Edit Tenant';
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
              <h3 class="font-weight-bolder">EDIT TENANT</h3> 
            </div>
            <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="tenants.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
          </div>
          <form action="edit_tenant.php" method="post">
            <div class="card">
              <div class="card-body">
                <h3 class="table-title fw-bolder pb-4">Tenant Details</h3>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                      <input type="text" hidden name="tenant-id" value="<?php echo $account_obj->id ?>">
                        <label for="first_name">First Name                                
                            <?php
                            if(isset($_POST['save']) && !validate_first_name($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                        </label>
                        <input required class="form-control form-control-sm " placeholder="First name" type="text" id="first_name" name="first_name" value="<?php if(isset($_POST['first_name'])) { echo $_POST['first_name']; } else { echo $account_obj->first_name; }?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                      <label for="middle_name">Middle Name
                      <?php
                            if(isset($_POST['save']) && !validate_middle_name($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                      </label>
                      <input class="form-control form-control-sm" type="text" id="middle_name" name="middle_name" value="<?php if(isset($_POST['middle_name'])) { echo $_POST['middle_name']; } else { echo $account_obj->middle_name; }?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                            <label for="last_name">Last Name
                              <?php
                              if(isset($_POST['save']) && !validate_last_name($_POST)){
                              ?>
                                <span class="text-danger">*</span>
                              <?php
                                  }
                              ?>
                            </label>
                          <input required class="form-control form-control-sm" placeholder="Last name" type="text" id="last_name" name="last_name" value="<?php if(isset($_POST['last_name'])) { echo $_POST['last_name']; } else { echo $account_obj->last_name; }?>">
                        </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="date_of_birth">Date of Birth
                        <?php
                            if(isset($_POST['save']) && !validate_date_birth($_POST)){
                            ?>
                              <span class="text-danger">*  Age 18 above</span>
                            <?php
                                }
                            ?>
                        </label>
                        <input class="form-control form-control-sm" type="date" id="date_of_birth" name="date_of_birth" value="<?php if(isset($_POST['date_of_birth'])) { echo $_POST['date_of_birth']; } else { echo $account_obj->date_of_birth; }?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <label for="email">Email</label>
                          <input required class="form-control form-control-sm" placeholder="Email" type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } else { echo $account_obj->email; }?>">
                        </div>
                      </div>
                      <?php
                      if(isset($_POST['save']) && !validate_email($_POST)){
                      ?>
                        <span class="text-danger">*</span>
                      <?php
                          }
                      ?>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group-row">
                        <div class="col">
                          <label for="contact_no">Contact No.
                            <?php
                              if(isset($_POST['save']) && !validate_contact_num($_POST)){
                              ?>
                                <span class="text-danger">*</span>
                              <?php
                                  }
                              ?>
                          </label>
                          <input required class="form-control form-control-sm" type="text" id="contact_no" name="contact_no" maxlength="12" value="<?php if(isset($_POST['contact_no'])) { echo $_POST['contact_no']; } else { echo $account_obj->contact_no; }?>">
                        </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                      <label for="previous_address">Previous Address
                      <?php
                            if(isset($_POST['save']) && !validate_prev_address($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                      </label>
                        <input required class="form-control form-control-sm" placeholder="House No., Building No."  type="text" id="previous_address" name="previous_address" value="<?php if(isset($_POST['previous_address'])) { echo $_POST['previous_address']; } else { echo $account_obj->previous_address; }?>">
                      </div>
                    </div>
                  </div>
                  <div class="col d-flex">
                    <div class="col-sm-4">
                      <label for="region">Region<span class="text-muted"></span>
                      <?php
                            if(isset($_POST['save']) && !validate_region($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?></label>
                      <select type="text" class="form-control form-control-sm" name="region" id="region" placeholder=""> 
                      <option value="None" <?php if(isset($_POST['region'])) { if ($_POST['region'] == 'None') echo ' selected="selected"'; } elseif ($account_obj->region == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                        <?php
                              require_once '../classes/reference.class.php';
                              $ref_obj = new Reference();
                              $ref = $ref_obj->get_region();
                              foreach($ref as $row){
                          ?>
                                  <option value="<?=$row['regCode']?>" <?php if(isset($_POST['region'])) { if ($_POST['region'] == $row['regCode']) echo ' selected="selected"'; } elseif ($account_obj->region == $row['regCode']) echo ' selected="selected"'; ?>><?=$row['regDesc']?></option>
                          <?php
                              }
                          ?>
                      </select>
                    </div>
                    <div class="col-sm-4 pl-0">
                      <label for="provinces">Provinces<span class="text-muted"></span>
                      <?php
                            if(isset($_POST['save']) && !validate_prov($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?></label>
                      <select type="text" id="provinces" class="form-control form-control-sm" name="provinces">
                      <option value="None" <?php if(isset($_POST['provinces'])) { if ($_POST['provinces'] == 'None') echo ' selected="selected"'; } elseif ($account_obj->provinces == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                      <?php
                              require_once '../classes/reference.class.php';
                              $ref_obj = new Reference();
                              $ref = $ref_obj->get_province($regCode);
                              foreach($ref as $row){
                          ?>
                                  <option value="<?=$row['provCode']?>" <?php if(isset($_POST['provinces'])) { if ($_POST['provinces'] == $row['provCode']) echo ' selected="selected"'; } elseif ($account_obj->provinces == $row['provCode']) echo ' selected="selected"'; ?>><?=$row['provDesc']?></option>
                          <?php
                              }
                          ?>
                      </select>
                    </div>
                    <div class="col-sm-4 pl-0">
                      <label for="city">City
                      <?php
                            if(isset($_POST['save']) && !validate_city($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                      </label>
                      <select type="text" class="form-control form-control-sm" id="city" name="city">
                      <option value="None" <?php if(isset($_POST['city'])) { if ($_POST['city'] == 'None') echo ' selected="selected"'; } elseif ($account_obj->city == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                      <?php
                          require_once '../classes/reference.class.php';
                          $ref_obj = new Reference();
                          $ref = $ref_obj->get_City($provCode);
                          foreach($ref as $row){
                      ?>
                              <option value="<?=$row['citymunCode']?>" <?php if(isset($_POST['city'])) { if ($_POST['city'] == $row['citymunCode']) echo ' selected="selected"'; } elseif ($account_obj->city == $row['citymunCode']) echo ' selected="selected"'; ?>><?=$row['citymunDesc']?></option>
                      <?php
                          }
                          ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                          <label for="sex" class="form-label">Sex<span class="text-muted"></span>
                          <?php
                            if(isset($_POST['save']) && !validate_sex($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?></label>
                          <select class="form-control" id="sex" placeholder="" name="sex">
                              <option value="None">--Select--</option>
                              <option value="Male"<?php if(isset($_POST['sex'])) { if ($_POST['sex'] == 'Male') echo ' selected="selected"'; } elseif ($account_obj->sex == 'Male') echo 'selected="selected"'; ?>>Male</option>
                              <option value="Female"<?php if(isset($_POST['sex'])) { if ($_POST['sex'] == 'Female') echo ' selected="selected"'; } elseif ($account_obj->sex == 'Female') echo 'selected="selected"'; ?>>Female</option>
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="col d-flex">
                    <div class="col-sm-4 pr-1 fs2">
                      <label for="has_pet">Do Tenant own a pet?
                      <?php
                            if(isset($_POST['save']) && !validate_has_pet($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                      </label><br>
                      <input type="radio" id="has_pet" name="has_pet" value="Yes"<?php if(isset($_POST['has_pet'])) { if ($_POST['has_pet'] == 'Yes') echo ' checked'; } elseif ($account_obj->has_pet == 'Yes') echo ' checked'; ?>>
                      <label for="has_pet">Yes</label>
                      <input type="radio" id="has_pet" name="has_pet" value="No"<?php if(isset($_POST['has_pet'])) { if ($_POST['has_pet'] == 'No') echo ' checked'; } elseif ($account_obj->has_pet == 'No') echo ' checked'; ?>>
                      <label for="has_pet">No</label>
                    </div>
                    <div class="col px-1 fs1 fs2">
                      <label for="number_of_pets">No. of Pets</label>
                      <input class="form-control form-control-sm fs1" type="number" name="number_of_pets" value="<?php if(isset($_POST['number_of_pets'])) { echo $_POST['number_of_pets']; } else { echo $account_obj->number_of_pets; }?>">
                    </div>
                    <div class="col-md-4 pl-1 fs1 fs2">
                      <label for="type_of_pet">Pet Type:
                        <?php
                            if(isset($_POST['save']) && !validate_pet_type($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                      </label>
                      <input class="form-control form-control-sm fs1" type="text" id="type_of_pet" name="type_of_pet" value="<?php if(isset($_POST['type_of_pet'])) { echo $_POST['type_of_pet']; } else { echo $account_obj->type_of_pet; }?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="relationship_status">Civil Status
                        <?php
                            if(isset($_POST['save']) && !validate_civil_status($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                        </label>
                        <select class="form-control form-control-sm" id="relationship_status" name="relationship_status" >
                          <option name="relationship_status" value="None" <?php if(isset($_POST['relationship_status'])) { if ($_POST['relationship_status'] == 'None') echo ' selected="selected"'; } elseif ($account_obj->relationship_status == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                          <option name="relationship_status" value="single" <?php if(isset($_POST['relationship_status'])) { if ($_POST['relationship_status'] == 'single') echo ' selected="selected"'; } elseif ($account_obj->relationship_status == 'single') echo ' selected="selected"'; ?>>Single</option>
                          <option name="relationship_status" value="in a relationship" <?php if(isset($_POST['relationship_status'])) { if ($_POST['relationship_status'] == 'in a relationship') echo ' selected="selected"'; } elseif ($account_obj->relationship_status == 'in a relationship') echo ' selected="selected"'; ?>>In a relationship</option>
                          <option name="relationship_status" value="married" <?php if(isset($_POST['relationship_status'])) { if ($_POST['relationship_status'] == 'married') echo ' selected="selected"'; } elseif ($account_obj->relationship_status == 'married') echo ' selected="selected"'; ?>>Married</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="is_smoking">Do Tenant Smoke?
                        <?php
                            if(isset($_POST['save']) && !validate_is_smoking($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                        </label><br>
                        <input type="radio" id="is_smoking" name="is_smoking" value="Yes" <?php if(isset($_POST['is_smoking'])) { if ($_POST['is_smoking'] == 'Yes') echo ' checked'; } elseif ($account_obj->is_smoking == 'Yes') echo 'checked'; ?>>
                        <label for="yes">Yes</label>
                        <input type="radio" id="is_smoking" name="is_smoking" value="No" <?php if(isset($_POST['is_smoking'])) { if ($_POST['is_smoking'] == 'No') echo ' checked'; } elseif ($account_obj->is_smoking == 'No') echo 'checked'; ?>>
                        <label for="no">No</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="type_of_household">Type of Household
                        <?php
                            if(isset($_POST['save']) && !validate_house($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                        </label>
                        <select class="form-control form-control-sm" id="type_of_household" name="type_of_household" >
                          <option name="type_of_household" value="None" <?php if(isset($_POST['type_of_household'])) { if ($_POST['type_of_household'] == 'None') echo ' selected="selected"'; } elseif ($account_obj->type_of_household == 'None') echo ' selected="selected"'; ?>>--Select--</option>
                          <option name="type_of_household" value="one person" <?php if(isset($_POST['type_of_household'])) { if ($_POST['type_of_household'] == 'one person') echo ' selected="selected"'; } elseif ($account_obj->type_of_household == 'one person') echo ' selected="selected"'; ?> >One Person</option>
                          <option name="type_of_household" value="couple" <?php if(isset($_POST['type_of_household'])) { if ($_POST['type_of_household'] == 'couple') echo ' selected="selected"'; } elseif ($account_obj->type_of_household == 'couple') echo ' selected="selected"'; ?>>Couple</option>
                          <option name="type_of_household" value="single parent" <?php if(isset($_POST['type_of_household'])) { if ($_POST['type_of_household'] == 'single parent') echo ' selected="selected"'; } elseif ($account_obj->type_of_household == 'single parent') echo ' selected="selected"'; ?>>Single Parent</option>
                          <option name="type_of_household" value="family" <?php if(isset($_POST['type_of_household'])) { if ($_POST['type_of_household'] == 'family') echo ' selected="selected"'; } elseif ($account_obj->type_of_household == 'family') echo ' selected="selected"'; ?>>Family</option>
                          <option name="type_of_household" value="extended family" <?php if(isset($_POST['type_of_household'])) { if ($_POST['type_of_household'] == 'extended family') echo ' selected="selected"'; } elseif ($account_obj->type_of_household == 'extended family') echo ' selected="selected"'; ?>>Extended Family</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="has_vehicle">Please check if tenant own any of the vehicles:</label><br>
                        <input type="checkbox" name="has_vehicle" value="car" <?php if(isset($_POST['has_vehicle'])) { if ($_POST['has_vehicle'] == 'car') echo ' checked'; } elseif ($account_obj->has_vehicle == 'car') echo 'checked'; ?>>Car<br>
                        <input type="checkbox" name="has_vehicle" value="motorcycle" <?php if(isset($_POST['has_vehicle'])) { if ($_POST['has_vehicle'] == 'motorcycle') echo ' checked'; } elseif ($account_obj->has_vehicle == 'motorcycle') echo 'checked'; ?>>Motorcycle<br>
                        <input type="checkbox" name="has_vehicle" value="others" <?php if(isset($_POST['has_vehicle'])) { if ($_POST['has_vehicle'] == 'others') echo ' checked'; } elseif ($account_obj->has_vehicle == 'others') echo 'checked'; ?>>Others<br>
                        <div class="d-flex col-sm-12">
                          <label for="vehicle_specification" hidden>If other, please specify:</label><br>
                          <input class="form-control form-control-sm" type="text" name="vehicle_specification" id="vehicle_specification" style="display:none;" value="<?php if(isset($_POST['vehicle_specification'])) { echo $_POST['vehicle_specification']; } else { echo $account_obj->vehicle_specification; }?>"><br>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="spouse_fields" style="display: none;">
                    <div class="row g-3">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col d-flex">
                          <h3 class="table-title pt-2">Spouse Details</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="spouse_first_name">First Name</label>
                            <input class="form-control form-control-sm" type="text" id="spouse_first_name" name="spouse_first_name" value="<?php if(isset($_POST['spouse_first_name'])) { echo $_POST['spouse_first_name']; } else { echo $account_obj->spouse_first_name; }?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="spouse_first_name">Last Name</label>
                            <input class="form-control form-control-sm" type="text" id="spouse_last_name" name="spouse_last_name" value="<?php if(isset($_POST['spouse_last_name'])) { echo $_POST['spouse_last_name']; } else { echo $account_obj->spouse_last_name; }?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="spouse_email">Email</label>
                            <input class="form-control form-control-sm" type="email" id="spouse_email" name="spouse_email" value="<?php if(isset($_POST['spouse_email'])) { echo $_POST['spouse_email']; } else { echo $account_obj->spouse_email; }?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col"> 
                            <label for="spouse_num">Contact No.</label>
                            <input class="form-control form-control-sm" type="text" id="spouse_num" name="spouse_num" value="<?php if(isset($_POST['spouse_num'])) { echo $_POST['spouse_num']; } else { echo $account_obj->spouse_num; }?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="other_occupants_fields" style="display: none;">
                    <div class="row g-3">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col">
                            <h3 class="table-title">Other Occupants</h3>
                          </div>
                        </div>
                        <div class="form-group-row w-100">
                          <div class="col">
                            <label for="occupants">Full Name/s</label>
                            <textarea class="form-control form-control-lg" id="occupants" name="occupants" cols="100" rows="5" value="<?php if(isset($_POST['occupants'])) { echo $_POST['occupants']; } else { echo $account_obj->occupants; }?>"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="co_applicant_fields" style="display: none;">
                    <div class="row g-3">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col d-flex">
                            <h3 class="table-title pt-2">Co-Applicant Details</h3>
                              <input type="hidden" id="status" name="status" value="Primary">
                              <button class="btn btn-success col-sm-2 ml-2 p-0" type="button" id="set_to_primary"><i class="bx bx-plus btn-icon-prepend"></i> Set to Primary</button>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="co_fname">First Name</label>
                            <input class="form-control form-control-sm" type="text" id="co_applicant_first_name" name="co_applicant_first_name" value="<?php if(isset($_POST['co_applicant_first_name'])) { echo $_POST['co_applicant_first_name']; } else { echo $account_obj->co_applicant_first_name; }?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="co_applicant_mid_name">Middle Name</label>
                            <input class="form-control form-control-sm" type="text" id="co_applicant_mid_name" name="co_applicant_mid_name" value="<?php if(isset($_POST['co_applicant_mid_name'])) { echo $_POST['co_applicant_mid_name']; } else { echo $account_obj->co_applicant_mid_name; }?>" >
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="co_lname">Last Name</label>
                            <input class="form-control form-control-sm" type="text" id="co_applicant_last_name" name="co_applicant_last_name" value="<?php if(isset($_POST['co_applicant_last_name'])) { echo $_POST['co_applicant_last_name']; } else { echo $account_obj->co_applicant_last_name; }?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="co_email">Email</label>
                            <input class="form-control form-control-sm" type="email" id="co_applicant_email" name="co_applicant_email" value="<?php if(isset($_POST['co_applicant_email'])) { echo $_POST['co_applicant_email']; } else { echo $account_obj->co_applicant_email; }?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col"> 
                            <label for="co_applicant_contact_no">Contact No.</label>
                            <input class="form-control form-control-sm" type="text" id="co_applicant_contact_no" name="co_applicant_contact_no" value="<?php if(isset($_POST['co_applicant_contact_no'])) { echo $_POST['co_applicant_contact_no']; } else { echo $account_obj->co_applicant_contact_no; }?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <hr>
                    <div class="form-group-row">
                      <div class="col">
                        <h3 class="table-title">Emergency Contact Person Details</h3>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 d-flex">
                    <div class="form-group-row w-50">
                      <div class="col">
                        <label for="emergency_contact_person">Full Name
                        <?php
                            if(isset($_POST['save']) && !validate_full_name($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                        </label>
                        <input class="form-control form-control-sm" type="text" id="emergency_contact_person" name="emergency_contact_person" value="<?php if(isset($_POST['emergency_contact_person'])) { echo $_POST['emergency_contact_person']; } else { echo $account_obj->emergency_contact_person; }?>">
                      </div>
                    </div>
                    <div class="form-group-row w-50">
                      <div class="col">
                        <label for="emergency_contact_number">Contact No.
                        <?php
                            if(isset($_POST['save']) && !validate_econtact_no($_POST)){
                            ?>
                              <span class="text-danger">*</span>
                            <?php
                                }
                            ?>
                        </label>
                        <input class="form-control form-control-sm" type="text" id="emergency_contact_number" name="emergency_contact_number" value="<?php if(isset($_POST['emergency_contact_number'])) { echo $_POST['emergency_contact_number']; } else { echo $account_obj->emergency_contact_number; }?>">
                      </div>
                    </div>
                  </div>
                  <div class="ps-6">
                    <input type="submit" class="btn btn-success btn-sm" value="Save Tenant" name="save" id="save">
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
                      var otherVehicleTypeInput = document.querySelector('input[name="vehicle_specification"]');
                      var otherVehicleTypeLabel = document.querySelector('label[for="vehicle_specification"]');

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

                      var statusDropdown = document.getElementById("relationship_status");
                      var spouseFields = document.getElementById("spouse_fields");

                      statusDropdown.addEventListener("change", function() {
                        if (this.value == "married") {
                          spouseFields.style.display = "block";
                        } else {
                          spouseFields.style.display = "none";
                        }
                      });


                      // Get the "Type of Household" dropdown element
                    var typeOfHousehold = document.getElementById("type_of_household");

                    // Get the other occupants and co-applicant fields
                    var otherOccupantsFields = document.getElementById("other_occupants_fields");
                    var coApplicantFields = document.getElementById("co_applicant_fields");

                    // Add an event listener to the "Type of Household" dropdown
                    typeOfHousehold.addEventListener("change", function() {
                      // Check the selected value of the dropdown
                      var selectedValue = typeOfHousehold.value;
                      
                      // Show/hide the other occupants and co-applicant fields based on the selected value
                      if (selectedValue == "couple" ||selectedValue == "single parent" ||selectedValue == "family" || selectedValue == "extended family") {
                        otherOccupantsFields.style.display = "block";
                        coApplicantFields.style.display = "block";
                      } else {
                        otherOccupantsFields.style.display = "none";
                        coApplicantFields.style.display = "none";
                      }
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
            </script>
          </form>
