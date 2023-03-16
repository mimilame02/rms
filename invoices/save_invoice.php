<?php
require_once '../classes/database.php';
require_once '../classes/leases.class.php';
require_once '../classes/invoices.class.php';
require_once '../classes/penalty.class.php';

$db = new Database();
$leases = new Leases($db);
$penalties = new Penalties($db);

$invoice = new Invoice();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action']) && $_POST['action'] == 'fetch_lease_data' && isset($_POST['lease_unit_id'])) {
    $lease_unit_id = $_POST['lease_unit_id'];

    // Fetch lease unit data based on lease_unit_id using the existing lease_fetch function
    $lease_data = $leases->lease_fetch($lease_unit_id);

    // Calculate rent_due_date based on lease_end (3 months from lease_start)
    $lease_end = new DateTime($lease_data['lease_end']);
    $rent_due_date = $lease_end->sub(new DateInterval('P3M'))->format('Y-m-d');

    // Add rent_due_date to lease_data
    $lease_data['rent_due_date'] = $rent_due_date;

    // Return the data as JSON
    echo json_encode($lease_data);

  } elseif (isset($_POST['action']) && $_POST['action'] == 'fetch_penalty_amount' && isset($_POST['penalty_id'])) {
    $penalty_id = $_POST['penalty_id'];

    // Fetch penalty data based on penalty_id
    $penalty_data = $penalties->fetch_penalty($penalty_id);

    // Return the penalty_amount as JSON
    echo json_encode(['penalty_amount' => $penalty_data['amount']]);

  } elseif (isset($_POST['action']) && $_POST['action'] == 'save_invoice') {
    // Set the invoice data from the submitted form
    $invoice->lease_unit_id = $_POST['lease_unit_id'];
    $invoice->tenant_id = $_POST['tenant_id'];
    $invoice->monthly_rent = $_POST['monthly_rent'];
    $invoice->rent_due_date = $_POST['rent_due_date'];
    $invoice->electricity = $_POST['electricity'];
    $invoice->water = $_POST['water'];
    $invoice->penalty_id = $_POST['penalty_id'];
    $invoice->rent_paid = 0; // Set to 0 initially, update when the tenant pays rent
    $invoice->balance = $invoice->monthly_rent + $invoice->electricity + $invoice->water + $invoice->penalty - $invoice->rent_paid;

    // Save the invoice
    if ($invoice->invoice_add()) {
      // Return success status as JSON
      echo json_encode(['status' => 'success']);
    } else {
      // Return error status as JSON
      echo json_encode(['status' => 'error']);
    }
  }
}
?>
