<?php

require_once '../includes/dbconfig.php';
require_once '../classes/landlords.class.php';
require_once '../classes/properties.class.php';
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
      $sql = "SELECT * FROM landlord WHERE id = $landlord_id";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);

      // Retrieve the properties under the landlord's name
      $sql = "SELECT * FROM properties WHERE landlord_id = $landlord_id";
      $result = mysqli_query($conn, $sql);

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
								<img src="../img/userprofile/grayavatar.png" alt="Admin" class="rounded-circle p-1" width="130">
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
									<span class="text-secondary"><?php echo $row['date_of_birth']; ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">Address</h6>
									<span class="text-secondary"><?php echo $row['address']; ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">E-Contact Person</h6>
									<span class="text-secondary"><?php echo $row['emergency_contact_person']; ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
									<h6 class="mb-0">E-Contact No.</h6>
									<span class="text-secondary"><?php echo $row['emergency_contact_number']; ?></span>
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
                     <th>Property Name</th>
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
              $count = 1;
              while($properties = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$count."</td>";
                echo "<td>".$properties['property_name']."</td>";
                if($_SESSION['user_type'] == 'admin'){ 
                  echo "<td>
                  <div class='action'>
                  <a class='me-2 green' href='view_landlord.php?id=".$properties['id']."'><i class='fas fa-eye'></i></a>
                  <a class='me-2 green' href='edit_landlord.php?id=".$properties['id']."'><i class='fas fa-edit'></i></a>
                  <a class='green action-delete' href='delete_landlord.php?id=".$properties['id']."'><i class='fas fa-trash'></i></a>
                  </div>
                  </td>"; 
                }
                echo "</tr>";
                $count++;
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