<?php
  require_once '../includes/header.php';
  require_once '../tools/variables.php';
  $page_title = 'forgot_password';

  
  require_once '../includes/dbconfig.php';
?>
<body id="fp">
<main class="py-md-4">
        <div class="container-fluid d-flex align-items-md-center justify-content-md-center">
        <div class="container-fluid fp p-sm-5">
                <div class="row">
                    <div class="col-12 text-center">
                        <i class="fa-solid fa-envelope-open-text ff"></i>
                    </div>
                    <div class="col-12 mt-3">
                        <h2 class="fw-bold text-center ff">Update your password</h2>
                    </div>
                    <div class="col-12 text-center">
                        <p class="lbl text-center ff">
                            Enter your email address and click <span class="fw-bold">Reset Password</span>.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <form class="needs-validation">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Address<span class="text-muted"></span></label>
                                    <input type="email" class="form-control" id="email" placeholder="" required>
                                    <div class="invalid-feedback ff">
                                        Please enter a valid email address.
                                    </div>
                                </div>
                                <div class="col-12 pt-2 mb-3">
                                    <button class="btn btn-lg btn-success background-color-green btn-continue btn-font" type="submit">Send Email</button>
                                </div>
                                <div class="row mt-3">
                                  <p class="text-center ff">
                                     Go back to  <a class="ff fw-bold" href="login.php"> Login</a>
                                  </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>