<?php
    //resume session here to fetch session values
    session_start();
    require_once '../includes/dbconfig.php';
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
        header('location: ../login/login.php');  
    }
    require_once '../includes/header.php';
    require_once '../tools/variables.php';
    $page_title = 'RMS | Manage Users';
    $manage_user = 'active';
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
        <h3 class="font-weight-bolder">MANAGE USERS</h3> 
      </div>
      <div class="add-tenant-container">
      <?php
                    if($_SESSION['user_type'] == 'admin'){     
                ?>
     <button type="button" class="btn btn-success btn-icon-text float-right" data-toggle="modal" data-target="#manage_userModal">
            Add New User
          </button>
          <?php
                    }
                ?>
      </div>
    </div>
    <div class="row mt-4">
    <div class="card">
                <div class="card-body">
                  <div class="table-responsive pt-3">
                  <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
            <input type="hidden" class="user_id" value="<?php echo $row['id']; ?>">
                     <th>#</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Role</th>
            </tr>
        </thead>
        <tbody>
        <?php
                  $sql = "SELECT * FROM account";
                  $result = mysqli_query($conn, $sql);
                  $i = 1;
                  if (mysqli_num_rows($result) > 0){

                    while ($row = mysqli_fetch_assoc($result)){
                      echo '
                    <tr>
                      <td>'.$i.'</td>
                      <td>'.$row['username'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['type'].'</td>
                    </tr>';
                    $i++;
                    }
                  }
?>
        </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>


<!-- ADD Modal -->
<!-- Modal -->
<div class="modal fade" id="manage_userModal" tabindex="-1" aria-labelledby="manage_userModal_Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manage_userModal_Label">Add New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     
      <form action="save_user.php" method="POST">
  <div class="modal-body">
    <div class="col mb-3">
      <label for="type">Role</label>
      <select id="type" class="form-control form-control-sm" name="type">
        <option selected>--Select--</option>
        <option value="landlord">Landlord</option>
        <option value="tenant">Tenant</option>
      </select>
    </div>
    <div class="col mb-3 d-none" id="tenant">
  <label for="tenant_name">Tenant Name</label>
  <select id="tenant_name" class="form-control form-control-sm" name="tenant_name">
    <option selected>--Select--</option>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM tenant");
    while ($row = mysqli_fetch_assoc($result)) {
      // check if the tenant's email is already in the account table
      $email = $row['email'];
      $result2 = mysqli_query($conn, "SELECT * FROM account WHERE email='$email'");
      if (mysqli_num_rows($result2) == 0) {
        // if the email is not in the account table, add the option
        echo '<option value="' . $row['id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
      }
    }
    ?>
  </select>
</div>


    <div class="col mb-3 d-none" id="landlord">
      <label for="landlord_name">Landlord Name</label>
      <select id="landlord_name" class="form-control form-control-sm" name="landlord_name">
        <option selected>--Select--</option>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM landlord");
        while ($row = mysqli_fetch_assoc($result)) {
          // check if the landlord's email is already in the account table
          $email = $row['email'];
          $result1 = mysqli_query($conn, "SELECT * FROM account WHERE email='$email'");
          if (mysqli_num_rows($result1) == 0) {
            // if the email is not in the account table, add the option
            echo '<option value="' . $row['id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
          }
        }
        ?>
      </select>
    </div>
         

    <div class="col mb-3">
      <label for="email">Email</label>
      <input class="form-control form-control-sm" placeholder="Email" type="text" id="email" name="email" autocomplete="off">
    </div>

    <div class="col mb-3">
      <label for="password">Password</label>
      <input class="form-control form-control-sm" placeholder="Password" type="password" id="password" name="password" autocomplete="off">
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" name="save_user">Create User</button>
  </div>
</form>




<script>
  $(document).ready(function() {
    $('#example').DataTable( {
      responsive: {
        breakpoints: [
          {name: 'bigdesktop', width: Infinity},
          {name: 'meddesktop', width: 1480},
          {name: 'smalldesktop', width: 1280},
          {name: 'medium', width: 1188},
          {name: 'tabletl', width: 1024},
          {name: 'btwtabllandp', width: 848},
          {name: 'tabletp', width: 768},
          {name: 'mobilel', width: 480},
          {name: 'mobilep', width: 320}
        ]
      },
      "rowCallback": function(row, data, index) {
        if (data[0] == "1") {
          $(row).css('display', 'none');
        }
      },
      "columnDefs": [
      {
        // The index of the column you want to hide
        "targets": [0],
        // Set the visible property to false to hide the column
        "visible": false
      }
    ]
    });
  });
</script>
<script>
const select = document.getElementById('type');
const tenantSelect = document.getElementById('tenant');
const landlordSelect = document.getElementById('landlord');
const tenantNameSelect = document.getElementById('tenant_name');

select.addEventListener('change', function() {
  const role = this.value;

  // Show/hide the appropriate input field based on the selected role
  if (role === 'tenant') {
    tenantSelect.classList.remove('d-none');
    landlordSelect.classList.add('d-none');
  } else if (role === 'landlord') {
    landlordSelect.classList.remove('d-none');
    tenantSelect.classList.add('d-none');
  } else {
    landlordSelect.classList.add('d-none');
    tenantSelect.classList.add('d-none');
  }
});
</script>

<script>
  // listen for changes to the tenant_name select element
document.getElementById("tenant_name").addEventListener("change", function() {
  // get the selected option's value
  var tenant_id = this.value;
  // send a request to the server to get the corresponding email
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // check if the email already exists in the account table
      var email = this.responseText.trim();
      var emailInput = document.getElementById("email");
      if (email !== "" && emailInput.value !== email) {
        var xhr2 = new XMLHttpRequest();
        xhr2.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.exists) {
              alert("This email address is already in use. Please choose a different email address.");
              emailInput.value = "";
            } else {
              emailInput.value = email;
            }
          }
        };
        xhr2.open("GET", "check_email.php?email=" + encodeURIComponent(email), true);
        xhr2.send();
      } else {
        emailInput.value = email;
      }
    }
  };
  xhr.open("GET", "get_tenant_email.php?id=" + tenant_id, true);
  xhr.send();
});
// listen for changes to the landlord_name select element
document.getElementById("landlord_name").addEventListener("change", function() {
  // get the selected option's value
  var landlord_id = this.value;
  // send a request to the server to get the corresponding email
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // check if the email already exists in the account table
      var email = this.responseText.trim();
      var emailInput = document.getElementById("email");
      if (email !== "" && emailInput.value !== email) {
        var xhr2 = new XMLHttpRequest();
        xhr2.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.exists) {
              alert("This email address is already in use. Please choose a different email address.");
              emailInput.value = "";
            } else {
              emailInput.value = email;
            }
          }
        };
        xhr2.open("GET", "check_email.php?email=" + encodeURIComponent(email), true);
        xhr2.send();
      } else {
        emailInput.value = email;
      }
    }
  };
  xhr.open("GET", "get_landlord_email.php?id=" + landlord_id, true);
  xhr.send();
});

</script>


<script>
   $(document).ready(function() {

$('#hotelrooms_table').DataTable();

$('.delete_btn').click(function (e) { 
   e.preventDefault();

   var htlroom_id = $(this).closest('tr').find('.htlroom_id').text();
   // console.log(htlroom_id);
   $('#delete_id').val(htlroom_id);
   $('#deleteHotelroomsModal').modal('show');

});
});

</script>