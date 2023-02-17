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
      function show()
        {
      $sql = "SELECT * FROM tenant;";
      $query = $this->db->connect()->prepare($sql);
      if($query->execute()){
        $data = $query->fetchAll();
    }
    return $data;
    }
  }

?>