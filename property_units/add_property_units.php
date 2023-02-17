<?php
  require_once '../tools/variables.php';
  require_once '../includes/dbconfig.php';

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

    $page_title = 'RMS | Add Property Units';
    $p_units = 'active';
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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">PROPERTY UNITS</h3>
                </div>
                <div class="add-page-container">
                  <div class="col-md-2 d-flex justify-align-between float-right">
                    <a href="property_units.php" class='bx bx-caret-left'>Back</a>
                </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title fw-bolder">Property Unit Details</h4>
            <form action="add_property_units.php" method="post" class="form-sample">
              <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="unit_name">Property Name</label>
                        <input  class="form-control form-control-sm " placeholder="Property Unit Name" type="text" id="unit_name" name="unit_name" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                      <label for="monthly_rent">Monthly Rent Amount</label>
                        <input class="form-control form-control-sm" placeholder=""  type="number" id="monthly_rent" name="monthly_rent_amount" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="main_property">Select Main Property</label>
                        <select class="form-control form-control-sm" placeholder="" id="main_property" name="main_property" required>
                        <option value="none">--Select--</option>
                        <?php
                                    require_once '../classes/reference.class.php';
                                    $ref_obj = new Reference();
                                    $ref = $ref_obj->get_main_pro($_POST['filter']);
                                    foreach($ref as $row){
                                ?>
                                        <option value="<?=$row['id']?>"><?=$row['property_name']?></option>
                                <?php
                                    }
                                    ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="unit_condition">Select Unit Condition</label>
                        <select class="form-control form-control-sm" placeholder="" id="unit_condition" name="unit_condition" required>
                        <option value="none">--Select--</option>
                        <?php
                                    require_once '../classes/reference.class.php';
                                    $ref_obj = new Reference();
                                    $ref = $ref_obj->get_unit_con($_POST['filter']);
                                    foreach($ref as $row){
                                ?>
                                        <option value="<?=$row['id']?>"><?=$row['condition_name']?></option>
                                <?php
                                    }
                                    ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-row">
                      <div class="col">
                        <label for="unit_type">Select Type of Unit</label>
                        <select class="form-control form-control-sm" placeholder="" id="unit_type" name="unit_type" required>
                        <option value="none">--Select--</option>
                        <?php
                                    require_once '../classes/reference.class.php';
                                    $ref_obj = new Reference();
                                    $ref = $ref_obj->get_unit_type($_POST['filter']);
                                    foreach($ref as $row){
                                ?>
                                        <option value="<?=$row['id']?>"><?=$row['type_name']?></option>
                                <?php
                                    }
                                    ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                            <div class="form-group-row">
                              <div class="col">
                                  <label for="property_description">Description of the Property Unit</label>
                                  <textarea class="form-control form-control-lg" id="property_description" name="property_description" col="100" row="20"></textarea>
                                </div>
                              </div>
                            </div>
                            <div class="ps-6">
                    <input type="submit" class="btn btn-success btn-sm" value="Save Unit" name="save" id="save">
                  </div>
              </div>
            </form> 
          </div>
        </div>
    </div>
  </div>
</div>



                  


                       
