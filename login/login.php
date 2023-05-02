<?php
  session_start();

  $page_title = 'Login';
  require_once '../includes/dbconfig.php';


if (isset($_POST['email']) && isset($_POST['password'])) {
    //Sanitizing the inputs of the users. Mandatory to prevent injections!
    $email = htmlentities($_POST['email']);
    $password = htmlentities($_POST['password']);
    $sql = "SELECT * FROM account WHERE account.email ='$email' AND password ='$password'";
    $result1 = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result1) > 0) {
        while ($row = mysqli_fetch_assoc($result1)) {
            $_SESSION['logged_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = $row['type'];
            // display the appropriate dashboard page for user
            if ($_SESSION['user_type'] == 'admin') {
                header('location: ../admin/dashboard.php');
            } elseif ($_SESSION['user_type'] == 'tenant') {
                $sql = "SELECT account.id AS user_id, tenant.id AS tenant_id 
                        FROM account 
                        LEFT JOIN tenant ON tenant.user_id = account.id 
                        WHERE account.email ='$email'";
                $result2 = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result2) > 0) {
                    $row = mysqli_fetch_assoc($result2);
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['tenant_id'] = $row['tenant_id'];
                    header('Location: atenant-dash/dashboard.php');
                    exit();
                } else {
                    $error = 'Tenant account not found.';
                }
            } elseif ($_SESSION['user_type'] == 'landlord') {
                $sql = "SELECT account.id AS user_id, landlord.id AS landlord_id 
                        FROM account 
                        LEFT JOIN landlord ON landlord.user_id = account.id  
                        WHERE account.email ='$email'";
                $result3 = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result3) > 0) {
                    $row = mysqli_fetch_assoc($result3);
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['landlord_id'] = $row['landlord_id'];
                    header('location: ../alandlord-dash/dashboard.php');
                } else {
                    $error = 'Landlord account not found.';
                }
            } else {
                $error = 'Invalid user type.';
            }
        }
    } else {
        //set the error message if account is invalid
        $error = 'Invalid username/password. Try again.';
    }
}

  
  require_once '../includes/header.php';
  require_once '../tools/variables.php';
  $page_title = 'Loading...';
?>
<body id="sign-up">
<div class="loading-screen">
  <img class="logo" src="../img/logo-edit.png" alt="logo">
  <?php echo $page_title; ?>
  <div class="loading-bar"></div>
</div>
    <main class="py-md-4">
        <div class="container-fluid d-flex align-items-md-center justify-content-md-center">
            <div class="container-fluid sign-in p-sm-5 align-items-md-center">
                <div class="row">
                    <div class="col">
                        <h2 class="fw-bold text-center green">Welcome!</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <form class="needs-validation mt-3" action="login.php" method="post">
                            <div class="row g-3">
                                <div class="col-12">
                                    <input type="text" class="log-control text-light" id="email" name="email" placeholder="Email" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="password-container">
                                        <input type="password" class="log-control text-light" id="password" name="password" placeholder="Password" required>
                                        <span class="password-toggle-icon text-light fa fa-eye-slash"></span>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid password.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-check-inline me-0">
                                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember_me" value="1">
                                        <label class="form-check-label" for="remember-me">Remember me
                                            <?php
                                            require_once 'remember_me.php'; 
                                            ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 pt-2 mb-3">
                                    <input class="btn btn-lg btn-success background-color-green btn-continue btn-font" type="submit" value="Login" name="login"  id="login-button">
                                </div>
                                <div class="form-check form-check-inline me-0 mt-0">
                                        <a class="green text-decoration-none text-center" href="forgot_pass.php?reset=yes">Forgot password?</a>
                                    </div>
                                <?php
                                    //Display the error message if there is any.
                                    if(isset($error)){
                                        echo '<div><p class="error">'.$error.'</p></div>';
                                    }
                                    
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script>
$(function() {
    $('.password-toggle-icon').click(function() {
        var input = $(this).prev('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });
});
</script>

</body>


