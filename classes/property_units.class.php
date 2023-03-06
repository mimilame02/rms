<?php

require_once 'database.php';

class Property_Units {
    //Attributes
    public $id;
    public $property_unit_name;
    public $property_id;
    public $unit_type_id;
    public $monthly_rent;
    public $unit_condition_id;
    public $num_bedrooms;
    public $num_bathrooms;
    public $max_capacity;
    public $available_for;
    public $status;
   
    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function fetch($id = 0) {
        $sql = "SELECT * FROM property_units WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show() {
        $sql = "SELECT * FROM property_units;";
        $query = $this->db->connect()->prepare($sql);
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function property_unit_fetch($record_id) {
        $sql = "SELECT * FROM property_units WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $record_id);
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function property_unit_add() {
        // attempt insert query execution
        $sql = "INSERT INTO property_units (property_unit_name, property_id, unit_type_id, monthly_rent, unit_condition_id, num_bedrooms, num_bathrooms, max_capacity, available_for, status) 
                VALUES (:property_unit_name, :property_id, :unit_type_id, :monthly_rent, :unit_condition_id, :num_bedrooms, :num_bathrooms, :max_capacity, :available_for, :status)";
    
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':property_unit_name', $this->property_unit_name);
        $query->bindParam(':property_id', $this->property_id);
        $query->bindParam(':unit_type_id', $this->unit_type_id);
        $query->bindParam(':monthly_rent', $this->monthly_rent);
        $query->bindParam(':unit_condition_id', $this->unit_condition_id);
        $query->bindParam(':num_bedrooms', $this->num_bedrooms);
        $query->bindParam(':num_bathrooms', $this->num_bathrooms);
        $query->bindParam(':max_capacity', $this->max_capacity);
        $query->bindParam(':available_for', $this->available_for);
        $query->bindParam(':status', $this->status);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }	
    }

    function property_unit_edit() {
        // attempt insert query execution
        $sql = "UPDATE property_units SET property_unit_name=:property_unit_name, monthly_rent=:monthly_rent, property_id=:property_id, unit_condition_id=:unit_condition_id, unit_type_id=:unit_type_id, num_bedrooms=:num_bedrooms, num_bathrooms=:num_bathrooms, max_capacity=:max_capacity, available_for=:available_for, status=:status WHERE id=:id;";
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':property_unit_name', $this->property_unit_name);
        $query->bindParam(':monthly_rent', $this->monthly_rent);
        $query->bindParam(':property_id', $this->property_id);
        $query->bindParam(':unit_condition_id', $this->unit_condition_id);
        $query->bindParam(':unit_type_id', $this->unit_type_id);
        $query->bindParam(':num_bedrooms', $this->num_bedrooms);
        $query->bindParam(':num_bathrooms', $this->num_bathrooms);
        $query->bindParam(':max_capacity', $this->max_capacity);
        $query->bindParam(':available_for', $this->available_for);
        $query->bindParam(':status', $this->status);
        $query->bindParam(':id', $this->id);
    
        if($query->execute()){
            return true;
        }
        else{
            return false;
        }	
    }

    function property_unit_delete($record_id){
        $sql = "DELETE FROM property_units WHERE id = :id;";
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':id', $record_id);
        if($query->execute()){
            return true;
        }
        else{
            return false;
        }
      }

}

?>