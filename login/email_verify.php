<?php
require_once '../includes/header.php';
require_once '../tools/variables.php';
$page_title = 'Verify Email';

require_once '../includes/dbconfig.php';
require_once '../classes/account.class.php';

$error_msg = '';
$success_msg = '';

if (isset($_POST['email'])) {
    $account_obj = new Account();
    $email = $_POST['email'];
    $data = $account_obj->get_account_info_by_email($email);
    if (!empty($data)) {
        // Email exists in the database, display the form to enter a new password
        $success_msg = "An email with instructions to reset your password will be sent to your email address shortly.";
        //redirect user to landing page after saving
        header('location: forgot_pass.php');
        exit;
    } else {
        $error_msg = "The email address you provided does not exist in our system.";
    }
}

?>

<body id="fp">
    <main class="py-md-4">
        <div class="container-fluid d-flex align-items-md-center justify-content-md-center">
            <div class="container-fluid fp p-sm-5">
                <form method="POST" class="needs-validation" id="email-form">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="col-12 text-center">
                                <h2 class="fw-bold text-center ff">Update your password</h2>
                                <p class="lbl text-center ff">
                                    Enter your email address and click <span class="fw-bold">Reset Password</span>.
                                </p>
                            </div>
                            <?php if (!empty($error_msg)): ?>
                                <div class="col-12">
                                    <div class="alert alert-danger text-center" role="alert">
                                        <?php echo $error_msg; ?>
                                    </div>
                                </div>
                            <?php elseif (!empty($success_msg)): ?>
                                <div class="col-12">
                                    <div class="alert alert-success text-center" role="alert">
                                        <?php echo $success_msg; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <label for="email" class="form-label">Email Address<span class="text-muted"></span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="" required>
                            <div class="invalid-feedback ff">
                                Please enter a valid email address.
                            </div>
                        </div>
                        <div class="col-12 pt-2 mb-3">
                            <button class="btn btn-lg btn-success background-color-green btn-continue btn-font" type="submit">Reset Password</button>
                        </div>
                        <div class="row mt-3">
                            <p class="text-center ff">
                                Go back to  <a class="ff fw-bold" href="login.php">  Login</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>