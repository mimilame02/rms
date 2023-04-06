<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7B4BLQNGYY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7B4BLQNGYY');
</script>
<?php
session_start();
require_once '../includes/dbconfig.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
    header('location: ../login/login.php');
}

if(isset($_POST['save_user'])) {
  $type = $_POST['type'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if ($type == 'landlord') {
    $landlord_id = $_POST['landlord_name'];
    $result = mysqli_query($conn, "SELECT * FROM landlord WHERE id = $landlord_id");
    $row = mysqli_fetch_assoc($result);
    $username = $row['first_name'] . ' ' . $row['last_name'];
  } elseif ($type == 'tenant') {
    $tenant_id = $_POST['tenant_name'];
    $result = mysqli_query($conn, "SELECT * FROM tenant WHERE id = $tenant_id");
    $row = mysqli_fetch_assoc($result);
    $username = $row['first_name'] . '  ' . $row['last_name'];
  } else {
    $username = $_POST['name'];
  }

  $query = "INSERT INTO account (username, email, password, type) VALUES ('$username', '$email', '$password', '$type')";

  if(mysqli_query($conn, $query)) {
    echo "User created successfully.";
    header('location: manage_user.php');

  } else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
  }
}

mysqli_close($conn);



require_once '../includes/header.php';
require_once '../tools/variables.php';
$page_title = 'RMS | Manage Users';
$manage_users = 'active';
?>
