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
    $page_title = 'RMS | Buildings';
    $properties = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';

        // Check if add_success parameter is present and data was added
        if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['added_properties'])) {
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
                          title: "Property added successfully!"
                      });
                  });
                </script>';
          // Unset the added_lease session variable so the message is only shown once
          unset($_SESSION['added_properties']);
      }
    
        // Check if add_success parameter is present and data was added
        if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['edited_properties'])) {
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
                          title: "Property updated successfully!"
                      });
                  });
                </script>';
          // Unset the added_lease session variable so the message is only shown once
          unset($_SESSION['edited_properties']);
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
        <h3 class="font-weight-bolder">BUILDINGS</h3> 
      </div>
      <div class="add-tenant-container">
        <?php
            if($_SESSION['user_type'] == 'admin'){ 
        ?>
        <a href="add_building.php" class="btn btn-success btn-icon-text float-right">
            Add Building </a>
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
                      <th>Building Name</th>
                      <th>Units</th>
                      <th>Location</th>
                      <?php if($_SESSION['user_type'] == 'admin' ){ ?>
                      <th>Landlord</th>
                      <?php } ?>
                      <?php if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){ ?>
                        <th>Action</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql = "SELECT properties.*, landlord.first_name, landlord.last_name, COUNT(property_units.id) AS unit_count,
                      refregion.regDesc, refcitymun.citymunDesc, refbrgy.brgyDesc
                      FROM properties 
                      LEFT JOIN landlord ON properties.landlord_id = landlord.id
                      LEFT JOIN property_units ON property_units.property_id = properties.id
                      LEFT JOIN refbrgy ON properties.barangay = refbrgy.brgyCode
                      LEFT JOIN refcitymun ON refbrgy.citymunCode = refcitymun.citymunCode
                      LEFT JOIN refprovince ON refcitymun.provCode = refprovince.provCode
                      LEFT JOIN refregion ON refprovince.regCode = refregion.regCode
                      GROUP BY properties.id";
              
                      $result = mysqli_query($conn, $sql);
                      $i = 1;
                      if (mysqli_num_rows($result) > 0){
                        while ($row = mysqli_fetch_assoc($result)){
                          $actionButtons = ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord') ? 
                          '<div class="action">
                            <a class="me-2 green" href="view_building.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                            <a class="me-2 green" href="edit_building.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                            <a class="green action-delete" href="delete_building.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
                          </div>' : '';
                          $landlordTd = ($_SESSION['user_type'] == 'admin') ? '<td>'.$row['first_name'].' '.$row['last_name'].'</td>' : '';
                          $unitCount = ($row['unit_count'] != 0) ? $row['unit_count'] : '- - -';
                          echo '
                          <tr>
                            <td>'.$i.'</td>
                            <td>'.$row['property_name'].'</td>
                            <td>'.$unitCount.'</td>
                            <td>'.ucwords(strtolower($row['brgyDesc'])).', '.ucwords(strtolower($row['citymunDesc'])).'</td>
                            '.$landlordTd.'
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
                                title: 'Property successfully deleted!'
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