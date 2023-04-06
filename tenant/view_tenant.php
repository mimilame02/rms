<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7B4BLQNGYY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7B4BLQNGYY');
</script>
<?php
 require_once '../includes/dbconfig.php';
 require_once '../classes/tenants.class.php';
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
    //if the above code is false then html below will be displayed

    $tenant_id = $_GET['id'];
      $sql = "SELECT * FROM tenant WHERE id = $tenant_id";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);

    require_once '../tools/variables.php';
    $page_title = 'RMS | Tenants';
    $tenant = 'active';

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
            <h3 class="font-weight-bolder">VIEW TENANT</h3> 
          </div>
        </div>
    <div class="row">
        <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right ">
                <a href="tenants.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
           

             <div class="media align-items-center py-3 mb-3" >
              <img src="../img/userprofile/grayavatar.png" alt="" class="rounded-circle" width="130" >
              <div class="media-body ml-4">
                <h4 class="font-weight-bold mb-0 white-text"><?php echo $row['first_name'] . " " . $row['last_name']; ?></h4>
                <div class="text-muted mb-2">Tenant ID: <?php echo $row['id']; ?></div>
              </div>
              </div>


	<div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">Tenant Details</div>
                <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="name">Full Name</label>
                            <input class="form-control" id="name" type="text"  value="<?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'] ; ?>">
                        </div>

                        <div class="row gx-3 mb-3">
                  
                            <div class="col-md-6">
                                <label class="small mb-1" for="email">Email</label>
                                <input class="form-control" id="email" type="text" value="<?php echo $row['email']; ?>">
                            </div>
                   
                            <div class="col-md-6">
                                <label class="small mb-1" for="Contact">Contact No.</label>
                                <input class="form-control" id="Contact" type="text" value="<?php echo $row['contact_no']; ?>">
                            </div>
                        </div>
                
                        <div class="row gx-3 mb-3">
                       
                            <div class="col-md-6">
                                <label class="small mb-1" for="Civil_Status">Civil Status</label>
                                <input class="form-control" id="Civil_Status" type="text" value="<?php echo $row['relationship_status']; ?>">
                            </div>
                     
                            <div class="col-md-6">
                                <label class="small mb-1" for="Sex">Sex</label>
                                <input class="form-control" id="Sex" type="text"  value="<?php echo $row['sex']; ?>">
                            </div>
                        </div>
												<div class="row gx-3 mb-3">
												<div class="col-md-6">
                                <label class="small mb-1" for="dob">Date of Birth</label>
                                <input class="form-control" id="dob" type="text" value="<?php echo $row['date_of_birth']; ?>">
                            </div>
														<div class="col-md-6">
                                <label class="small mb-1" for="toh">Type of Household</label>
                                <input class="form-control" id="toh" type="text"  value="<?php echo $row['type_of_household']; ?>">
                            </div>
														</div>
                          <div class="row gx-3 mb-3">
                     
                        <div class="col-md-6">
                            <label class="small mb-1" for="address">Address</label>
                            <input class="form-control" id="address" value="<?php echo $row['previous_address'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="address">Is smoking?</label>
                            <input class="form-control" id="address" value="<?php echo $row['is_smoking'] ?>">
                        </div>
                        </div>
                    
                    
                        <div class="row gx-3 mb-3">
                        
                            <div class="col-md-6">
                                <label class="small mb-1" for="petd">Pet Details</label>
                                <input class="form-control" id="petd" value="<?php echo $row['number_of_pets'] . " " . $row['type_of_pet']; ?>">
                            </div>
                      
                            <div class="col-md-6">
                            <label class="small mb-1" for="vehicled">Vehicle Details</label>
                            <input class="form-control" id="vehicled"  value="<?php echo implode(", ", json_decode($row['has_vehicle'])) . " " . $row['vehicle_specification']; ?>">
                            </div>

                        </div>
                </div>
            </div>
        </div>

        <?php if ($row['type_of_household'] != 'one person' && $row['relationship_status'] == 'married'): ?>
            <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">Spouse Details</div>
                <div class="card-body">
                  
                        <div class="mb-3">
                            <label class="small mb-1" for="sname">Full Name</label>
                            <input class="form-control" id="sname" type="text"  value="<?php echo $row['spouse_first_name'] . " " . $row['spouse_last_name']; ?>">
                        </div>
                     
                        <div class="row gx-3 mb-3">
                      
                            <div class="col-md-6">
                                <label class="small mb-1" for="semail">Email</label>
                                <input class="form-control" id="semail" type="text" value="<?php echo $row['spouse_email'] ?>">
                            </div>
                          
                            <div class="col-md-6">
                                <label class="small mb-1" for="scontact">Contact No.</label>
                                <input class="form-control" id="scontact" type="text" value="<?php echo $row['spouse_num'] ?>">
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($row['type_of_household'] != 'one person' && $row['relationship_status'] != 'single'): ?>
            <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header">Other Occupants Details</div>
            <div class="card-body">
        <?php $occupants = json_decode($row['occupants'], true);
        $occupants_relations = json_decode($row['occupants_relations'], true);
        if(is_array($occupants) && count($occupants) > 0) { ?>
            <?php foreach($occupants as $key => $occupant) { ?>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1" for="oname-<?php echo $key; ?>">Full Name</label>
                        <input class="form-control" id="oname-<?php echo $key; ?>" type="text" value="<?php echo $occupant; ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1" for="orelationship-<?php echo $key; ?>">Relationship to Tenant</label>
                        <input class="form-control" id="orelationship-<?php echo $key; ?>" type="text" value="<?php echo $occupants_relations[$key]; ?>" disabled>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No other occupants</p>
        <?php } ?>
    </div>
    </div>
<?php endif; ?>

            <div class="card mb-4">
                <div class="card-header">Emergency Contact Person Details</div>
                <div class="card-body">
                        <div class="row gx-3 mb-3">
                  
                            <div class="col-md-6">
                                <label class="small mb-1" for="ename">Full Name</label>
                                <input class="form-control" id="ename" type="text" value="<?php echo $row['emergency_contact_person'] ?>">
                            </div>
            
                            <div class="col-md-6">
                                <label class="small mb-1" for="econtact">Contact No.</label>
                                <input class="form-control" id="econtact" type="text" value="<?php echo $row['emergency_contact_number'] ?>">
                            </div>
                        </div>
    </div>
</div>