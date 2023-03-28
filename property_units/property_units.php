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
    $page_title = 'RMS | Property Units';
    $p_units = 'active';

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
        <h3 class="font-weight-bolder">PROPERTY UNITS</h3> 
      </div>
      <div class="add-tenant-container">
      <?php
                    if($_SESSION['user_type'] == 'admin'){ 
                ?>
      <a href="add_property_units.php" class="btn btn-success btn-icon-text float-right">
          Add Property Unit </a>
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
                      <th>Main Property</th>
                      <th>Units</th>
                      <th>Floors</th>
                      <th>Condition</th>
                      <th>Rent</th>
                      <th>Status</th>
                      <?php if($_SESSION['user_type'] == 'admin'){ ?>
                        <th>Action</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql = "SELECT pu.*, uc.condition_name, p.property_name, pu.unit_no,
                      COALESCE(lease.status, pu.status) AS current_status,
                      lease.lease_end
                      FROM property_units pu 
                      LEFT JOIN unit_condition uc ON pu.unit_condition_id = uc.id 
                      LEFT JOIN properties p ON pu.property_id = p.id
                      LEFT JOIN lease ON lease.property_unit_id = pu.id
                      GROUP BY p.id, pu.id";
                      $result = mysqli_query($conn, $sql);              
                      $i = 1;
                      if (mysqli_num_rows($result) > 0){
                        while ($row = mysqli_fetch_assoc($result)){
                          //Determine status based on lease updated status
                          $status = '';
                          if ($row['current_status'] == 'Occupied') {
                            if (strtotime($row['lease_end']) >= time()) {
                              $status = '<button class="btn btn-warning btn-lg p-2">Occupied until '.$row['lease_end'].'</button>';
                            } else {
                              $status = '<button class="btn btn-danger btn-lg p-2">Occupied (expired lease)</button>';
                            }
                          } elseif ($row['current_status'] == 'Vacant') {
                            if (!is_null($row['lease_end'])) {
                              $vacant_date = date('F j, Y', strtotime($row['lease_end'] . ' + 7 days'));
                              $status = '<button class="btn btn-success btn-lg p-2">Vacant after '.$vacant_date.'</button>';
                            } else {
                              $status = '<button class="btn btn-success btn-lg p-2">Vacant</button>';
                            }
                          } elseif ($row['current_status'] == 'Unavailable') {
                            $status = '<button class="btn btn-secondary btn-lg p-2">Unavailable</button>';
                          }

                          echo '
                          <tr>
                            <td>'.$i.'</td>
                            <td>'.$row['property_name'].'</td>
                            <td>'.$row['unit_no'].'</td>
                            <td>'.$row['floor_level'].'</td>
                            <td>'.$row['condition_name'].'</td>
                            <td>'.$row['monthly_rent'].'</td>
                            <td>'.$status.'</td>
                            <td>
                              <div class="action">
                                <a class="me-2 green" href="view_property_units.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                                <a class="me-2 green" href="edit_property_units.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                                <a class="green action-delete" href="delete_property_units.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
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

<div id="delete-dialog" class="dialog" title="Delete Property Unit">
    <p><span>Are you sure you want to delete the property unit record?</span></p>
</div>

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

        // Change the background colors of the Yes and Cancel buttons using Bootstrap classes
        $("#delete-dialog").dialog('option', 'buttons', {
            "Yes" : {
                text: "Yes",
                class: "btn btn-danger",
                click: function() {
                    window.location.href = $(this).data("href");
                }
            },
            "Cancel" : {
                text: "Cancel",
                class: "btn btn-secondary",
                click: function() {
                    $(this).dialog("close");
                }
            }
        });

        $(".action-delete").on('click', function(e) {
            e.preventDefault();
            $("#delete-dialog").data("href", $(this).attr("href")).dialog("open");
        });
    });
</script>