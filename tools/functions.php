<?php

function pluralize($noun, $count) {
  if ($count == 1) {
      return $noun;
  }

  $last_letter = strtolower($noun[strlen($noun) - 1]);
  switch ($last_letter) {
      case 's':
      case 'x':
      case 'z':
      case 'o':
          return $noun . 'es';
          break;
      case 'y':
          return substr($noun, 0, -1) . 'ies';
          break;
      default:
          return $noun . 's';
  }
}

/* tenant validation */
/* In validate_first_name, the condition to check if 'region' is set should be removed. It is not relevant to the first name validation. */
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
   $middle_name = strip_tags(trim($POST['middle_name']));
   if (preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ-]+$/', $middle_name)) {
     // Returns false if the string contains anything other than letters, spaces or dashes.
     return false;
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
  // Remove all non-letter, non-digit characters from the input using a regular expression
$letters_digits = preg_replace("/^[0-9]*\s*[a-zA-Z0-9\s,.'()\[\]`{|}~-]+$/", '', $POST['address']);

// Check if the input contains only letters and digits using a regular expression
if (preg_match("/^[0-9]*\s*[a-zA-Z0-9\s,.'()\[\]`{|}~-]+$/", $letters_digits)) {
 // If the input contains only letters and digits, return the sanitized input
 return true;
} else {
 // If the input contains non-letter, non-digit characters, return false
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
  }else if(strcmp($POST['relationship_status'], 'None') == 0){
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

/* In validate_full_name, you should change the condition inside preg_match to return true if the pattern matches, and false otherwise.*/
function validate_full_name($POST) {
  $emergency_contact_person = strip_tags(trim($POST['emergency_contact_person']));
  if (!preg_match('/^([A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+[\s-]?){2,}[A-Za-zÀ-ÖØ-öø-ÿĀ-ȳ]+$/', $emergency_contact_person)) {
      // Returns false if the string contains anything other than letters, spaces or dashes.
      return false;
  }
  return true;
}

/* In validate_econtact_no, you have missed the second argument in the preg_replace function.*/
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
  // Trim the input and Strip HTML tags
  $propertyName = strip_tags(trim($POST['property_name']));

  // Limit the length
  $max_length = 100;
  if (strlen($propertyName) > $max_length) {
      return false;
  }

  // Check for invalid characters
  if (preg_match('/[^A-Za-z0-9\-_.,\s]/', $propertyName)) {
      // Returns false if the string contains anything other than allowed characters.
      return false;
  }

  return true;
}

function validate_street($POST){
  // Remove all non-letter, non-digit characters from the input using a regular expression
$letters_digits = preg_replace('/[^a-zA-Z0-9]/', '', $POST['street']);

// Check if the input contains only letters and digits using a regular expression
if (preg_match('/^[a-zA-Z0-9]+$/', $letters_digits)) {
 // If the input contains only letters and digits, return the sanitized input
 return true;
} else {
 // If the input contains non-letter, non-digit characters, return false
 return false;
}
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
  $description = $POST['property_description'];
  return strlen($description) <= $max_length;
}

function validate_features_description($POST) {
  $max_length = 500; // Set the maximum length
  $features_description = $POST['features_description'];
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

/* function validate_image_path($FILES) {
  // Validate if the uploaded file is an image
  $image_path = $FILES['image_path'];
  $image_info = getimagesize($image_path['tmp_name']);
  return $image_info !== false;
}

function validate_floor_plan($FILES) {
  // Validate if the uploaded file is an image
  $floor_plan = $FILES['floor_plan'];
  $image_info = getimagesize($floor_plan['tmp_name']);
  return $image_info !== false;
} */

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
  if (!validate_first_name($post) || !validate_middle_name($post) || !validate_last_name($post) || !validate_email($post) || !validate_contact_num($post) || !validate_date_birth($post) || !validate_address($post) || !validate_region($post) || !validate_prov($post) || !validate_city($post) || !validate_full_name($post) || !validate_econtact_no($post)){
    return false;
  }
  return true;
}

function validate_add_properties($post) {
  if (!validate_property_name($post) ||
      !validate_property_description($post) ||
      !validate_num_of_floors($post) ||
      !validate_landlord_id($post) ||
      !validate_region($post) ||
      !validate_prov($post) ||
      !validate_city($post) ||
      !validate_brgy($post) ||
      !validate_street($post) ||
      !validate_features_description($post) ||
      !validate_features($post)
  ) {
      return false;
  }
  return true;
}


?>