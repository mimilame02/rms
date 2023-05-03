<?php

require_once '../includes/dbconfig.php';
require_once '../classes/property_units.class.php';
require_once '../classes/account.class.php';


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
    $unit_id = $_GET['id'];

    $sql = "SELECT pu.*, p.property_name, ut.type_name, uc.condition_name, uc.unit_type_picture AS condition_picture, GROUP_CONCAT(f.feature_name SEPARATOR ', ') AS features
    FROM property_units pu
    LEFT JOIN properties p ON pu.property_id = p.id
    LEFT JOIN unit_type ut ON pu.unit_type_id = ut.id
    LEFT JOIN unit_condition uc ON pu.unit_condition_id = uc.id
    LEFT JOIN features f ON JSON_EXTRACT(pu.pu_features, CONCAT('$[', f.id - 1, ']')) = f.id
    WHERE pu.id = ?
    GROUP BY pu.id;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $unit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    
    require_once '../includes/header.php';
    
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
            <h3 class="font-weight-bolder">VIEW UNIT</h3> 
          </div>
        </div>
        <div class="row">
        <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right ">
                <a href="property_units.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
            </div>
       
             <div class="row">
              <div class="col-xl-4">
                          <!-- Profile picture card-->
                          <div class="card mb-4 mb-xl-0">
                              <div class="card-header">Unit Condition</div>
                              <div class="card-body">
                              <div class="mb-3">
                            <label class="large fs-4 mb-1 float-right"><?= $row['condition_name'] ?></label><br>
                            <?php if (isset($row['condition_name'])) {
    require_once '../classes/reference.class.php';
    $ref_obj = new Reference();
    $ref = $ref_obj->get_unit_con();
    $unit_condition_id = $row['unit_condition_id'];
    $unit_type_picture = "SELECT unit_type_picture FROM unit_condition WHERE id=:unit_condition_id;";

    foreach ($ref as $unit_condition) {
        if ($unit_condition['id'] == $unit_condition_id) {
            $unit_type_picture = isset($unit_condition['unit_type_picture']) && $unit_condition['unit_type_picture'] !== '' ? $unit_condition['unit_type_picture'] : 'default-image.png';
            break;
        }
    }
?>
    <div class="image-container mb-3" style="margin-inline: 30%;">
        <img class="" src="../img/unit_conditions/<?php echo $unit_type_picture; ?>" alt="Condition Picture" class="img-thumbnail" width="100%">
    </div>
<?php } ?>

                        </div>
                      
                  </div>
                          </div>
                      </div>
 
   <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Unit Details</div>
                <div class="card-body">
                     
                        <div class="mb-3">
                            <label class="small mb-1">Main Building</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['property_name'] ?>" readonly>
                        </div>
                      
                        <div class="row gx-3 mb-3">
                        
                            <div class="col-md-6">
                                <label class="small mb-1">Type of Unit</label>
                                <input class="form-control" id="" type="text" value="<?= $row['type_name'] ?>" readonly>
                            </div>
                           
                            <div class="col-md-6">
                                <label class="small mb-1">Monthly Rent Amount</label>
                                <input class="form-control" id="monthly_rent" type="text" value="<?= $row['monthly_rent'] ?>" readonly>
                            </div>
                        </div>
                      
                        <div class="row gx-3 mb-3">
                          
                            <div class="col-md-6">
                                <label class="small mb-1" >One Month Deposit Amount</label>
                                <input class="form-control" id="" type="text"value="<?= $row['one_month_deposit'] ?> " readonly>
                            </div>
                        
                            <div class="col-md-6">
                                <label class="small mb-1" >One Month Advance Amount</label>
                                <input class="form-control" id="" type="text" value=" <?= $row['one_month_advance'] ?>" readonly>
                            </div>
                        </div>
                       
                        <div class="mb-3">
                            <label class="small mb-1" >Unit No.</label>
                            <input class="form-control" id="" value="<?= $row['unit_no'] ?>"readonly >
                        </div>
                       
                        <div class="row gx-3 mb-3">
                           
                            <div class="col-md-6">
                                <label class="small mb-1" >Floor Level</label>
                                <input class="form-control" id=""   value="<?= $row['floor_level'] ?>"readonly>
                            </div>
                           
                            <div class="col-md-6">
                                <label class="small mb-1" >Status</label>
                                <input class="form-control" id=""  value="<?= $row['status'] ?>"readonly>
                            </div>
                            </div>
                            <div class="row gx-3 mb-3">
                          
                            <div class="col-md-6">
                                <label class="small mb-1" >Number of Rooms </label>
                                <input class="form-control" id=""   value="<?= $row['num_rooms'] ?>"readonly>
                            </div>
                           
                            <div class="col-md-6">
                                <label class="small mb-1">Number of Bathrooms</label>
                                <input class="form-control" id=""  value="<?= $row['num_bathrooms'] ?>"readonly>
                            </div>
                        </div>
                        </div>
                </div>
               
                <div class="card mb-4">
    <div class="card-header">Features</div>
    <div class="card-body">
        <?php
        $feature_ids = json_decode($row['pu_features']);
        if (!empty($feature_ids)) {
            $sql_features = "SELECT feature_name FROM features WHERE id IN (" . implode(',', $feature_ids) . ")";
            $result_features = mysqli_query($conn, $sql_features);
            if (mysqli_num_rows($result_features) > 0) {
                echo '<ul>';
                while ($row_features = mysqli_fetch_assoc($result_features)) {
                    echo '<li>' . $row_features['feature_name'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo 'No feature found.';
            }
        } else {
            echo 'No feature found.';
        }
        ?>
    </div>
</div>


    