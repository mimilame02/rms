<?php

require_once 'database.php';

class Leases{
    //Attributes

    public $id;
    public $property_unit_name;
    public $monthly_rent;
    public $tenant_name;
    public $lease_start;
    public $lease_end;
    public $rent_paid;
    public $one_month_deposit;
    public $one_month_advance;
    public $property_picture;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }
    function fetch($id=0){
        $sql = "SELECT * FROM lease WHERE id = :id;";
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        if($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show(){
        $sql = "SELECT * FROM lease;";
        $query=$this->db->connect()->prepare($sql);
        if($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }
    function lease_fetch($record_id){
        $sql = "SELECT * FROM lease WHERE id = :id;";
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':id', $record_id);
        if($query->execute()){
            $data = $query->fetch();
            // Add the file contents to the returned data array
        }
        return $data;
    }

    function lease_add() {
        // attempt insert query execution
        $sql = "INSERT INTO lease (property_unit_name, monthly_rent, tenant_name, lease_start, lease_end, rent_paid, one_month_deposit, one_month_advance, property_picture) 
        VALUES (:property_unit_name, :monthly_rent, :tenant_name, :lease_start, :lease_end, :rent_paid, :one_month_deposit, :one_month_advance, :property_picture)";
    
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':property_unit_name', $this->property_unit_name);
        $query->bindParam(':monthly_rent', $this->monthly_rent);
        $query->bindParam(':tenant_name', $this->tenant_name);
        $query->bindParam(':lease_start', $this->lease_start);
        $query->bindParam(':lease_end', $this->lease_end);
        $query->bindParam(':rent_paid', $this->rent_paid);
        $query->bindParam(':one_month_deposit', $this->one_month_deposit);
        $query->bindParam(':one_month_advance', $this->one_month_advance);
        $query->bindParam(':property_picture', $this->property_picture);


        if($query->execute()){
            return true;
        }
        else{
            return false;
        }
    }
    function lease_delete($record_id){
        $sql = "DELETE FROM lease WHERE id = :id;";
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