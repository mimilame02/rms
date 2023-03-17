<?php

require_once 'database.php';

class Invoice{
    //Attributes

    public $id;
    public $lease_unit_id;
    public $tenant_id;
    public $monthly_rent;
    public $rent_due_date;
    public $electricity;
    public $water;
    public $penalty_id;
    public $penalty;
    public $rent_paid;
    public $status;
    public $invoice_date;


    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }
    function fetch($id=0){
        $sql = "SELECT * FROM invoice WHERE id = :id;";
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        if($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show(){
        $sql = "SELECT * FROM invoice;";
        $query=$this->db->connect()->prepare($sql);
        if($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }
    function invoice_fetch($record_id){
        $sql = "SELECT * FROM invoice WHERE id = :id;";
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':id', $record_id);
        if($query->execute()){
            $data = $query->fetch();
            // Add the file contents to the returned data array
        }
        return $data;
    }

    function invoice_add() {
        $sql = "INSERT INTO invoice (lease_unit_id, tenant_id, monthly_rent, rent_due_date, electricity, water, penalty_id, rent_paid, status, invoice_date) 
        VALUES (:lease_unit_id, :tenant_id, :monthly_rent, :rent_due_date, :electricity, :water, :penalty_id, :rent_paid, :status, :invoice_date)";        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':lease_unit_id', $this->lease_unit_id);
        $query->bindParam(':tenant_id', $this->tenant_id);
        $query->bindParam(':monthly_rent', $this->monthly_rent);
        $query->bindParam(':rent_due_date', $this->rent_due_date);
        $query->bindParam(':electricity', $this->electricity);
        $query->bindParam(':water', $this->water);
        $query->bindParam(':penalty_id', $this->penalty_id);
        $query->bindParam(':rent_paid', $this->rent_paid);
        $query->bindParam(':status', $this->status);
        $query->bindParam(':invoice_date', $this->invoice_date);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    function invoice_delete($record_id){
        $sql = "DELETE FROM invoice WHERE id = :id;";
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