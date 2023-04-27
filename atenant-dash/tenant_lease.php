<?php
require_once '../includes/dbconfig.php';

//resume session here to fetch session values
session_start();

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

// Get the tenant's email from the session
$email = $_SESSION['email'];

// Query the database for the tenant's ID based on their email
$sql = "SELECT id FROM tenant WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
$result = mysqli_query($conn, $sql);

// Check if a row was returned
if (mysqli_num_rows($result) > 0) {
    // Get the tenant's ID
    $row = mysqli_fetch_assoc($result);
    $tenant_id = $row['id'];

    // Query the database for lease details for this tenant
    $sql = "SELECT lease.*, property_units.unit_no, unit_type.type_name, property_units.monthly_rent, property_units.floor_level
            FROM lease
            LEFT JOIN property_units ON lease.property_unit_id = property_units.id
            LEFT JOIN unit_type ON property_units.unit_type_id = unit_type.id
            WHERE lease.tenant_id = " . mysqli_real_escape_string($conn, $tenant_id);

    $result = mysqli_query($conn, $sql);
    $i = 1;
    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {

        echo '<div class="container-scroller">';
        require_once 'tenant_navbar.php';
        echo '<div class="container-fluid page-body-wrapper">';
        require_once 'tenant_sidebar.php';
        echo '<div class="main-panel">';
        echo '<div class="content-wrapper">';
        echo '<div class="row">';
        echo '<div class="col-12 col-xl-8 mb-4 mb-xl-0">';
        echo '<h3 class="font-weight-bolder">MY LEASE</h3>';
        echo '</div>';
        echo '<div class="add-tenant-container">';
        echo '</div>';
        echo '</div>';
        echo '<div class="row mt-4">';
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<div class="table-responsive pt-3">';
        echo '<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Unit</th>';
        echo '<th>Floor</th>';
        echo '<th>Type</th>';
        echo '<th>Rent</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $i++ . '</td>';
            echo '<td>' . $row['unit_no'] . '</td>';
            echo '<td>' . $row['floor_level'] . '</td>';
            echo '<td>' . $row['type_name'] . '</td>';
            echo '<td>' . $row['monthly_rent'] . '</td?>';
        echo '</tr>';
        }
      }

    echo '</tbody></table></div>';
} else {
    // If no rows were returned, display a message
    echo 'No leases found for this tenant.';
    echo '<br>Error: ' . mysqli_error($conn);
    echo '<br>SQL: ' . $sql;
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
        $("#delete-dialog").dialog({
            resizable: false,
            draggable: false,
            height: "auto",
            width: 500,
            modal: true,
            autoOpen: false
        });
</script>