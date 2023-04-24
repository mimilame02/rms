<?php
    session_start();
    require_once '../includes/dbconfig.php';
   
    if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
        header('location: ../login/login.php');
    }
  
    if(isset($_POST['save_tickets'])) {
      $raised_by = $_POST['raised_by'];
      $subject = $_POST['subject'];
    
      $messages = $_POST['messages'];
    
      $query = "INSERT INTO tickets (raised_by, subject, messages) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->execute([$raised_by, $subject, $messages]);
    
      if($stmt) {   
        $_SESSION['status'] = "Successfully Saved";
        header('Location: tickets.php');
      } else {
        $_SESSION['status'] = "Not Saved";
        header('Location: tickets.php');
      }
    }

    require_once '../includes/header.php';
    require_once '../tools/variables.php';
    $page_title = 'RMS | Manage Users';
    $manage_users = 'active';
?>


