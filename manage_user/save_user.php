<?php
    session_start();
    require_once '../includes/dbconfig.php';
   
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
        header('location: ../login/login.php');  
    }
    if(isset($_POST['save_user'])) {
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $type = $_POST['type'];
    
      $query = "INSERT INTO account (username, email, password, type) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->execute([$username, $email, $password, $type]);
    
      if($stmt) {   
        $_SESSION['status'] = "Successfully Saved";
        header('Location: manage_user.php');
      } else {
        $_SESSION['status'] = "Not Saved";
        header('Location: manage_user.php');
      }
    }

    require_once '../includes/header.php';
    require_once '../tools/variables.php';
    $page_title = 'RMS | Manage Users';
    $manage_users = 'active';
?>


