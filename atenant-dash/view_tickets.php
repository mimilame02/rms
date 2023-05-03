<?php

    //resume session here to fetch session values
    session_start();
    $user_id = $_SESSION['user_id'];
    $tenant_id = $_SESSION['tenant_id'];
    
    require_once '../tools/functions.php';
    require_once '../classes/tickets.class.php';
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'tenant'){
        header('location: ../login/login.php');
    }
    //if the above code is false then html below will be displayed
    // Check if the form was submitted
if (isset($_POST['save_tickets'])) {
  // Create a new instance of the class containing the add_tickets function
  $tickets_obj = new Ticket();

  // Set the class properties with the submitted form data
  $tickets_obj->raised_by = $_SESSION['username']; // Assuming you store the user ID in the session
  $tickets_obj->subject = $_POST['subject'];
  $tickets_obj->date_created = date('Y-m-d H:i:s');
  $tickets_obj->status = 'open'; // Assuming new tickets have an 'open' status
  $tickets_obj->messages = $_POST['messages'];

  // Handle the file upload if a file was provided
  if (!empty($_FILES['attachment']['name'])) {
      // Define the target directory and file name
      $target_dir = '../img/tickets/'; // You may need to create this directory and set appropriate permissions
      $target_file = $target_dir . basename($_FILES['attachment']['name']);

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
          $tickets_obj->attachment = $target_file;
      } else {
          $_SESSION['error'] = 'Error uploading the attachment.';
      }
  }

  // Call the add_tickets function
  if ($tickets_obj->add_tickets()) {
    $_SESSION['added_tickets'] = true;
    header('location: tenant_ticket.php?add_success=1');
  } else {
      $_SESSION['error'] = 'Error raising the ticket.';
  }

}
// Check if add_success parameter is present and data was added
if (isset($_GET['add_success']) && $_GET['add_success'] == '1' && isset($_SESSION['added_tickets'])) {
  echo '<script>
          $(document).ready(function() {
              Swal.fire({
                  toast: true,
                  position: "top-end",
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                      toast.addEventListener("mouseenter", Swal.stopTimer)
                      toast.addEventListener("mouseleave", Swal.resumeTimer)
                  },
                  icon: "success",
                  title: "Ticket added successfully!"
              });
          });
        </script>';
  // Unset the added_lease session variable so the message is only shown once
  unset($_SESSION['added_tickets']);
}

    require_once '../tools/variables.php';
    $page_title = 'RMS | Tickets';
    $tickets_obj = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
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
     require_once 'tenant_sidebar.php';
  ?>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-bolder">MY TICKETS</h3> 
      </div>
      <div class="add-tenant-container">
      <?php
                    if($_SESSION['user_type'] == 'tenant'){ 
                ?>
     <button type="button" class="btn btn-success btn-icon-text float-right" data-toggle="modal" data-target="#tickets_Modal">
            Raise Ticket
          </button>
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
                     <th>Raised By</th>
                     <th>Subject</th>
                     <th>Date Created</th>
                     <th>Status</th>

                     <?php
                                if($_SESSION['user_type'] == 'tenant'){ 
                            ?>
                            <th>Action</th>
                            <?php
                                }
                            ?>
            </tr>
        </thead>
        <tbody>
        <?php
                  $sql = "SELECT * FROM tickets";
                  $result = mysqli_query($conn, $sql);
                  $i = 1;
                  if (mysqli_num_rows($result) > 0){

                    while ($row = mysqli_fetch_assoc($result)){
                      echo '
                    <tr>
                      <td>'.$i.'</td>
                      <td>'.$row['raised_by'].'</td>
                      <td>'.$row['subject'].'</td>
                      <td>'.$row['date_created'].'</td>
                      <td>'.$row['status'].'</td>
                        <td>
                        <div class="action">
                        <a class="me-2 green" href="view_tickets.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
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

<!-- Modal -->
<div class="modal fade" id="tickets_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tickets_Modal_Label">Raise Tickets</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="tenant_ticket.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
      <div class="col">
                        <label for="subject">Subject</label>
                        <input  class="form-control form-control-sm " placeholder="subject" type="text" id="subject" name="subject" required>
                        <br>
                      </div>
                      <div class="col">
                        <label for="attachment">Attachment</label>
                        <input type="file" class="form-control form-control-sm " id="attachment" name="attachment">
                      </div>
                            <div class="col mt-2">
                                  <label for="messages">Message/Task/Description</label>
                                  <textarea class="form-control form-control-lg" id="messages" name="messages" col="100" row="20"></textarea>
                                </div>
                            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="save_tickets">Raise Ticket</button>
      </div>
    </div>
  </div>
</div>
</form>


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