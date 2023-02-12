<?php

require_once 'database.php';

class Tenant{

  public $id;
  public $first_name;
  public $last_name;
  public $email;
  public $contact_no;
  public $relationship_status;
  public $type_of_household;
  public $previous_addres;
  public $city;
  public $provinces;
  public $zip_code;
  public $sex;
  public $date_of_birth;
  public $has_pet;
  public $number_of_pets;
  public $type_of_pet;
  public $is_smoking;
  public $has_vehicle;
  public $vehicle_specification;
  public $occupants;
  public $co_applicant_first_name;
  public $co_applicant_last_name;
  public $co_applicant_email;
  public $co_applicant_contact_no;
  public $emergency_contact_person;
  public $emergency_contact_number;
  public $status;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function tenants_add() {
        $sql = "INSERT INTO tenant ( first_name, last_name, email, contact_no, relationship_status, type_of_household, previous_address, city, provinces, zip_code, sex, date_of_birth, has_pet, number_of_pets, type_of_pet, is_smoking, has_vehicle, vehicle_specification, occupants, co_applicant_first_name, co_applicant_last_name, co_applicant_email, co_applicant_contact_no, emergency_contact_person, emergency_contact_number, status) 
        VALUES (:first_name, :last_name, :email, :contact_no, :relationship_status, :type_of_household, :previous_address, :city, :provinces, :zip_code, :sex, :date_of_birth, :has_pet, :number_of_pets, :type_of_pet, :is_smoking, :has_vehicle, :vehicle_specification, :occupants, :co_applicant_first_name, :co_applicant_last_name, :co_applicant_email, :co_applicant_contact_no, :emergency_contact_person, :emergency_contact_number, :status)";
    
      $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':email', $this->email);
        $query->bindParam(':contact_no', $this->contact_no);
        $query->bindParam(':relationship_status', $this->relationship_status);
        $query->bindParam(':type_of_household', $this->type_of_household);
        $query->bindParam(':previous_address', $this->previous_address);
        $query->bindParam(':city', $this->city);
        $query->bindParam(':provinces', $this->provinces);
        $query->bindParam(':zip_code', $this->zip_code);
        $query->bindParam(':sex', $this->sex);
        $query->bindParam(':date_of_birth', $this->date_of_birth);
        $query->bindParam(':has_pet', $this->has_pet);
        $query->bindParam(':number_of_pets', $this->number_of_pets);
        $query->bindParam(':type_of_pet', $this->type_of_pet);
        $query->bindParam(':is_smoking', $this->is_smoking);
        $query->bindParam(':has_vehicle', $this->has_vehicle);
        $query->bindParam(':vehicle_specification', $this->vehicle_specification);
        $query->bindParam(':occupants', $this->occupants);
        $query->bindParam(':co_applicant_first_name', $this->co_applicant_first_name);
        $query->bindParam(':co_applicant_last_name', $this->co_applicant_last_name);
        $query->bindParam(':co_applicant_email', $this->co_applicant_email);
        $query->bindParam(':co_applicant_contact_no', $this->co_applicant_contact_no);
        $query->bindParam(':emergency_contact_person', $this->emergency_contact_person);
        $query->bindParam(':emergency_contact_number', $this->emergency_contact_number);
        $query->bindParam(':status', $this->status);
  
        if($query->execute()){
          return true;
          }
          else{
             return false;
          }	
        }
  
        function fetch($id=0){
          $sql = "SELECT * FROM tenant WHERE id = :id;";
          $query=$this->db->connect()->prepare($sql);
          $query->bindParam(':id', $id);
          if($query->execute()){
              $data = $query->fetchAll();
          }
          return $data;
        }
      function show(){
          $sql = "SELECT * FROM tenant;";
          $query = $this->db->connect()->prepare($sql);
          if($query->execute()){
            $data = $query->fetchAll();
          }
        return $data;
        }
  }
  function update_tenant($id, $tenant_data) {
    $sql = "UPDATE tenant SET first_name = :first_name, last_name = :last_name, email = :email, contact_no = :contact_no, relationship_status = :relationship_status, 
    type_of_household = :type_of_household, previous_address = :previous_address, city = :city, provinces = :provinces, zip_code = :zip_code, sex = :sex, date_of_birth = :date_of_birth, 
    has_pet = :has_pet, number_of_pets = :number_of_pets, type_of_pet = :type_of_pet, is_smoking = :is_smoking, has_vehicle = :has_vehicle, vehicle_specification = :vehicle_specification, occupants = :occupants, 
    co_applicant_first_name = :co_applicant_first_name, co_applicant_last_name = :co_applicant_last_name, co_applicant_email = :co_applicant_email, co_applicant_contact_no = :co_applicant_contact_no,
    emergency_contact_person = :emergency_contact_person, emergency_contact_number = :emergency_contact_number, status = :status WHERE id = :id";

    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':id', $id);
    $query->bindParam(':first_name', $tenant_data['first_name']);
    $query->bindParam(':last_name', $tenant_data['last_name']);
    $query->bindParam(':email', $tenant_data['email']);
    $query->bindParam(':contact_no', $tenant_data['contact_no']);
    $query->bindParam(':relationship_status', $tenant_data['relationship_status']);
    $query->bindParam(':type_of_household', $tenant_data['type_of_household']);
    $query->bindParam(':previous_address', $tenant_data['previous_address']);
    $query->bindParam(':city', $tenant_data['city']);
    $query->bindParam(':provinces', $tenant_data['provinces']);
    $query->bindParam(':zip_code', $tenant_data['zip_code']);
    $query->bindParam(':sex', $tenant_data['sex']);
    $query->bindParam(':date_of_birth', $tenant_data['date_of_birth']);
    $query->bindParam(':has_pet', $tenant_data['has_pet']);
    $query->bindParam(':number_of_pets', $tenant_data['number_of_pets']);
    $query->bindParam(':type_of_pet', $tenant_data['type_of_pet']);
    $query->bindParam(':is_smoking', $tenant_data['is_smoking']);
    $query->bindParam(':has_vehicle', $tenant_data['has_vehicle']);
    $query->bindParam(':vehicle_specification', $tenant_data['vehicle_specification']);
    $query->bindParam(':occupants', $tenant_data['occupants']);
    $query->bindParam(':co_applicant_first_name', $tenant_data['co_applicant_first_name']);
    $query->bindParam(':co_applicant_last_name', $tenant_data['co_applicant_last_name']);
    $query->bindParam(':co_applicant_email', $tenant_data['co_applicant_email']);
    $query->bindParam(':co_applicant_contact_no', $tenant_data['co_applicant_contact_no']);
    $query->bindParam(':emergency_contact_person', $tenant_data['emergency_contact_person']);
    $query->bindParam(':emergency_contact_number', $tenant_data['emergency_contact_number']);
    $query->bindParam(':status', $tenant_data['status']);

    if($query->execute()){
        return true;
    }
    else{
        return false;
    }
    function fetch($id=0){
      $sql = "SELECT * FROM tenant WHERE id = :id;";
      $query=$this->db->connect()->prepare($sql);
      $query->bindParam(':id', $id);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }
    function show(){
        $sql = "SELECT * FROM tenant;";
        $query = $this->db->connect()->prepare($sql);
        if($query->execute()){
          $data = $query->fetchAll();
        }
      return $data;
      }	
}


?>