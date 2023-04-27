<?php

require_once '../includes/dbconfig.php';
require_once '../classes/buildings.class.php';
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

    $property_id = $_GET['id'];

    $sql = "SELECT p.*, CONCAT(landlord.first_name, ' ', landlord.last_name) AS landlord_name, 
    rr.regDesc AS region, 
    rp.provDesc AS provinces, 
    rcm.citymunDesc AS city, 
    rb.brgyDesc AS barangay, 
    GROUP_CONCAT(f.feature_name SEPARATOR ', ') AS features
    FROM properties p
    LEFT JOIN landlord ON p.landlord_id = landlord.id
    LEFT JOIN refregion rr ON p.region = rr.regCode 
    LEFT JOIN refprovince rp ON p.provinces = rp.provCode 
    LEFT JOIN refcitymun rcm ON p.city = rcm.citymunCode 
    LEFT JOIN refbrgy rb ON p.barangay = rb.brgyCode 
    LEFT JOIN features f ON JSON_EXTRACT(p.features, CONCAT('$[', f.id - 1, ']')) = f.id
    WHERE p.id = ?
    GROUP BY p.id";

    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $property_id);
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
            <h3 class="font-weight-bolder">VIEW BUILDING</h3> 
          </div>
        </div>
        <div class="row">
        <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right ">
                <a href="buildings.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
            </div>
       
<div class="row">
    <div class="col-xl-4">
        <?php
        // Decode the floor plans and store them in an array
        $floor_plans = json_decode($row['floor_plan'], true);
        ?>

        <div class="card bg-light mb-4 mb-xl-0">
            <div class="card-header">Floors</div>
            <div class="card-body  text-center">
                <div id="floor_plan" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php foreach ($floor_plans as $i => $floor_plan): ?>
                            <li data-target="#floor_plan" data-slide-to="<?php echo $i; ?>" class="<?php echo $i === 0 ? 'active' : ''; ?>"></li>
                        <?php endforeach; ?>
                    </ol>
                    
                    <div class="carousel-inner">
                        <?php if (!empty($floor_plans)): ?>
                            <?php foreach ($floor_plans as $i => $floor_plan): ?>
                                <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                                    <div id="floor-plan-container_<?php echo $i; ?>" class="floor-plan-container d-flex justify-content-center h-100 text-dark">
                                        <img id="uploaded-floor-plan_<?php echo $i; ?>" src="<?php echo '../img/floor_plans/' . $floor_plan; ?>" alt="Floor Plan <?php echo $i + 1; ?>" class="" height="650px" width="500px">
                                    </div>
                                    <div class="carousel-caption card bg-light h-100 text-dark d-md-block">
                                        <h5>Floor <?php echo $i + 1; ?></h5>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (count($floor_plans) > 1): ?>
                        <a class="carousel-control-prev" href="#floor_plan" role="button" data-slide="prev">
                            <span class="bx bx-skip-previous-circle icon fs-1 text-primary" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#floor_plan" role="button" data-slide="next">
                            <span class="bx bx-skip-next-circle icon fs-1 text-primary" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

 
         <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Building Details</div>
                <div class="card-body">
                      <div class="mb-3">
                            <label class="small mb-1">Building Name</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['property_name'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Number of Floors</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['num_of_floors'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Building Description</label>
                            <textarea class="form-control" id="" type="text"readonly><?= $row['property_description'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Assigned Landlord</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['landlord_name'] ?>"readonly>
                        </div>
                        </div>
                    </div>
               

              
                  <div class="card mb-4">
                <div class="card-header">Building Location</div>
                <div class="card-body">
                <div class="mb-3">
                            <label class="small mb-1">Street</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['street'] ?>"readonly>
                        </div>
                <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">Region</label>
                            <input class="form-control" id="" type="text" value="<?= $row['region'] ?>"readonly>
                        </div>
                       
                        <div class="col-md-6">
                            <label class="small mb-1">Province</label>
                            <input class="form-control" id="" type="text" value="<?= $row['provinces'] ?>"readonly>
                        </div>
                    </div>
                  
                    <div class="row gx-3 mb-3">
                      
                        <div class="col-md-6">
                            <label class="small mb-1" >City</label>
                            <input class="form-control" id="" type="text"value=" <?= $row['city'] ?>"readonly>
                        </div>
                    
                        <div class="col-md-6">
                            <label class="small mb-1" >Barangay</label>
                            <input class="form-control" id="" type="text" value=" <?= $row['barangay'] ?>"readonly>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="card mb-4">
    <div class="card-header">Features</div>
    <div class="card-body">
        <?php if (!is_null($row['features'])): ?>
            <ul>
                <?php foreach (explode(",", $row['features']) as $feature):?>
                    <li><?php echo $feature; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No features found.</p>
        <?php endif; ?>
    </div>
</div>


