<?php

    //resume session here to fetch session values
    session_start();
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
        header('location: ../login/login.php');
    }
  
    //if the above code is false then html below will be displayed

    require_once '../tools/variables.php';
    $page_title = 'RMS | Tenants';
    $tenant = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';

    // Check if add_success parameter is present and data was added
    if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['added_tenants'])) {
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
                      title: "Tenant added successfully!"
                  });
              });
            </script>';
      // Unset the added_lease session variable so the message is only shown once
      unset($_SESSION['added_tenants']);
  }

    // Check if add_success parameter is present and data was added
    if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['edited_tenants'])) {
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
                      title: "Tenant updated successfully!"
                  });
              });
            </script>';
      // Unset the added_lease session variable so the message is only shown once
      unset($_SESSION['edited_tenants']);
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
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'landlord') {
                require_once '../alandlord-dash/landlord_sidebar.php';
            } elseif ($_SESSION['user_type'] == 'admin') {
                require_once '../includes/sidebar.php';
            }
            // Add more conditions for other user types if needed
        } else {
            // Redirect to login or show a default sidebar if the user type is not set
        }
    ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bolder">TENANTS</h3> 
          </div>
          <div class="add-tenant-container">
            <?php
               if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){  
            ?>
            <a href="add_tenant.php" class="btn btn-success btn-icon-text float-right">
              Add Tenant </a>
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
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Contact No.</th>
                      <th>Leases</th>
                      <th>Contract Dates</th>
                      <th>Floor No.</th>
                      <?php if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){ ?>
                        <th>Action</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql = "SELECT tenant.*, COUNT(lease.id) AS lease_count, 
                              DATE_FORMAT(MIN(lease.lease_start), '%M %d, %Y') AS lease_start,
                              DATE_FORMAT(MAX(lease.lease_end), '%M %d, %Y') AS lease_end,
                              property_units.floor_level 
                              FROM tenant
                              LEFT JOIN lease ON lease.tenant_id = tenant.id 
                              LEFT JOIN property_units ON property_units.id = lease.property_unit_id 
                              GROUP BY tenant.id";
                      $result = mysqli_query($conn, $sql);
                      $i = 1;
                      if (mysqli_num_rows($result) > 0){
                          while ($row = mysqli_fetch_assoc($result)){
                            $actionButtons = ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord') ? 
                          '<div class="action">
                            <a class="me-2 green" href="view_tenant.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                            <a class="me-2 green" href="edit_tenant.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                            <a class="green action-delete" href="delete_tenant.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
                          </div>' : '';
                          $leaseDates = ($row['lease_start'] !== null && $row['lease_end'] !== null) ? $row['lease_start'] . ' to ' . $row['lease_end'] : 'No contract yet';
                          $leaseCount = ($row['lease_count'] != 0) ? $row['lease_count'] : '- - -';
                          $floorlevel = ($row['floor_level'] != 0) ? $row['floor_level'] : '- - -';
                              echo '
                              <tr>
                                  <td>'.$i.'</td>
                                  <td>'.$row['first_name'].' '.$row['last_name'].'</td>
                                  <td>'.$row['email'].'</td>
                                  <td>'.$row['contact_no'].'</td>
                                  <td>'.$leaseCount.'</td>
                                  <td>'.$leaseDates.'</td>
                                  <td>'.$floorlevel.'</td>
                                  <td>'.$actionButtons.'</td>
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


<!-- SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<!-- Include DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Include DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

<!-- Include DataTables Select -->
<script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>

<!-- Include DataTables DateTime -->
<script src="https://cdn.datatables.net/datetime/1.4.1/js/dataTables.dateTime.min.js"></script>




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
                                title: 'Tenant successfully deleted!'
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
