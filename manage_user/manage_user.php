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
                     <?php
                                if($_SESSION['user_type'] == 'admin'){ 
                            ?>
                            <th>Action</th>
                            <?php
                                }
                            ?>
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
                        <td>
                        <div class="action">
                        <a class="green action-delete" href="delete_user.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
          </div>
                        </td>
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
    <div class="col">
      <label for="type">Role</label>
      <select id="type" class="form-control form-control-sm" name="type">
        <option selected>--Select--</option>
        <option value="landlord">Landlord</option>
        <option value="tenant">Tenant</option>
      </select>
    </div>

    <div class="col d-none" id="tenant">
      <label for="tenant_name">Tenant Name</label>
      <select id="tenant_name" class="form-control form-control-sm" name="tenant_name">
        <option selected>--Select--</option>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM tenant");
        while ($row = mysqli_fetch_assoc($result)) {
          echo '<option value="' . $row['id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
        }
        ?>
      </select>
    </div>

    <div class="col d-none" id="landlord">
      <label for="landlord_name">Landlord Name</label>
      <select id="landlord_name" class="form-control form-control-sm" name="landlord_name">
        <option selected>--Select--</option>
        <?php
          $result = mysqli_query($conn, "SELECT * FROM landlord");
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
          }
        ?>
      </select>
    </div>
         

    <div class="col">
      <label for="email">Email</label>
      <input class="form-control form-control-sm" placeholder="Email" type="text" id="email" name="email" autocomplete="off">
    </div>

    <div class="col">
      <label for="password">Password</label>
      <input class="form-control form-control-sm" placeholder="Password" type="password" id="password" name="password" autocomplete="off">
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" name="save_user">Create User</button>
  </div>
</form>



<!-- Delete Hotel Rooms Modal -->
<div class="modal fade" id="delmanage_userModal" tabindex="-1" aria-labelledby="delmanage_userModal_Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="delmanage_userModal_Label">Delete User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="save_user.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="delete_id">
                        <h4>Are you sure, you want to delete this data?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="delete_user" class="btn btn-danger">Yes. Delete</button>
                    </div>
                </form>    
            </div>
        </div>
    </div>
    <!-- End of Delete Hotel Rooms Modal -->



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