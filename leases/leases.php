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
    $page_title = 'RMS | Leases';
    $leases = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';

    // Check if add_success parameter is present and data was added
    if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['added_lease'])) {
        $added_lease = $_SESSION['added_lease'];
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
                        title: "Lease added successfully!"
                    });
                });
              </script>';
        // Unset the added_lease session variable so the message is only shown once
        unset($_SESSION['added_lease']);
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
        <h3 class="font-weight-bolder">LEASES</h3> 
      </div>
      <div class="add-tenant-container">
      <?php
          if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){ 
      ?>
      <a href="add_lease.php" class="btn btn-success btn-icon-text float-right">
          Add Lease</a>
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
                        <th>Unit</th>
                        <th>Floor</th>
                        <th>Type</th>
                        <th>Rent</th>
                        <th>Tenant Name</th>
                        <th>Status</th>
                        <th>Lease Contract</th>
                        <?php
                            if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){ 
                        ?>
                        <th>Action</th>
                        <?php
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT lease.*, property_units.unit_no, unit_type.type_name, property_units.monthly_rent, property_units.floor_level, DATE_FORMAT(MIN(lease.lease_start), '%M %d, %Y') AS lease_start,
                        DATE_FORMAT(MAX(lease.lease_end), '%M %d, %Y') AS lease_end, tenant.first_name, tenant.last_name
                        FROM lease
                        LEFT JOIN property_units ON lease.property_unit_id = property_units.id
                        LEFT JOIN unit_type ON property_units.unit_type_id = unit_type.id
                        RIGHT JOIN tenant ON lease.tenant_id = tenant.id 
                        WHERE tenant_id=tenant.id";
                    $result = mysqli_query($conn, $sql);
                    $i = 0;
                    if (mysqli_num_rows($result) > 0){
                        while ($row = mysqli_fetch_assoc($result)){
                            // Determine lease status based on lease end date
                            $status = '';
                            if ($row['status'] == 'Occupied') {
                                if (strtotime($row['lease_end']) >= time()) {
                                    $status = '<button class="btn btn-warning btn-lg p-2">Occupied until ' . date('F d', strtotime($row['lease_end'])) . '</button>';
                                } else {
                                    $status = '<button class="btn btn-danger btn-lg p-2">Occupied (expired lease)</button>';
                                }
                            } elseif ($row['status'] == 'Vacant') {
                                $vacant_date = date('F j, Y', strtotime($row['lease_end'] . ' + 7 days'));
                                $status = '<button class="btn btn-success btn-lg p-2">Vacant after '.$vacant_date.'</button>';
                            } elseif ($row['status'] == 'Unavailable') {
                                $status = '<button class="btn btn-secondary btn-lg p-2">Unavailable</button>';
                            }
                            $actionButtons = ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord') ? 
                            '<div class="action">
                              <a class="me-2 green" href="view_lease.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                              <a class="me-2 green" href="edit_lease.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                              <a class="green action-delete" href="delete_lease.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
                            </div>' : '';

                            $leaseDates = null;
                            if (!empty($i > 0)) {
                                if (!empty($row['lease_start']) && !empty($row['lease_end'])) {
                                $leaseDates = $row['lease_start'] . '  -  ' . $row['lease_end'];
                                } else {
                                $leaseDates = 'No contract yet';
                                } 
                            } else {
                                $i = '';
                                $leaseDates = '';
                                $status = '';
                                $actionButtons = '';
                            }
                            
                                echo '
                                <tr>
                                    <td>'.$i.'</td>
                                    <td>'.$row['unit_no'].'</td>
                                    <td>'.$row['floor_level'].'</td>
                                    <td>'.$row['type_name'].'</td>
                                    <td>'.$row['monthly_rent'].'</td>
                                    <td>'.$row['last_name'].','.$row['first_name'].'</td>
                                    <td>'.$status.'</td>
                                    <<td>'.$leaseDates.'</td>
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
                buttonsStyling: false,
                reverseButtons: true
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
                                title: 'Lease successfully deleted!'
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
