<?php

require_once 'database.php';
require_once '../classes/invoices.class.php';

class Leases{
    //Attributes

    public $id;
    public $property_unit_id;
    public $monthly_rent;
    public $tenant_id;
    public $lease_start;
    public $lease_end;
    public $lease_doc;
    public $one_month_deposit;
    public $one_month_advance;
    public $electricity;
    public $water;
    public $status;
    public $updated_at; 

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
        $sql = "INSERT INTO lease (property_unit_id, monthly_rent, tenant_id, lease_start, lease_end, lease_doc, one_month_deposit, one_month_advance, electricity, water, status)
        VALUES (:property_unit_id,  :monthly_rent, :tenant_id, :lease_start, :lease_end, :lease_doc, :one_month_deposit, :one_month_advance, :electricity, :water, :status)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':property_unit_id', $this->property_unit_id);
        $query->bindParam(':tenant_id', $this->tenant_id);
        $query->bindParam(':monthly_rent', $this->monthly_rent);
        $query->bindParam(':one_month_deposit', $this->one_month_deposit);
        $query->bindParam(':one_month_advance', $this->one_month_advance);
        $query->bindParam(':lease_start', $this->lease_start);
        $query->bindParam(':lease_end', $this->lease_end);
        $query->bindParam(':electricity', $this->electricity);
        $query->bindParam(':water', $this->water);
        $query->bindParam(':lease_doc', $this->lease_doc);
        $query->bindParam(':status', $this->status);

        if($query->execute()) {
            return true;
        } else {
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

      function fetch_all_leases(){
        $sql = "SELECT lease.*, tenant.first_name, tenant.last_name FROM lease INNER JOIN tenant ON lease.tenant_id = tenant.id";
        $query = $this->db->connect()->prepare($sql);
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }


}

?>