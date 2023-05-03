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
    $page_title = 'RMS | Landlords';
    $landlord = 'active';

    require_once '../includes/header.php';
?>
<body>
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
              <h3 class="font-weight-bolder">VIEW TICKET</h3> 
            </div>
            <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right">
                <a href="tenant_ticket.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>
                      <?php
                      $id = $_GET['id'];
                      $sql = "SELECT * FROM tickets WHERE id = $id";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result); 
                        
      ?><div class="card mb-4">
      <div class="card-header">Tickets</div>
      <div class="card-body">
     
      <div class="row gx-3 mb-3">
              <div class="col-md-6">
                  <label class="small mb-1">Raised by</label>
                  <input class="form-control" id="" type="text" value="<?= $row['raised_by']  ?? '' ?>"readonly>
              </div>
             
              <div class="col-md-6">
                  <label class="small mb-1">Subject</label>
                  <input class="form-control" id="" type="text" value="<?= $row['raised_by']  ?? '' ?>"readonly>
              </div>
          </div>
        
          <div class="row gx-3 mb-3">
            
              <div class="col-md-6">
                  <label class="small mb-1" >Date Created</label>
                  <input class="form-control" id="" type="text"value=" <?= $row['date_created']  ?? '' ?>"readonly>
              </div>
          
              <div class="col-md-6">
                  <label class="small mb-1" >Status</label>
                  <input class="form-control" id="" type="text" value=" <?= $row['status']  ?? '' ?>"readonly>
              </div>
          </div>
          <div class="mb-3">
                  <label class="small mb-1">Message/Description</label>
                  <input class="form-control" id="" type="text"  value="<?= $row['messages']  ?? '' ?>"readonly>
              </div>
          </div>
      </div>
                    </div>
                        </div>
                      </div>