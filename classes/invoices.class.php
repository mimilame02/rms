<?php

require_once 'database.php';

class Invoice{
    //Attributes

    public $id;
    public $lease_unit_id;
    public $tenant_id;
    public $property_id;
    public $property_unit_id;
    public $monthly_rent;
    public $rent_due_date;
    public $electricity;
    public $water;
    public $one_month_deposit;
    public $one_month_advance;
    public $penalty_id;
    public $total_due;
    public $amount_paid;
    public $balance;
    public $status;
    public $payment_date;
    public $fixed_bills;
    public $monthly_bills;
    public $updated_at; 


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

    function invoice_add($tenant_id) {
        $sql = "INSERT INTO invoice (lease_unit_id, tenant_id, property_id, property_unit_id, monthly_rent, rent_due_date, electricity, water, one_month_deposit, one_month_advance, penalty_id, total_due, amount_paid, balance, status, payment_date, fixed_bills, monthly_bills) 
        VALUES (:lease_unit_id, :tenant_id, :property_id, :property_unit_id, :monthly_rent, :rent_due_date, :electricity, :water, :one_month_deposit, :one_month_advance, :penalty_id, :total_due, :amount_paid, :balance,:status, :payment_date, :fixed_bills, :monthly_bills)";        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':lease_unit_id', $this->lease_unit_id);
        $query->bindParam(':tenant_id', $this->tenant_id);
        $query->bindParam(':property_id', $this->property_id);
        $query->bindParam(':property_unit_id', $this->property_unit_id);
        $query->bindParam(':monthly_rent', $this->monthly_rent);
        $query->bindParam(':rent_due_date', $this->rent_due_date);
        $query->bindParam(':electricity', $this->electricity);
        $query->bindParam(':water', $this->water);
        $query->bindParam(':one_month_deposit', $this->one_month_deposit);
        $query->bindParam(':one_month_advance', $this->one_month_advance);
        $query->bindParam(':penalty_id', $this->penalty_id);
        $query->bindParam(':total_due', $this->total_due);
        $query->bindParam(':amount_paid', $this->amount_paid);
        $query->bindParam(':balance', $this->balance);
        $query->bindParam(':status', $this->status);
        $query->bindParam(':payment_date', $this->payment_date);
        $query->bindParam(':fixed_bills', $this->fixed_bills);
        $query->bindParam(':monthly_bills', $monthly_bills);

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

      function update_invoice_pay() {
        $sql = "UPDATE invoice SET payment_date = :payment_date, amount_paid = :amount_paid, balance = :balance, status = :status, updated_at=CURRENT_TIMESTAMP() WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':amount_paid', $this->amount_paid);
        $query->bindParam(':balance', $this->balance);
        $query->bindParam(':status', $this->status);
        $query->bindParam(':payment_date', $this->payment_date);
        $query->bindParam(':id', $this->id);
    
        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}

?>