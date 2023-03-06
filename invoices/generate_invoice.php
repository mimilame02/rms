<?php
  require_once '../tools/functions.php';
  require_once '../classes/database.php';
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

    require_once '../tools/variables.php';
    $page_title = 'RMS | Generate Invoice';
    $leases = 'active';

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
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-bolder">GENERATE INVOICE</h3> 
      </div>
      <div class="add-page-container">
        <div id="settings-trigger"><i class='bx bx-calculator'></i></div>
      </div>
    </div>
    <div id="calculator" style="display:none;">
  <input type="text" id="result" readonly>
  <table>
    <tr>
      <td><button onclick="addToResult(7)">7</button></td>
      <td><button onclick="addToResult(8)">8</button></td>
      <td><button onclick="addToResult(9)">9</button></td>
      <td><button onclick="addToResult('+')">+</button></td>
    </tr>
    <tr>
      <td><button onclick="addToResult(4)">4</button></td>
      <td><button onclick="addToResult(5)">5</button></td>
      <td><button onclick="addToResult(6)">6</button></td>
      <td><button onclick="addToResult('-')">-</button></td>
    </tr>
    <tr>
      <td><button onclick="addToResult(1)">1</button></td>
      <td><button onclick="addToResult(2)">2</button></td>
      <td><button onclick="addToResult(3)">3</button></td>
      <td><button onclick="addToResult('*')">*</button></td>
    </tr>
    <tr>
      <td><button onclick="addToResult(0)">0</button></td>
      <td><button onclick="addToResult('.')">.</button></td>
      <td><button onclick="calculate()">=</button></td>
      <td><button onclick="addToResult('/')">/</button></td>
    </tr>
    <tr>
      <td><button onclick="clearResult()">C</button></td>
    </tr>
  </table>
</div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Invoice Details </h4>
                  <form class="forms-sample">
                    <div class="form-group">
                      <label for="property_unit_name">Leased Unit</label><span class="req"> *</span>
                      <select name="property_unit_name" id="property_unit_name" class="form-select">
                        <option value="">-- Select --</option>
                          <!-- Populate this select with the list of property units -->
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="monthly_rent">Tenant Name</label>
                      <input type="number" class="form-control" id="monthly_rent" placeholder="(default)"disabled>
                    </div>
                    <div class="form-group">
                      <label for="rent_paid">Rent Due Date</label>
                      <input type="number" class="form-control" id="monthly_rent" placeholder="(default)"disabled>
                    </div>
                    <h4 class="card-title">Billing</h4>
                    <div class="form-group">
                      <label for="rent_paid">Monthly Rent</label>
                      <input type="number" class="form-control" id="monthly_rent" placeholder="(default)"disabled>
                    </div>
                    <div class="form-group">
                      <label for="rent_paid">Electricity</label>
                      <input type="number" class="form-control" id="monthly_rent" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="rent_paid">Water</label>
                      <input type="number" class="form-control" id="monthly_rent" placeholder="">
                    </div>
                    
                   
                    <button type="submit" class="btn btn-primary float-right mr-2">Save</button>
                </div>
              </div>
  </div>
                </form>
                </div>
              </body>

<script>
  // Get the calculator element
  const calculator = document.getElementById("calculator");

  // Show the calculator when the settings-trigger is clicked
  document.getElementById("settings-trigger").addEventListener("click", function() {
    calculator.style.display = "block";
  });

  // Hide the calculator when a user clicks outside of it
  window.addEventListener("click", function(event) {
    if (event.target != calculator && event.target.parentNode != calculator) {
      calculator.style.display = "none";
    }
  });

  // Functions to handle the calculator buttons
  function addToResult(value) {
    document.getElementById("result").value += value;
  }

  function calculate() {
    const result = eval(document.getElementById("result").value);
    document.getElementById("result").value = result;
  }

  function clearResult() {
    document.getElementById("result").value = "";
  }
</script>