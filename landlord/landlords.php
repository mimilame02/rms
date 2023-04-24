<?php

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

    require_once '../tools/variables.php';
    $page_title = 'RMS | Landlords';
    $landlord = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
    // Check if add_success parameter is present and data was added
    if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['added_landlords'])) {
      echo '<script>
              $(document).ready(function() {
                  Swal.fire({
                      toast: true,
                      position: "top-end",
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                          toast.addEventListener("mouseenter", Swal.stopTimer)
                          toast.addEventListener("mouseleave", Swal.resumeTimer)
                      },
                      icon: "success",
                      title: "Landlord added successfully!"
                  });
              });
            </script>';
      // Unset the added_lease session variable so the message is only shown once
      unset($_SESSION['added_landlords']);
  }

    // Check if add_success parameter is present and data was added
    if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['edited_landlords'])) {
      echo '<script>
              $(document).ready(function() {
                  Swal.fire({
                      toast: true,
                      position: "top-end",
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                          toast.addEventListener("mouseenter", Swal.stopTimer)
                          toast.addEventListener("mouseleave", Swal.resumeTimer)
                      },
                      icon: "success",
                      title: "Landlord updated successfully!"
                  });
              });
            </script>';
      // Unset the added_lease session variable so the message is only shown once
      unset($_SESSION['edited_landlords']);
  }
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
        <h3 class="font-weight-bolder">LANDLORDS</h3> 
      </div>
      <div class="add-tenant-container">
        <?php
            if($_SESSION['user_type'] == 'admin'){ 
        ?>
        <div class="add-tenant-container">
          <a href="add_landlord.php" class="btn btn-success btn-icon-text float-right">
              Add Landlord
              </a>
            <?php
                    }
                ?>
        </div>
      </div>
    </div>
    <div class="row mt-4">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive pt-3">
          <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
                <th>Assigned</th>
                <?php if($_SESSION['user_type'] == 'admin'){ ?>
                  <th>Action</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql = "SELECT landlord.*, COUNT(DISTINCT properties.id) AS property_count, COUNT(property_units.id) AS unit_count FROM landlord
                LEFT JOIN properties ON properties.landlord_id = landlord.id
                LEFT JOIN property_units ON property_units.property_id = properties.id
                GROUP BY landlord.id";
                $result = mysqli_query($conn, $sql);
                $i = 1;
                if (mysqli_num_rows($result) > 0){
                  while ($row = mysqli_fetch_assoc($result)){
                    echo '
                    <tr>
                      <td>'.$i.'</td>
                      <td>'.$row['first_name'].' '.$row['last_name'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['contact_no'].'</td>
                      <td>Properties: '.$row['property_count'].'<br><br>Units: '.$row['unit_count'].'</td>
                      <td>
                        <div class="action">
                          <a class="me-2 green" href="view_landlord.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                          <a class="me-2 green" href="edit_landlord.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                          <a class="green action-delete" href="delete_landlord.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
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



<script>
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
  }
} );
</script>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
    $(document).ready(function() {
        $(".action-delete").on('click', function(e) {
            e.preventDefault();

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    let deleteUrl = $(this).attr("href");
                    let row = $(this).closest('tr');

                    $.ajax({
                        url: deleteUrl,
                        type: "DELETE",
                        success: function(data) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            // Remove the table row corresponding to the deleted Tenant
                            row.remove();

                            Toast.fire({
                                icon: 'success',
                                title: 'Landlord successfully deleted!'
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            });
                        }
                    });
                }
            });
        });
    });
</script>