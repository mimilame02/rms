<?php

function validate_first_name($POST) {
  if (!isset($POST['first_name']) || trim($POST['first_name']) == '') {
      return false;
  }
  return true;
}

function validate_last_name($POST) {
  if (!isset($POST['last_name']) || trim($POST['last_name']) == '') {
      return false;
  }
  return true;
}

function validate_email($POST) {
  if (!isset($POST['email']) || trim($POST['email']) == '') {
      return false;
  }
  if (!filter_var($POST['email'], FILTER_VALIDATE_EMAIL)) {
      return false;
  }
  return true;
}

function validate_contact_num($POST) {
  if (!isset($POST['contact_num']) || trim($POST['contact_num']) =='') {
    return false;
  }
  return true;
}

function validate_add_tenants($POST) {
  if (!validate_first_name($POST) || !validate_last_name($POST) || !validate_email($POST) ||
      !validate_contact_num($POST)) {
    return false;
  }
  return true;
}

function validate_add_landlord($post) {
  $errors = [];
  if(empty($post['firstname'])) {
     $errors['firstname'] = "First Name is required";
  }
  if(empty($post['lastname'])) {
     $errors['lastname'] = "Last Name is required";
  }
  if(empty($post['email'])) {
     $errors['email'] = "Email is required";
  }
  if(empty($post['contact_num'])) {
     $errors['contact_num'] = "Contact Number is required";
  }
  if(empty($post['address'])) {
     $errors['address'] = "Address is required";
  }
  if(empty($post['city'])) {
     $errors['city'] = "City is required";
  }
  if(empty($post['province'])) {
     $errors['province'] = "Province is required";
  }
  if(empty($post['zip'])) {
     $errors['zip'] = "Zip Code is required";
  }
  if(empty($post['id_doc'])) {
     $errors['id_doc'] = "Identification Document is required";
  }
  if(empty($post['fname'])) {
     $errors['fname'] = "Emergency Contact Name is required";
  }
  if(empty($post['emergency_num'])) {
     $errors['emergency_num'] = "Emergency Contact Number is required";
  }
  if(count($errors) > 0) {
     $_SESSION['errors'] = $errors;
     return false;
  }
  return true;
}


?>