<?php
  require_once '../tools/functions.php';
  require_once '../classes/landlords.class.php';
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

    $landlord_obj = new Landlord;
    //if the above code is false then html below will be displayed
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      
      //sanitize user inputs
      $landlord_obj->id = $_POST['landlord-id'];
      $landlord_obj->first_name = htmlentities($_POST['first_name']);
      $landlord_obj->middle_name = htmlentities($_POST['middle_name']);
      $landlord_obj->last_name = htmlentities($_POST['last_name']);
      $landlord_obj->date_of_birth = htmlentities($_POST['date_of_birth']);
      $landlord_obj->email = htmlentities($_POST['email']);
      $landlord_obj->contact_no = htmlentities($_POST['contact_no']);
      $landlord_obj->address = htmlentities($_POST['address']);
      $landlord_obj->region= $_POST['region'];
      $landlord_obj->provinces = $_POST['provinces'];
      $landlord_obj->city = $_POST['city'];
      $landlord_obj->emergency_contact_person = htmlentities($_POST['emergency_contact_person']);
      $landlord_obj->emergency_contact_number = htmlentities($_POST['emergency_contact_number']);

      if (isset($_FILES['identification_document']) && $_FILES['identification_document']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['identification_document']['name'];
        $target = "../img/id/" . basename($image);
    
        if (move_uploaded_file($_FILES['identification_document']['tmp_name'], $target)) {
            $landlord_obj->identification_document = $_FILES['identification_document']['name'];
        } else {
            // handle file upload error
            $msg = "Error uploading file";
        }
      }else{
        $landlord_obj->identification_document = $_POST['id_docu'];;
      }

        // Add product to database
        if(validate_add_landlord($_POST)){
          if ($landlord_obj->landlord_edit()) {
            
            $_SESSION['edited_landlords'] = true;
            header('location: landlords.php?add_success=1');
            exit; // always exit after redirecting
          }
        }
    }else{
      if ($landlord_obj->landlord_fetch($_GET['id'])){ 
          $data = $landlord_obj->landlord_fetch($_GET['id']);
          $landlord_obj->id = $data['id'];
          $landlord_obj->first_name = $data['first_name'];
          $landlord_obj->middle_name = $data['middle_name'];
          $landlord_obj->last_name = $data['last_name'];
          $landlord_obj->date_of_birth = $data['date_of_birth'];
          $landlord_obj->email = $data['email'];
          $landlord_obj->contact_no = $data['contact_no'];
          $landlord_obj->address = $data['address'];
          $landlord_obj->region= $data['region'];
          $landlord_obj->provinces = $data['provinces'];
          $landlord_obj->city = $data['city'];
          $landlord_obj->identification_document = $data['identification_document'];
          $landlord_obj->emergency_contact_person = $data['emergency_contact_person'];
          $landlord_obj->emergency_contact_number = $data['emergency_contact_number'];
      }
    }


    require_once '../tools/variables.php';
    $page_title = 'RMS | Edit Landlord';
    $landlord = 'active';

    require_once '../includes/header.php';
?>
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
        require_once '../includes/sidebar.php';
      ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bolder">EDIT LANDLORD</h3> 
            </div>
            <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="landlords.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
            <form action="edit_landlord.php" method="post" enctype="multipart/form-data" class="needs-validation" id="addLandlord" novalidate>
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-12">
                        <div class="form-group-row">
                          <div class="col">
                            <h3 class="table-title fw-bolder pb-3">Landlord Details</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <input type="text" hidden name="landlord-id" value="<?php echo $landlord_obj->id ?>">
                          <div class="col">
                            <label for="first_name">First Name</label>
                            <input  class="form-control form-control-sm " placeholder="First name" type="text" id="first_name" name="first_name" value="<?php if(isset($_POST['first_name'])) { echo $_POST['first_name']; } else { echo $landlord_obj->first_name; }?>" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })" required>
                            <div class="invalid-feedback">Please provide a valid first name (letters, spaces, and dashes only).</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="middle_name">Middle Name</label>
                            <input  class="form-control form-control-sm " placeholder="Middle name" type="text" id="middle_name" name="middle_name" value="<?php if(isset($_POST['middle_name'])) { echo $_POST['middle_name']; } else { echo $landlord_obj->middle_name; }?>" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                            <div class="invalid-feedback">Please provide a valid middle name (letters, spaces, and dashes only).</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="last_name">Last Name</label>
                            <input class="form-control form-control-sm" placeholder="Last name" type="text" id="last_name" name="last_name" value="<?php if(isset($_POST['last_name'])) { echo $_POST['last_name']; } else { echo $landlord_obj->last_name; }?>" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })" required>
                            <div class="invalid-feedback">Please provide a valid last name (letters, spaces, and dashes only).</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="date_of_birth">Date of Birth</label>
                            <input class="form-control form-control-sm" type="date" id="date_of_birth" name="date_of_birth" value="<?php if(isset($_POST['date_of_birth'])) { echo $_POST['date_of_birth']; } else { echo $landlord_obj->date_of_birth; }?>">
                            <div class="invalid-feedback">Please provide a valid date of birth (age must be 18 or above).</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="email">Email</label>
                            <input  class="form-control form-control-sm" placeholder="Email" type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } else { echo $landlord_obj->email; }?>" required>
                            <div class="invalid-feedback">Please provide a valid email address.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                            <label for="contact_no">Contact No.</label><br>
                            <div class="px-3 row g-3">
                              <input class="form-control form-control-sm"  placeholder="11-digit mobile number" type="text" id="contact_no" name="contact_no" value="<?php if(isset($_POST['contact_no'])) { echo $_POST['contact_no']; } else { echo $landlord_obj->contact_no; }?>" required>
                              <div class="invalid-feedback">Please provide a valid contact number.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group-row">
                          <div class="col">
                          <label for="address">Address</label>
                            <input class="form-control form-control-sm" placeholder="House No., Building No."  type="text" id="address" name="address" value="<?php if(isset($_POST['address'])) { echo $_POST['address']; } else { echo $landlord_obj->address; }?>" onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })" required>
                            <div class="invalid-feedback">Please provide a valid previous address.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6 d-flex">
                        <div class="col-sm-4">
                          <label for="region">Region</label>
                          <select type="text" class="form-control selectpicker" data-live-search="true" data-size="9" data-dropup-auto="false" name="region" id="region" placeholder="" required> 
                            <option value="None">--Select--</option>
                            <?php
                                  require_once '../classes/reference.class.php';
                                  $ref_obj = new Reference();
                                  $ref = $ref_obj->get_region();
                                  foreach($ref as $row){
                              ?>
                                      <option value="<?=$row['regCode']?>" <?php if(isset($_POST['region'])) { if ($_POST['region'] == $row['regCode']) echo ' selected="selected"'; } elseif ($landlord_obj->region == $row['regCode']) echo ' selected="selected"'; ?>><?=$row['regDesc']?></option>
                              <?php
                                  }
                              ?>
                          </select>
                        </div>
                        <div class="col-sm-4 pl-0">
                          <label for="provinces">Provinces</label>
                          <select type="text" id="provinces" class="form-control selectpicker" data-live-search="true" data-size="9" data-dropup-auto="false" name="provinces" required>
                          <option value="None">--Select--</option>
                          <?php
                                  require_once '../classes/reference.class.php';
                                  $ref_obj = new Reference();
                                  $ref = $ref_obj->get_provinced();
                                  foreach($ref as $row){
                              ?>
                                      <option value="<?=$row['provCode']?>" <?php if(isset($_POST['provinces'])) { if ($_POST['provinces'] == $row['provCode']) echo ' selected="selected"'; } elseif ($landlord_obj->provinces == $row['provCode']) echo ' selected="selected"'; ?>><?=$row['provDesc']?></option>
                              <?php
                                  }
                              ?>
                          </select>
                        </div>
                        <div class="col-md-4 pl-0">
                          <label for="city">City</label>
                          <select type="text" class="form-control selectpicker" data-live-search="true" data-size="9" data-dropup-auto="false" id="city" name="city" required>
                          <option value="None">--Select--</option>
                          <?php
                              require_once '../classes/reference.class.php';
                              $ref_obj = new Reference();
                              $ref = $ref_obj->get_Citys();
                              foreach($ref as $row){
                          ?>
                                  <option value="<?=$row['citymunCode']?>" <?php if(isset($_POST['city'])) { if ($_POST['city'] == $row['citymunCode']) echo ' selected="selected"'; } elseif ($landlord_obj->city == $row['citymunCode']) echo ' selected="selected"'; ?>><?=$row['citymunDesc']?></option>
                          <?php
                              }
                              ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group-row w-100">
                          <div class="col">
                            <label for="identification_document">Identification Document</label><br>
                            <div class="image-container float-right" style="width: 47%;">
                              <img id="uploaded-image" src="../img/id/<?php echo isset($landlord_obj->identification_document) ? $landlord_obj->identification_document : 'default.png' ?>" height="250px" width="500px">
                              <?php if (!empty($landlord_obj->identification_document)) { ?>
                              <p class="mt-2 file-name text-break">File name: <?php echo basename($landlord_obj->identification_document); ?></p>
                              <?php } else { ?>
                                <p class="mt-2 ml-2 file-name text-break">No file selected yet</p>
                              <?php } ?>
                            </div>
                            <input class="form-control form-control-sm col-sm-6" type="file" id="identification_document" name="identification_document" value="<?php echo $landlord_obj->identification_document ?>" accept=".jpg,.jpeg,.png">
                            <div class="invalid-feedback">Issued identification card must be in jpg, jpeg or png format only.</div>
                          </div>
                          <input type="text" hidden name="id_docu" value="<?php echo $landlord_obj->identification_document ?>">
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <div class="col">
                          <h3 class="table-title pt-4">Emergency Contact Person Details</h3>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 d-flex">
                      <div class="form-group-row w-50">
                        <div class="col">
                          <label for="emergency_contact_person">Full Name</label>
                          <input class="form-control form-control-sm" type="text" id="emergency_contact_person" name="emergency_contact_person" value="<?php if(isset($_POST['emergency_contact_person'])) { echo $_POST['emergency_contact_person']; } else { echo $landlord_obj->emergency_contact_person; }?>"onkeyup="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); })">
                          <div class="invalid-feedback">Please provide a valid name (letters, spaces, and dashes only).</div>
                        </div>
                      </div>
                      <div class="form-group-row w-50">
                        <div class="col">
                          <label for="emergency_contact_number">Contact No.</label><br>
                          <div class="px-3 row g-3">
                            <input class="form-control form-control-sm" type="text" id="emergency_contact_number" name="emergency_contact_number" value="<?php if(isset($_POST['emergency_contact_number'])) { echo $_POST['emergency_contact_number']; } else { echo $landlord_obj->emergency_contact_number; }?>">
                            <div class="invalid-feedback">Please provide a valid contact number.</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="pt-3">
                      <input type="submit" class="btn btn-success btn-sm" value="Save" name="save" id="save">
                    </div>
                </div>
              </div>
       


<script>
    $(document).ready(function() {
      $('#identification_document').on('change', function() {
        const input = this;
        
        if (input.files && input.files[0]) {
          const reader = new FileReader();

          reader.onload = function(e) {
            $('#uploaded-image').attr('src', e.target.result);
            $('.image-container').show();
            $('#identification_document').addClass('col-md-6');
            $('.image-container').css('display', 'block');

            // Set the file name in the <p> tag
            const fileName = input.files[0].name;
            $('.file-name').text('File name: ' + fileName);
          };

          reader.readAsDataURL(input.files[0]);
        } else {
          $('#uploaded-image').attr('src', '../img/id/default.png');
          $('.image-container').hide();
          $('#identification_document').removeClass('col-md-6');
          $('.image-container').css('display', 'none');

          // Clear the file name in the <p> tag
          $('.file-name').empty();
        }
      });
    });


</script>

<script>
  $(document).ready(function() {
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
            $('#provinces').selectpicker('refresh'); // Add this line
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
            $('#city').selectpicker('refresh'); // Add this line
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
          }  
        });
      });
    });
</script>

<script>
    var contactInput = document.querySelector("#contact_no");
    var emergencyContactInput = document.querySelector("#emergency_contact_number");

    const contactIti = getItiInstance(contactInput);
    const emergencyContactIti = getItiInstance(emergencyContactInput);

    // Set the formatted number as the input value
    contactInput.value = contactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    emergencyContactInput.value = emergencyContactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);

    function getItiInstance(inputElement) {
      return window.intlTelInput(inputElement, {
        separateDialCode: true,
        initialCountry: "ph",
        geoIpLookup: function (success, failure) {
          $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
            var countryCode = (resp && resp.country) ? resp.country : "ph";
            success(countryCode);
          });
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.21/js/utils.js",
      });
    }
    
</script>

<script>
  const form = document.getElementById('addLandlord');
  const firstNameInput = document.getElementById('first_name');
  const middleNameInput = document.getElementById('middle_name');
  const lastNameInput = document.getElementById('last_name');
  const dateOfBirthInput = document.getElementById('date_of_birth');
  const addressInput = document.getElementById('address');
  const emailInput = document.getElementById('email');

  const regionSelect = document.getElementById('region');
  const provinceSelect = document.getElementById('provinces');
  const citySelect = document.getElementById('city');
  const emergencyFNameInput = document.getElementById('emergency_contact_person');
  const idSelect = document.getElementById('identification_document');

</script>

<script>
  function validateName(name) {
    const namePattern = /^[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ-]+$/;
    return namePattern.test(name);
  }
  function validateFName(fname) {
    const namePattern = /^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/;
    return namePattern.test(fname);
  }
  function validateAddress(inputValue) {
    // Check if the input contains only letters and digits using a regular expression
    return /^[0-9]*\s*[a-zA-Z0-9\s,.'()\[\]`{|}~-]+$/.test(inputValue);
  }
  function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9.!#$%&’()*+/=?^_`{|}~\[\]-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return emailPattern.test(email);
  }
  function validateDateOfBirth(dateOfBirth) {
    const currentDate = new Date();
    const dob = new Date(dateOfBirth);
    const ageDifference = currentDate - dob;
    const ageDate = new Date(ageDifference);
    const age = Math.abs(ageDate.getUTCFullYear() - 1970);
    return age >= 18;
  }
  function validateSelect(value) {
    return value !== ""; // Check if a value has been selected
  }
  function validatePhone(itiInstance) {
    return itiInstance.isValidNumber();
  }
  function updateInvalidEmailFeedback(inputElement) {
    const feedbackElement = inputElement.parentNode.querySelector('.invalid-feedback');

    if (!validateEmail(inputElement.value)) {
      feedbackElement.innerHTML = "Please provide a valid email address.";
    } else {
      feedbackElement.innerHTML = "";
    }
  }
  function validateIdentificationFileType(file) {
    const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i; // Only allow jpg, jpeg, and png files
    return allowedExtensions.test(file.name);
  }
  function updateValidInputClass(input, isValid) {
    if (isValid) {
      input.classList.add('is-valid');
      input.classList.remove('is-invalid');
    } else {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
    }
  }

</script>

<script>

  firstNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  middleNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  lastNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateName(this.value));
  });

  emergencyFNameInput.addEventListener('input', function () {
    updateValidInputClass(this, validateFName(this.value));
  });

  dateOfBirthInput.addEventListener('input', function () {
    updateValidInputClass(this, validateDateOfBirth(this.value));
  });

  contactInput.addEventListener('input', function () {
    updateValidInputClass(this, validatePhone(contactIti));
    contactInput.value = contactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
  });

  emergencyContactInput.addEventListener('input', function () {
    updateValidInputClass(this, validatePhone(emergencyContactIti));
    emergencyContactInput.value = emergencyContactIti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
  });

  addressInput.addEventListener('input', function () {
    updateValidInputClass(this, validateAddress(this.value))
  });

  emailInput.addEventListener('input', function() {
    updateValidInputClass(this, validateEmail(this.value));
    updateInvalidEmailFeedback(emailInput);
  });

  regionSelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  provinceSelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  citySelect.addEventListener('change', function () {
    updateValidInputClass(this, validateSelect(this.value));
  });

  idSelect.addEventListener('input', function() {
    const file = this.files[0];
    const isValid = validateIdentificationFileType(file);
    updateValidInputClass(this, isValid);
  });

</script>

<script>
  function validateForm() {
    let isValid = true;

    if (!validateName(firstNameInput.value)) {
      firstNameInput.classList.add('is-invalid');
      isValid = false;
    } else {
      firstNameInput.classList.remove('is-invalid');
    }

    if (!validateName(lastNameInput.value)) {
      lastNameInput.classList.add('is-invalid');
      isValid = false;
    } else {
      lastNameInput.classList.remove('is-invalid');
    }

    if (!validateDateOfBirth(dateOfBirthInput.value)) {
      dateOfBirthInput.classList.add('is-invalid');
      isValid = false;
    } else {
      dateOfBirthInput.classList.remove('is-invalid');
    }

    if (!validatePhone(contactIti)) {
      contactInput.classList.add('is-invalid');
      isValid = false;
    } else {
      contactInput.classList.remove('is-invalid');
    }

    if (!validatePhone(emergencyContactIti)) {
      emergencyContactInput.classList.add('is-invalid');
      isValid = false;
    } else {
      emergencyContactInput.classList.remove('is-invalid');
    }
    if (!validateAddress(addressInput.value)) {
      addressInput.classList.add('is-invalid');
      isValid = false;
    } else {
      addressInput.classList.remove('is-invalid');
    }
    if (!validateEmail(emailInput.value)) {
      emailInput.classList.add('is-invalid');
      updateInvalidEmailFeedback(emailInput);
      isValid = false;
    } else {
      emailInput.classList.remove('is-invalid');
    }

    if (!validateSelect(regionSelect.value)) {
      regionSelect.classList.add('is-invalid');
      isValid = false;
    } else {
      regionSelect.classList.remove('is-invalid');
    }

    if (!validateSelect(provinceSelect.value)) {
      provinceSelect.classList.add('is-invalid');
      isValid = false;
    } else {
      provinceSelect.classList.remove('is-invalid');
    }

    if (!validateSelect(citySelect.value)) {
      citySelect.classList.add('is-invalid');
      isValid = false;
    } else {
      citySelect.classList.remove('is-invalid');
    }
    if (!validateFName(emergencyFNameInput.value)) {
      emergencyFNameInput.classList.add('is-invalid');
      isValid = false;
    } else {
      emergencyFNameInput.classList.remove('is-invalid');
    }


    return isValid;
  }
</script>

<script>
  document.getElementById('save').addEventListener('click', function (event) {
    event.preventDefault();

    if (validateForm()) {
      Swal.fire({
        title: 'Are you sure you want to save the record?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Save'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // submit the form if the user confirms
        }
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please fix the errors in the form!'
      });
    }
  });

</script>