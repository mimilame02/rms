<?php

require_once 'database.php';

class Reference{

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }
    
    function get_province(){
      $sql = "SELECT * FROM refprovince;";
      $query=$this->db->connect()->prepare($sql);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
  }

  function get_City($province_code){
    $sql = "SELECT * FROM refcitymun;";
    $query=$this->db->connect()->prepare($sql);
    if($query->execute()){
        $data = $query->fetchAll();
    }
    return $data;
}

function get_main_pro(){
  $sql = "SELECT * FROM property;";
  $query=$this->db->connect()->prepare($sql);
  if($query->execute()){
      $data = $query->fetchAll();
  }
  return $data;
}
function get_unit_con(){
  $sql = "SELECT * FROM unit_condition;";
  $query=$this->db->connect()->prepare($sql);
  if($query->execute()){
      $data = $query->fetchAll();
  }
  return $data;
}
function get_unit_type(){
  $sql = "SELECT * FROM unit_type;";
  $query=$this->db->connect()->prepare($sql);
  if($query->execute()){
      $data = $query->fetchAll();
  }
  return $data;
}
}