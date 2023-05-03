<?php

require_once '../includes/dbconfig.php';
require_once '../classes/landlords.class.php';
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
    //if the above code is false then html below will be displayed

    // Retrieve the landlord's info based on the ID
      $landlord_id = $_GET['id'];

    // Retrieve the properties under the landlord's name
    $sql = "SELECT 
          landlord.*, 
          properties.property_name, 
          COUNT(DISTINCT property_units.floor_level) AS floor_count,
          GROUP_CONCAT(DISTINCT property_units.unit_no) AS units,
          COALESCE(lease.status, property_units.status) AS current_status, 
          COUNT(CASE WHEN COALESCE(lease.status, property_units.status) = 'Occupied' THEN 1 END) AS occupied_count,
          COUNT(CASE WHEN COALESCE(lease.status, property_units.status) = 'Vacant' THEN 1 END) AS vacant_count,
          COUNT(CASE WHEN COALESCE(lease.status, property_units.status) = 'Unavailable' THEN 1 END) AS unavailable_count,
          lease.lease_end 
          FROM landlord
          LEFT JOIN properties ON properties.landlord_id = landlord.id
          LEFT JOIN property_units ON property_units.property_id = properties.id
          LEFT JOIN lease ON lease.property_unit_id = property_units.id
          WHERE landlord.id = '$landlord_id'
          GROUP BY landlord.id, properties.id, COALESCE(lease.status, property_units.status)";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);

      $date_of_birth = strtotime($row['date_of_birth']);
      $formatted_date = date('M d, Y', $date_of_birth);

    require_once '../tools/variables.php';
    $page_title = "RMS | " . $row['first_name'] . " " . $row['last_name'];;
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
        require_once '../includes/sidebar.php';
      ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bolder">VIEW LANDLORD</h3> 
          </div>
        </div>
        <div class="row">
        <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right ">
                <a href="landlords.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
             </div>
			<div class="row">
				<div class="col-lg-4">
					<div class="card mt-4">
						<div class="card-body">
							<div class="d-flex flex-column align-items-center text-center">
								<img src="../img/userprofile/<?php echo !empty($row['profile_img']) ? $row['profile_img'] : 'default.png'; ?>" alt="Landlord" class="rounded-circle p-1" width="200" height="200">
								<div class="mt-3">
								<h4 class="white-text" ><?php echo $row['first_name'] . " " . $row['last_name']; ?></h4>
									<p class="text-muted font-size-sm"><?php echo $row['email']; ?></p>
									<button class="btn btn-primary">Edit</button>
									<button class="btn btn-danger">Delete</button>
								</div>
							</div>
							<hr class="my-4">
							<ul class="list-group list-group-flush">
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">Contact No.</h6>
									<span class="text-secondary"><?php echo $row['contact_no']; ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">Date of Birth</h6>
									<span class="text-secondary"><?php echo $formatted_date; ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">Address</h6>
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
                    

                    $address = $row['address'] . ', ';

                    if (!empty($city)) {
                        $address .= $city . ', ';
                    }

                    if (!empty($province)) {
                        $address .= $province . ', ';
                    }

                    $address .= $region;
                  ?>
									<span class="text-secondary mt-3"><?php echo $address; ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">E-Contact Person</h6>
									<span class="text-secondary"><?php echo $row['emergency_contact_person']; ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">E-Contact No.</h6>
									<span class="text-secondary"><?php echo $row['emergency_contact_number']; ?></span>
								</li>
                <hr class="my-4 mx-3">
                <li class="list-group-item">
									<h6 class="mb-0">Identification Document</h6><br>
                  <div class="image-container float-right">
									  <img src="../img/id/<?php echo !empty($row['identification_document']) ? $row['identification_document'] : 'default.png'; ?>" alt="Admin" class="mx-auto rounded p-1 w-100 h-50" width="auto" height="250">
                  </div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div class="card mt-4">
						<div class="card-body">
            <div class="table-responsive pt-3">
                  <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                     <th>#</th>
                     <th>Building</th>
                     <th>Units</th>
                     <th>Status</th>
                     <?php
                            if($_SESSION['user_type'] == 'admin'){ 
                        ?>
                        <th>Action</th>
                        <?php
                            }
                        ?>
            </tr>
        </thead>
        <tbody>
        <?php
            $i = 1;
            if(is_array($row)) {
                foreach($result = mysqli_query($conn, $sql) as $row){
                  $units = explode(',', $row['units']);
                  $floor_count = $row['floor_count'];
                
                  for($i = 1; $i <= $floor_count; $i++) {
                    $num_units_per_floor = count(array_filter($units, function($unit) use ($i) {
                      return strpos($unit, '-'.$i.',') !== false;
                    }));

                  $unit_counts = 'Occupied: '.$row['occupied_count'].'<br><br>Vacant: '.$row['vacant_count'].'<br><br>Unavailable: '.$row['unavailable_count'];
              
                  echo '
                  <tr>
                      <td>'.$i.'</td>
                      <td>'.$row['property_name'].'</td>
                      <td>'.$num_units_per_floor.'</td>
                      <td>'.$unit_counts.'</td>';
              
                  if($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'landlord'){ 
                      echo '<td>
                                <div class="action">
                                  <a class="me-2 green" href="view_property_units.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                                  <a class="me-2 green" href="edit_property_units.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                                  <a class="green action-delete" href="delete_property_units.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>';
                  }
                  echo '</tr>';
                  $i++;
                }
              }
            }
            ?>
        </tbody>
    </table>
</div>
					
					</div>
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