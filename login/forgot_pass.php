<?php
require_once '../includes/header.php';
require_once '../tools/variables.php';
$page_title = 'Reset Password';
$forgot_password = 'active';

require_once '../includes/dbconfig.php';
require_once '../classes/account.class.php';

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $query = "SELECT * FROM account WHERE id = :id;";
    $account_obj = new Account();
    $data = $account_obj->get_account_info_by_email($email);
    if (!empty($data)) {
        // Email exists in the database, display the form to enter a new password
        $_SESSION['account_id'] = $data[0]['id'];
        // Email exists in the database, display the form to enter a new password
        $success_msg = "An email with instructions to reset your password will be sent to your email address shortly.";
    } else {
        $error_msg = "The email address you provided does not exist in our system.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['account_id'])) {
    if (isset($_POST['new_password'])) {
        $new_password = $_POST['new_password'];
        if (!empty($new_password)) {
            $account_obj = new Account();
            $account_obj->account_reset($account_id, $new_password);
            //redirect user to landing page after saving
            header('location: login.php');
            exit;
        } else {
            $error_msg = "Please enter a new password.";
        }
    }
}

?>

<body id="fp">
    <main class="py-md-4">
        <div class="container-fluid d-flex align-items-md-center justify-content-md-center">
            <div class="container-fluid fp p-sm-5">
                <form method="POST" class="needs-validation" id="new-password-form">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h2 class="fw-bold text-center ff">Update your password</h2>
                            <p class="lbl text-center ff">
                                Enter your new password and click <span class="fw-bold">Save Password</span>.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="new_password" class="form-label">New Password<span class="text-muted"></span></label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="" required>
                                    <div class="invalid-feedback ff">
                                        Please enter a new password.
                                    </div>
                                </div>
                                <div class="col-12 pt-2 mb-3">
                                    <button class="btn btn-lg btn-success background-color-green btn-continue btn-font" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


