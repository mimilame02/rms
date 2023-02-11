<?php

require_once 'database.php';

class Landlord{
    //Attributes

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $contact_num;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $id_doc;
    public $fname;
    public $emergency_num;
   

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function get_tenant_info($account_id=0){
        $sql = "SELECT * FROM tenant WHERE account_id = :account_id;";
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $account_id);
        if($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show(){
        $sql = "SELECT * FROM landlord;";
        $query=$this->db->connect()->prepare($sql);
        if($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }

}

?>