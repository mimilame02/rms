<?php
  require_once '../tools/functions.php';
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
    //if the above code is false then html below will be displayed
    if(isset($_POST['save'])){

      // sanitize user inputs
      $first_name = htmlentities($_POST['first_name']);
      $last_name = htmlentities($_POST['last_name']);
      $email = htmlentities($_POST['email']);
      $contact_no = htmlentities($_POST['contact_no']);
      $address = htmlentities($_POST['address']);
      $city = htmlentities($_POST['city']);
      $province = htmlentities($_POST['province']);
      $zip_code = htmlentities($_POST['zip_code']);
      $identification_document = htmlentities($_FILES['identification_document']['name']);
      $emergency_contact_person = htmlentities($_POST['emergency_contact_person']);
      $emergency_contact_number = htmlentities($_POST['emergency_contact_number']);
      
      // attempt insert query execution
      $sql = "INSERT INTO `landlord`(`first_name`, `last_name`, `email`, `contact_no`, `address`, `city`, `province`, `zip_code`, `identification_document`, `emergency_contact_person`, `emergency_contact_number`) 
      VALUES ('$first_name', '$last_name', '$email', '$contact_no', '$address', '$city', '$province', '$zip_code', '$identification_document', '$emergency_contact_person', '$emergency_contact_number')";
      $result = mysqli_query($conn, $sql);
      
      if ($result) {
        header("Location: landlords.php");
        exit;
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    }

    require_once '../tools/variables.php';
    $page_title = 'RMS | Add Landlord';
    $landlord = 'active';

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
        <h3 class="font-weight-bolder">ADD LANDLORD</h3> 
      </div>
      <div class="add-page-container">
        <div class="col-md-2 d-flex justify-align-between float-right">
          <a href="landlords.php" class='bx bx-caret-left'>Back</a>
        </div>
      </div>
    </div>
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title fw-bolder">Landlord Details</h4>
            <form class="form-sample">
              <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="first_name">First Name</label>
                        <input  class="form-control form-control-sm " placeholder="First name" type="text" id="first_name" name="first_name" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                      <label for="address">Address</label>
                        <input class="form-control form-control-sm" placeholder="House No., Building No."  type="text" id="address" name="address" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="last_name">Last Name</label>
                        <input class="form-control form-control-sm" placeholder="Last name" type="text" id="last_name" name="last_name" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                      <div class="">
                        <div class="col d-flex">
                          <div class="col-sm-5">
                              <label for="city">City</label>
                              <input type="text" class="form-control form-control-sm" id="city" name="city">
                            </div>
                            <div class="col-sm-4">
                              <label for="province">Province</label>
                              <select id="province" class="form-control form-control-sm" id="province" name="province">
                                <option selected>Choose...</option>
                                <option>...</option>
                              </select>
                            </div>
                            <div class="col-sm-3">
                              <label for="zip_code">Zip</label>
                              <input type="text" class="form-control form-control-sm" id="zip_code" name="zip_code">
                            </div>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="email">Email</label>
                        <input  class="form-control form-control-sm" placeholder="Email" type="text" id="email" name="email" required>
                      </div>
                      <div class="col">
                        <label for="contact_no">Contact No.</label>
                        <input class="form-control form-control-sm" type="text" id="contact_no" name="contact_no" required>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row w-100">
                        <div class="col">
                          <label for="identification_document">Identification Document</label>
                          <input type="file" class="form-control form-control-sm" name="identification_document" id="identification_document">
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
                          <label for="emergency_contact_person">Full Name</label>
                          <input class="form-control form-control-sm" type="text" id="emergency_contact_person" name="emergency_contact_person" required>
                        </div>
                      </div>
                      <div class="form-group-row w-50">
                        <div class="col">
                          <label for="emergency_contact_number">Contact No.</label>
                          <input class="form-control form-control-sm" type="text" id="emergency_contact_number" name="emergency_contact_number" required>
                        </div>
                      </div>
                    </div>
                  <div class="ps-6">
                    <input type="submit" class="btn btn-success btn-sm" value="Save Landlord" name="save" id="save">
                  </div>

              </div>
            </form> 
          </div>
        </div>
    </div>
  </div>
</div>

