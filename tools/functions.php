<?php

function pluralize($noun, $count) {
  if ($count == 1) {
    return $noun;
  }
  
  $last_letter = strtolower($noun[strlen($noun) - 1]);
  $second_last_letter = strtolower($noun[strlen($noun) - 2]);

  switch ($last_letter) {
    case 's':
    case 'x':
    case 'z':
      return $noun . 'es';
    case 'o':
      if ($second_last_letter == 'o' || $second_last_letter == 's') {
        return $noun . 'es';
      } else {
        return $noun . 's';
      }
    case 'y':
      if ($second_last_letter == 'a' || $second_last_letter == 'e' || $second_last_letter == 'i' || $second_last_letter == 'o' || $second_last_letter == 'u') {
        return $noun . 's';
      } else {
        return substr($noun, 0, -1) . 'ies';
      }
    default:
      return $noun . 's';
  }
}



/* tenant validation */
function validate_first_name($POST) {
  if(!isset($POST['first_name'])){
   $first_name = strip_tags(trim($POST['first_name']));
   if (!preg_match('/^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/', $first_name)) {
    // Returns false if the string contains anything other than letters, spaces or dashes.
     return false;
   }
   return false;
 }
 return true;
}

 function validate_middle_name($POST) {
  if(!isset($POST['middle_name'])){
   $middle_name = strip_tags(trim($POST['middle_name']));
   if (preg_match('/^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/', $middle_name)) {
     // Returns false if the string contains anything other than letters, spaces or dashes.
     return false;
   }
    return true; 
  }
 return true;
}

 function validate_last_name($POST) {
  if(!isset($POST['last_name'])){
   $last_name = strip_tags(trim($POST['last_name']));
   if (!preg_match('/^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/', $last_name)) {
    // Returns false if the string contains anything other than letters, spaces or dashes.
     return false;
   }
   return false;
 }
 return true;
}

function validate_date_birth($POST) {
   // Sanitize the date input and convert to a timestamp
   $timestamp = strtotime(filter_var(trim($POST['date_of_birth']), FILTER_SANITIZE_STRING));
 
   // Calculate the age of the person based on the timestamp
   $age = (int) ((time() - $timestamp) / 31536000); // 31536000 = 1 year in seconds
 
   // Check if the person is 18 years old or above
   if ($age >= 18) {
     // If the person is 18 years old or above, return the sanitized date
     return true;
   } else {
     // If the person is under 18 years old, return false
     return false;
   }
 }

 function validate_email($POST) {
  // Remove any tags and white space from the email address
  $email = filter_var(trim($POST['email']), FILTER_SANITIZE_EMAIL);

  // Validate the email address using the provided regex pattern
  if (preg_match('/^[a-zA-Z0-9.!#$%&’()*+\/=?^_`{|}~\[\]-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/', $email)) {
      // If the email address is valid, return true
      return true;
  } else {
      // If the email address is not valid, return false
      return false;
  }
}


function validate_contact_num($POST) {
  // Remove all non-digit characters from the input using a regular expression
  $digits = preg_replace('/\D/', '', $POST['contact_no']);

  // Check if the input contains only digits
  if (ctype_digit($digits)) {
    // If the input contains only digits, return the sanitized input
    return true;
  } else {
    // If the input contains non-digit characters, return false
    return false;
  }
}



function validate_prev_address($POST) {
  // Check if the input matches the pattern
  if (preg_match('/^[0-9]*\s*[a-zA-Z\s]+([,.-]?[a-zA-Z0-9\s]+)*$/', $POST['previous_address'])) {
    return true;
  } else {
    return false;
  }
}

function validate_address($POST){
  // Check if the input contains only letters and digits using a regular expression
  if (preg_match("/^[0-9]*\s*[a-zA-Z0-9\s,.'()\[\]`{|}~-]+$/", $POST['address'])) {
    return true;
  } else {
    return false;
  }
}

function validate_region($POST){
   if(!isset($POST['region'])){
       return false;
   }else if(strcmp($POST['region'], 'None') == 0){
       return false;
   }
   return true;
}

function validate_prov($POST){
   if(!isset($POST['provinces'])){
       return false;
   }else if(strcmp($POST['provinces'], 'None') == 0){
       return false;
   }
   return true;
}

function validate_city($POST){
  if(!isset($POST['city'])){
      return false;
  }else if(strcmp($POST['city'], 'None') == 0){
      return false;
  }
  return true;
}

function validate_brgy($POST){
  if(!isset($POST['barangay'])){
      return false;
  }else if(strcmp($POST['barangay'], 'None') == 0){
      return false;
  }
  return true;
}


function validate_sex($POST){
   if(!isset($POST['sex'])){
      return false;
  }else if(strcmp($POST['sex'], 'None') == 0){
      return false;
  }
  return true;
}

function validate_has_pet($POST){
   if(!isset($POST['has_pet'])){
      return false;
   }
   return true;
}

function validate_pet_type($POST){
  if ($POST['has_pet'] === 'No') {
      return true;
  } elseif (isset($POST['type_of_pet'])) {
      $type_of_pet = strip_tags(trim($POST['type_of_pet']));
      if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ-]+$/', $type_of_pet)) {
          // Returns false if the string contains anything other than letters, spaces or dashes.
          return false;
      }
      return true;
  } else {
      return false;
  }
}


function validate_civil_status($POST){
  if(!isset($POST['relationship_status'])){
     return false;
  }
  return true;
}


function validate_is_smoking($POST){
   if(!isset($POST['is_smoking'])){
       return false;
   }
   return true;
}

function validate_house($POST){
   if(!isset($POST['type_of_household'])){
       return false;
   }else if(strcmp($POST['type_of_household'], 'None') == 0){
       return false;
   }
   return true;
}

function validate_full_name($POST) {
  $emergency_contact_person = strip_tags(trim($POST['emergency_contact_person']));
  if (!preg_match('/^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/', $emergency_contact_person)) {
      // Returns false if the string contains anything other than letters, spaces or dashes.
      return false;
  }
  return true;
}

function validate_econtact_no($POST) {
  // Remove all non-digit characters from the input using a regular expression
  $emergency_contact_number = preg_replace('/\D/', '', $POST['emergency_contact_number']);

  // Check if the input contains only digits
  if (ctype_digit($emergency_contact_number)) {
    // If the input contains only digits, return the sanitized input
    return true;
  } else {
    // If the input contains non-digit characters, return false
    return false;
  }
}


function validate_property_name($POST) {
  $property_name = strip_tags(trim($POST['property_name']));
  $namePattern = '/^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/';
  return preg_match($namePattern, $property_name);
}

function validate_street($POST) {
  $street = strip_tags(trim($POST['street']));

  // Return true if the street input is empty
  if (empty($street)) {
    return true;
  }

  return preg_match('/^[0-9]*\s*[a-zA-Z0-9\s,.\'()\[\]`{|}~-]+$/', $street);
}


function validate_select($POST, $key) {
  if (!isset($POST[$key])) {
    return false;
  }
  $value = strip_tags(trim($POST[$key]));
  return $value !== "";
}


function validate_landlord_id($POST) {
  if(!isset($POST['landlord'])){
    return false;
  }else if(strcmp($POST['landlord'], 'None') == 0){
      return false;
  }
  return true;
}

function validate_property_description($POST) {
  $max_length = 500; // Set the maximum length
  $description = str_replace("\n", "", $POST['property_description']); // Remove newline characters
  return strlen($description) <= $max_length;
}

function validate_features_description($POST) {
  $max_length = 500; // Set the maximum length
  $features_description = str_replace("\n", "", $POST['features_description']); // Remove newline characters
  return strlen($features_description) <= $max_length;
}


function validate_features($POST) {
  $features = $POST['features'];
  return !empty($features);
}

function validate_num_of_floors($POST) {
  $num_of_floors = $POST['num_of_floors'];
  return is_numeric($num_of_floors) && $num_of_floors > 0;
}

function validate_image_path($files, $input_field_name) {
  foreach ($files['tmp_name'] as $tmp_name) {
      $image_info = getimagesize($tmp_name);
      if ($image_info === false) {
          return false;
      }

      $allowed_extensions = array('jpg', 'jpeg', 'png');
      $extension = pathinfo($tmp_name, PATHINFO_EXTENSION);
      if (!in_array($extension, $allowed_extensions)) {
          return false;
      }
  }
  return true;
}

function validate_floor_plan($files, $input_field_name) {
  foreach ($files['tmp_name'] as $tmp_name) {
      $image_info = getimagesize($tmp_name);
      if ($image_info === false) {
          return false;
      }

      $allowed_extensions = array('jpg', 'jpeg', 'png');
      $extension = pathinfo($tmp_name, PATHINFO_EXTENSION);
      if (!in_array($extension, $allowed_extensions)) {
          return false;
      }
  }
  return true;
}



 

function validate_tenants($POST) {
  $validation_results = [
    'validate_first_name' => validate_first_name($POST),
    'validate_middle_name' => validate_middle_name($POST),
    'validate_last_name' => validate_last_name($POST),
    'validate_email' => validate_email($POST),
    'validate_contact_num' => validate_contact_num($POST),
    'validate_sex' => validate_sex($POST),
    'validate_has_pet' => validate_has_pet($POST),
    'validate_date_birth' => validate_date_birth($POST),
    'validate_prev_address' => validate_prev_address($POST),
    'validate_region' => validate_region($POST),
    'validate_prov' => validate_prov($POST),
    'validate_pet_type' => validate_pet_type($POST),
    'validate_civil_status' => validate_civil_status($POST),
    'validate_is_smoking' => validate_is_smoking($POST),
    'validate_house' => validate_house($POST),
    'validate_city' => validate_city($POST),
    'validate_full_name' => validate_full_name($POST),
    'validate_econtact_no' => validate_econtact_no($POST)
  ];

  foreach ($validation_results as $function => $result) {
    echo $function . ": " . ($result ? "PASS" : "FAIL") . "<br>";
  }
  if (!validate_first_name($POST)) {
    echo "Failing first name: " . $POST['first_name'] . "<br>";
}
if (!validate_last_name($POST)) {
    echo "Failing last name: " . $POST['last_name'] . "<br>";
}
if (!validate_email($POST)) {
    echo "Failing email: " . $POST['email'] . "<br>";
}
if (!validate_pet_type($POST)) {
    echo "Failing pet type: " . (isset($POST['type_of_pet']) ? $POST['type_of_pet'] : 'Not set') . "<br>";
}
if (!validate_full_name($POST)) {
    echo "Failing emergency contact person: " . $POST['emergency_contact_person'] . "<br>";
}
if (!validate_econtact_no($POST)) {
    echo "Failing emergency contact number: " . $POST['emergency_contact_number'] . "<br>";
}


  return !in_array(false, $validation_results);
}



function validate_add_landlord($post) {
  $validation_results = [
    'validate_first_name' => validate_first_name($post),
    'validate_middle_name' => validate_middle_name($post),
    'validate_last_name' => validate_last_name($post),
    'validate_email' => validate_email($post),
    'validate_contact_num' => validate_contact_num($post),
    'validate_date_birth' => validate_date_birth($post),
    'validate_address' => validate_address($post),
    'validate_region' => validate_region($post),
    'validate_prov' => validate_prov($post),
    'validate_city' => validate_city($post),
    'validate_full_name' => validate_full_name($post),
    'validate_econtact_no' => validate_econtact_no($post)
  ];

  foreach ($validation_results as $function => $result) {
    echo $function . ": " . ($result ? "PASS" : "FAIL") . "<br>";
  }
  
  if (!validate_first_name($post)) {
    echo "Failing first name: " . $post['first_name'] . "<br>";
  }
  
  if (!validate_last_name($post)) {
    echo "Failing last name: " . $post['last_name'] . "<br>";
  }
  
  if (!validate_email($post)) {
    echo "Failing email: " . $post['email'] . "<br>";
  }
  
  if (!validate_full_name($post)) {
    echo "Failing emergency contact person: " . $post['emergency_contact_person'] . "<br>";
  }
  
  if (!validate_econtact_no($post)) {
    echo "Failing emergency contact number: " . $post['emergency_contact_number'] . "<br>";
  }

  return !in_array(false, $validation_results);
}


function validate_add_properties($post, $files) {
  $validation_results = array(
    "validate_property_name" => validate_property_name($post),
    "validate_property_description" => validate_property_description($post),
    "validate_num_of_floors" => validate_num_of_floors($post),
    "validate_landlord_id" => validate_landlord_id($post),
    "validate_region" => validate_select($post, 'region'),
    "validate_province" => validate_select($post, 'provinces'),
    "validate_city" => validate_select($post, 'city'),
    "validate_brgy" => validate_select($post, 'barangay'),
    "validate_street" => validate_street($post),
    "validate_features_description" => validate_features_description($post),
    "validate_features" => validate_features($post),
    "validate_image_path" => validate_image_path($files,'image_path'),
    "validate_floor_plan" => validate_floor_plan($files,'floor_plan')

    // Add any other validation functions you have
  );

  // Debugging output
  foreach ($validation_results as $function => $result) {
    echo $function . ": " . ($result ? "PASS" : "FAIL") . "<br>";   
  }

  // Check if any validation failed
  if (in_array(false, $validation_results, true)) {
    return false;
  }
  return true;
}

function validate_main_property($POST) {
  if(!isset($POST['main_property'])){
    return false;
  }else if(strcmp($POST['main_property'], 'None') == 0){
      return false;
  }
  return true;
}
function validate_unit_type($POST) {
  if(!isset($POST['unit_type'])){
    return false;
  }else if(strcmp($POST['unit_type'], 'None') == 0){
      return false;
  }
  return true;
}
function validate_unit_condition($POST) {
  if(!isset($POST['unit_condition'])){
    return false;
  }else if(strcmp($POST['unit_condition'], 'None') == 0){
      return false;
  }
  return true;
}
function validate_unit_no($POST) {
  if (!isset($POST['unit_no'])) {
    return false;
  }
  $unit_no = $POST['unit_no'];
  return preg_match('/^[a-zA-Z0-9\-]+$/i', $unit_no);
}

function validate_floor_level($POST) {
  $floor_level = $POST['floor_level'];
  return is_numeric($floor_level) && $floor_level > 0;
}
function validate_num_rooms($POST) {
  $num_rooms = $POST['num_rooms'];
  return is_numeric($num_rooms) && $num_rooms > 0;
}
function validate_num_bathrooms($POST) {
  $num_bathrooms = $POST['num_bathrooms'];
  return is_numeric($num_bathrooms) && $num_bathrooms > 0;
}

function validate_featured($POST) {
  if (!isset($POST['pu_features']) || !is_array($POST['pu_features'])) {
    return false;
  }
  return count($POST['pu_features']) > 0;
}




function validate_add_property_unit($post) {
  $validation_results = array(
    "validate_main_property" => validate_main_property($post),
    "validate_unit_type" => validate_unit_type($post),
    "validate_unit_condition" => validate_unit_condition($post),
    "validate_unit_no" => validate_unit_no($post),
    "validate_floor_level" => validate_floor_level($post),
    "validate_num_rooms" => validate_num_rooms($post),
    "validate_num_bathrooms" => validate_num_bathrooms($post),
    "validate_featured" => validate_featured($post),
    "validate_status" => validate_status($post),
    "validate_monthly_rent" => validate_monthly_rent($post),
    "validate_one_month_deposit" => validate_one_month_deposit($post),
    "validate_one_month_advance" => validate_one_month_advance($post)

    // Add any other validation functions you have
  );

  // Debugging output
  foreach ($validation_results as $function => $result) {
    echo $function . ": " . ($result ? "PASS" : "FAIL") . "<br>";   
  }

  // Check if any validation failed
  if (in_array(false, $validation_results, true)) {
    return false;
  }
  return true;
}

function validate_main_prop($POST) {
  if(!isset($POST['property_name'])){
    return false;
  }else if(strcmp($POST['property_name'], 'None') == 0){
      return false;
  }
  return true;
}
function validate_property_unit_id($post) {
  if (isset($post['property_unit']) && !empty($post['property_unit'])) {
    return true;
  }
  return false;
}

function validate_tenant_id($post) {
  if (isset($post['tenant_name']) && !empty($post['tenant_name'])) {
    return true;
  }
  return false;
}

function validate_lease_start($post) {
  if (isset($post['lease_start']) && !empty($post['lease_start'])) {
    $last_month = strtotime("-1 month");
    $lease_start = strtotime($post['lease_start']);
    return $lease_start >= $last_month;
  }
  return false;
}

function validate_lease_end($post) {
  if (isset($post['lease_start']) && isset($post['lease_end']) && !empty($post['lease_start']) && !empty($post['lease_end'])) {
    $lease_start = strtotime($post['lease_start']);
    $lease_end = strtotime($post['lease_end']);
    $minimum_end_date = strtotime("+3 months", $lease_start);
    return $lease_end >= $minimum_end_date;
  }
  return false;
}
function validate_monthly_rent($POST) {
  $monthly_rent = $POST['monthly_rent'];
  return is_numeric($monthly_rent) && $monthly_rent > 2500;
}
function validate_one_month_deposit($POST) {
  $one_month_deposit = $POST['one_month_deposit'];
  return is_numeric($one_month_deposit) && $one_month_deposit > 2500;
}

function validate_one_month_advance($POST) {
  $one_month_advance = $POST['one_month_advance'];
  return is_numeric($one_month_advance) && $one_month_advance > 2500;
}

function validate_electricity($post) {
  if (isset($post['electricity']) && !empty($post['electricity'])) {
    return is_numeric($post['electricity']);
  }
  return false;
}

function validate_water($post) {
  if (isset($post['water']) && !empty($post['water'])) {
    return is_numeric($post['water']);
  }
  return false;
}
function validate_lease_doc($lease_docs) {
  if (!isset($lease_docs) || !isset($lease_docs['tmp_name']) || !is_array($lease_docs['tmp_name']) || count($lease_docs['tmp_name']) == 0) {
    echo "Error: lease_doc missing or empty<br>";
    return false;
  }
  foreach ($lease_docs['tmp_name'] as $tmp_name) {
    $image_info = getimagesize($tmp_name);
    if ($image_info === false) {
      echo "Error: invalid image format<br>";
      return false;
    }
  }
  return true;
}


function validate_status($POST) {
  if(!isset($POST['status'])){
    return false;
  }else if(strcmp($POST['status'], 'None') == 0){
      return false;
  }
  return true;
}


/* function validate_add_lease($post, $files) {
  $validation_results = array(
    "validate_main_prop" => validate_main_prop($post),
    "validate_property_unit_id" => validate_property_unit_id($post),
    "validate_tenant_id" => validate_tenant_id($post),
    "validate_monthly_rent" => validate_monthly_rent($post),
    "validate_one_month_deposit" => validate_one_month_deposit($post),
    "validate_one_month_advance" => validate_one_month_advance($post),
    "validate_lease_start" => validate_lease_start($post),
    "validate_lease_end" => validate_lease_end($post),
    "validate_electricity" => validate_electricity($post),
    "validate_water" => validate_water($post),
    "validate_lease_doc" => validate_lease_doc($files['lease_doc'])
    // Add any other validation functions you have
  );

  // Debugging output
  foreach ($validation_results as $function => $result) {
    echo $function . ": " . ($result ? "PASS" : "FAIL") . "<br>";   
  }

  // Check if any validation failed
  if (in_array(false, $validation_results, true)) {
    return false;
  }
  return true;
} */



?>