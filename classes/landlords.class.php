<?php



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

    function get_tenant_info($id=0){
        $sql = "SELECT * FROM landlord WHERE id = :id;";
        $query=$this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
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