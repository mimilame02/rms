<?php
require_once '../includes/dbconfig.php';
require_once '../classes/invoices.class.php';
require_once '../classes/account.class.php';

//resume session here to fetch session values
session_start();


$invoice = new Invoice();

// Check if the form was submitted
if (isset($_POST['save'])) {
  // Fetch the submitted data
  $invoice->id = $_POST['invoice_id'];
  $invoice->payment_date = $_POST['payment_date'];
  $invoice->amount_paid = $_POST['amount_paid'];
  $invoice->balance = $_POST['balance'];
  $invoice->status = 'Paid';


  // Check the result and redirect accordingly
  if ($invoice->update_invoice_pay()) {
      header('Location: invoices.php?success=1');
  } else {
      header('Location: show_invoice.php?error=db_error');
  }
}

?>