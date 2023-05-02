<?php
require_once '../includes/dbconfig.php';

//resume session here to fetch session values
session_start();
$user_id = $_SESSION['user_id'];
$tenant_id = $_SESSION['tenant_id'];


/*
    if user is not login then redirect to login page,
    this is to prevent users from accessing pages that requires
    authentication such as the dashboard
*/
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'tenant'){
    header('location: ../login/login.php');
}

//if the above code is false then html below will be displayed
require_once '../tools/variables.php';
$page_title = 'RMS | Leases';
$leases = 'active';
require_once '../includes/header.php';


?>
<div class="container-scroller">
<?php
        require_once 'tenant_navbar.php';
?>
<div class="container-fluid page-body-wrapper">
<?php
        require_once 'tenant_sidebar.php';
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bolder">MY LEASE</h3>
            </div>
            <div class="add-tenant-container">
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
                        </tr>
                        </thead>
                        <tbody>
                    <?php
                        // Query the database for lease details for this tenant
                        $sql = "SELECT lease.*, property_units.unit_no, unit_type.type_name, property_units.monthly_rent, property_units.floor_level
                        FROM lease
                        LEFT JOIN property_units ON lease.property_unit_id = property_units.id
                        LEFT JOIN unit_type ON property_units.unit_type_id = unit_type.id
                        WHERE lease.tenant_id = $tenant_id";

                        $result = mysqli_query($conn, $sql);
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                        <tr>
                            <td>' . $i++ . '</td>
                            <td>' . $row['unit_no'] . '</td>
                            <td>' . $row['floor_level'] . '</td>
                            <td>' . $row['type_name'] . '</td>
                            <td>' . $row['monthly_rent'] . '</td?>
                        </tr>';
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

