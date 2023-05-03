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

if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
    header('location: ../login/login.php');
}

//if the above code is false then html below will be displayed

$tenant_id = $_GET['id'];
$sql = "SELECT * FROM tenant WHERE id = $tenant_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$date_of_birth = strtotime($row['date_of_birth']);
$formatted_date = date('M d, Y', $date_of_birth);

require_once '../tools/variables.php';
$page_title = "RMS | " . $row['first_name'] . " " . $row['last_name'];
$tenant = 'active';

require_once '../includes/header.php';

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
                            <h3 class="font-weight-bolder">VIEW TENANT</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="add-page-container">
                                <div class="col-md-2 d-flex justify-align-between float-right ">
                                    <a href="tenants.php" class='bx bx-caret-left pb-3'>Back</a>
                                </div>
                            </div>
                            <div class="media align-items-center py-3 mb-3 w-100">
                                <img src="../img/userprofile/<?php echo !empty($row['profile_img']) ? $row['profile_img'] : 'default.png'; ?>" alt="" class="ml-2 px-2 rounded-circle" width="170" height="150">
                                <div class="media-body ml-4">
                                    <h4 class="font-weight-bold mb-0 white-text"><?php echo $row['first_name'] . " " . $row['last_name']; ?></h4>
                                    <div class="text-muted mb-2">Tenant ID: <?php echo $row['id']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">Tenant Details</div>
                            <div class="card-body">
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1" for="name">Full Name</label>
                                        <?php 
                                        $name = $row['first_name'] . ' ';

                                        if (!empty($row['middle_name'])) {
                                            $name .= substr($row['middle_name'], 0, 1) . '. ';
                                        }

                                        $name .= $row['last_name'];
                                        ?>
                                        <input class="form-control" id="name" type="text" value="<?php echo $name; ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <?php
                                        require_once '../classes/reference.class.php';
                                        
                                        $reference = new Reference();
                                        
                                        $regCode = $row['region'];
                                        $provCode = $row['provinces'];
                                        $citymunCode = $row['city'];

                                        function ucwords_custom($str) {
                                            return ucwords(strtolower($str));
                                        }
                                        
                                        $region = ucwords_custom($reference->get_region_by_code($regCode));
                                        $region_parts = explode(" ", $region);
                                        if (count($region_parts) > 1) {
                                            $region = $region_parts[0] . " " . strtoupper($region_parts[1]);
                                        }
                                        
                                        $province = ucwords_custom($reference->get_province_by_code($provCode));
                                        $city = ucwords_custom($reference->get_city_by_code($citymunCode));
                                        

                                        $prevAdd = $row['previous_address'] . ', ';

                                        if (!empty($city)) {
                                            $prevAdd .= $city . ', ';
                                        }

                                        if (!empty($province)) {
                                            $prevAdd .= $province . ', ';
                                        }

                                        $prevAdd .= $region;
                                        ?>
                                        <label class="small mb-1" for="prevAdd">Previous Address</label>
                                        <input class="form-control" id="prevAdd" type="text" value="<?php echo $prevAdd; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="email">Email</label>
                                        <input class="form-control" id="email" type="text" value="<?php echo $row['email']; ?>"readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="Contact">Contact No.</label>
                                        <input class="form-control" id="Contact" type="text" value="<?php echo $row['contact_no']; ?>"readonly>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="Civil_Status">Civil Status</label>
                                        <input class="form-control" id="Civil_Status" type="text" value="<?php echo $row['relationship_status']; ?>"readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="Sex">Sex</label>
                                        <input class="form-control" id="Sex" type="text"  value="<?php echo $row['sex']; ?>"readonly>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="dob">Date of Birth</label>
                                        <input class="form-control" id="dob" type="text" value="<?php echo $formatted_date; ?>"readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="toh">Type of Household</label>
                                        <input class="form-control" id="toh" type="text"  value="<?php echo $row['type_of_household']; ?>"readonly>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="address">Address</label>
                                        <input class="form-control" id="address" value="<?php echo $row['previous_address'] ?>"readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="address">Is smoking?</label>
                                        <input class="form-control" id="address" value="<?php echo $row['is_smoking'] ?>"readonly>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="petd">Pet Details</label>
                                        <?php 
                                        require_once '../tools/functions.php';
                                            $number_of_pets = $row['number_of_pets'];
                                            $type_of_pet = $row['type_of_pet'];
                                            $type_of_pet_plural = pluralize($type_of_pet, $number_of_pets);
                                            $pet_details = "Has " . $number_of_pets . " " . $type_of_pet_plural; 
                                        ?>
                                        <input class="form-control" id="petd" value="<?php if($number_of_pets === 0 || $type_of_pet_plural === "None"){ echo 'N/A'; } else { echo $pet_details;} ?>"readonly>
                                    </div>
                                    <div class="col-md-6">
                                    <?php
                                            $has_vehicle = json_decode($row['has_vehicle']);
                                            $vehicle_specification = $row['vehicle_specification'];
                                            
                                            $vehicles = array();
                                            if ($has_vehicle != null) {
                                                foreach ($has_vehicle as $vehicle) {
                                                    if ($vehicle === 'Others') {
                                                        $vehicles[] = $vehicle_specification;
                                                    } else {
                                                        $vehicles[] = $vehicle;
                                                    }
                                                }
                                            }
                                            
                                            $count = count($vehicles);
                                            if ($count == 1) {
                                                $veh = "Owns a " . $vehicles[0];
                                            } else if ($count == 2) {
                                                $veh = "Owns a " . implode(" and ", $vehicles);
                                            } else {
                                                $veh = "Owns a " . implode(", ", array_slice($vehicles, 0, -1)) . ", and " . end($vehicles);
                                            }
                                        ?>
                                        <label class="small mb-1" for="vehicled">Vehicle Details</label>
                                        <input class="form-control" id="vehicled" value="<?php if(isset($row['has_vehicle'])){echo $veh;}else {echo 'N/A';} ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (strcasecmp($row['type_of_household'], 'One Person') !== 0 && strcasecmp($row['relationship_status'], 'Married') === 0): ?>
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">Spouse Details</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="small mb-1" for="sname">Full Name</label>
                                    <input class="form-control" id="sname" type="text"  value="<?php echo (!empty($row['spouse_first_name']) && !empty($row['spouse_last_name'])) ? ($row['spouse_first_name'] . " " . $row['spouse_last_name']) : 'Not Available'; ?>"readonly>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="semail">Email</label>
                                        <input class="form-control" id="semail" type="text" value="<?php echo $row['spouse_email'] ?>"readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="scontact">Contact No.</label>
                                        <input class="form-control" id="scontact" type="text" value="<?php echo $row['spouse_num'] ?>"readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (strcasecmp($row['type_of_household'], 'One Person') !== 0 ||   strcasecmp($row['type_of_household'], 'Couple') !== 0 && strcasecmp($row['relationship_status'], 'Married') !== 0 && !empty($occupants) && count($occupants) > 0): ?>
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">Other Occupants Details</div>
                            <div class="card-body">
                                <?php $occupants = json_decode($row['occupants'], true);
                                $occupants_relations = json_decode($row['occupants_relations'], true);
                                if(is_array($occupants)) { ?>
                                    <?php foreach($occupants as $key => $occupant) { ?>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="oname-<?php echo $key; ?>">Full Name</label>
                                                <input class="form-control" id="oname-<?php echo $key; ?>" type="text" value="<?php echo $occupant; ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="orelationship-<?php echo $key; ?>">Relationship to Tenant</label>
                                                <input class="form-control" id="orelationship-<?php echo $key; ?>" type="text" value="<?php echo $occupants_relations[$key]; ?>" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p>No other occupants</p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">Emergency Contact Person Details</div>
                            <div class="card-body">
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="ename">Full Name</label>
                                        <input class="form-control" id="ename" type="text" value="<?php echo $row['emergency_contact_person'] ?>"readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="econtact">Contact No.</label>
                                        <input class="form-control" id="econtact" type="text" value="<?php echo $row['emergency_contact_number'] ?>"readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
