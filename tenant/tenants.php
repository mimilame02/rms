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
    $page_title = 'RMS | Tenants';
    $tenant = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
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
            <h3 class="font-weight-bolder">TENANTS</h3> 
          </div>
          <div class="add-tenant-container">
          <?php
                        if($_SESSION['user_type'] == 'admin'){ 
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
                    <?php
                        if(isset($_GET['success']) && $_GET['success'] === 'true' 
                            || isset($_GET['delete']) && $_GET['delete'] === 'true' 
                            || isset($_GET['edited']) && $_GET['edited'] === 'true') 
                        {
                            $title = "";
                            $message = "";
                            $icon = "";
                            
                            if (isset($_GET['success']) && $_GET['success'] === 'true') {
                                $title = "Tenant added successfully";
                                $message = "";
                                $icon = "success";
                            } elseif (isset($_GET['delete']) && $_GET['delete'] === 'true') {
                                $title = "Tenant deleted successfully";
                                $message = "";
                                $icon = "success";
                            } elseif (isset($_GET['edited']) && $_GET['edited'] === 'true') {
                                $title = "Tenant modified successfully";
                                $message = "";
                                $icon = "success";
                            }
                    ?>
                            <script>
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
                                });

                                Toast.fire({
                                    icon: '<?php echo $icon ?>',
                                    title: '<?php echo $title ?>',
                                    text: '<?php echo $message ?>'
                                });
                            </script>
                    <?php
                        }
                    ?>
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
                          <?php if($_SESSION['user_type'] == 'admin'){ ?>
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
                                echo '
                                <tr>
                                    <td>'.$i.'</td>
                                    <td>'.$row['first_name'].' '.$row['last_name'].'</td>
                                    <td>'.$row['email'].'</td>
                                    <td>'.$row['contact_no'].'</td>
                                    <td>'.$row['lease_count'].'</td>
                                    <td>'.$row['lease_start'].' to '.$row['lease_end'].'</td>
                                    <td>'.$row['floor_level'].'</td>
                                    <td>
                                      <div class="action">
                                        <a class="me-2 green" href="view_tenant.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                                        <a class="me-2 green" href="edit_tenant.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                                        <a class="green action-delete" href="delete_tenant.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
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
                $.ajax({
                    url: deleteUrl,
                    type: "DELETE",
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Item successfully deleted!',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        window.location.replace('tenants.php?delete=true');
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
