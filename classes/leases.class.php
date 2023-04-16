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
        VALUES (:property_unit_id, :monthly_rent, :tenant_id, :lease_start, :lease_end, :lease_doc, :one_month_deposit, :one_month_advance, :electricity, :water, :status)";
    
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':property_unit_id', $this->property_unit_id);
        $query->bindParam(':monthly_rent', $this->monthly_rent);
        $query->bindParam(':tenant_id', $this->tenant_id);
        $query->bindParam(':lease_start', $this->lease_start);
        $query->bindParam(':lease_end', $this->lease_end);
        $query->bindParam(':lease_doc', $this->lease_doc);
        $query->bindParam(':one_month_deposit', $this->one_month_deposit);
        $query->bindParam(':one_month_advance', $this->one_month_advance);
        $query->bindParam(':electricity', $this->electricity);
        $query->bindParam(':water', $this->water);
        $query->bindParam(':status', $this->status);
    
        if($query->execute()){
            $lease_id = $this->db->connect()->lastInsertId();
            // Determine if the invoice should be generated automatically (fixed bill) or manually
            if (!empty($this->electricity) && !empty($this->water)) {
                // Fixed bill - generate invoice automatically by month
                // ...
                $invoice_monthly_bills = 1;
                $invoice_fixed_bills = 1;
            } else {
                // Manual bill - do not generate invoice automatically
                // ...
                $invoice_monthly_bills = 0;
                $invoice_fixed_bills = 0;
            }
            // Call the invoice_add() function to generate the invoice
            $invoice = new Invoice();
            $invoice->lease_unit_id = $lease_id;
            $invoice->tenant_id = $this->tenant_id;
            $invoice->property_id = $this->property_id;
            $invoice->property_unit_id = $this->property_unit_id;
            $invoice->monthly_rent = $this->monthly_rent;
            $invoice->rent_due_date = date('Y-m-d', strtotime($this->lease_start . ' +1 month'));
            $invoice->electricity = $this->electricity;
            $invoice->water = $this->water;
            $invoice->one_month_deposit = $this->one_month_deposit;
            $invoice->one_month_advance = $this->one_month_advance;
            $invoice->penalty_id = null;
            $invoice->total_due = $this->monthly_rent + $this->electricity + $this->water;
            $invoice->amount_paid = 0;
            $invoice->balance = $invoice->total_due;
            $invoice->status = 'unpaid';
            $invoice->payment_date = null;
            $invoice->fixed_bills = $invoice_fixed_bills;
            $invoice->monthly_bills = $invoice_monthly_bills;
            $invoice->invoice_add();
            return true;
        } else{
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