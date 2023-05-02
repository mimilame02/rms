<?php

require_once 'database.php';

class Reference{

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }
    
    function get_region(){
      $sql = "SELECT * FROM refregion;";
      $query=$this->db->connect()->prepare($sql);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }    
    /* Reference based on regCode */
    function get_province($regCode){
      $sql = "SELECT * FROM refprovince WHERE regCode = :regCode ORDER BY provDesc ASC;";
      $query=$this->db->connect()->prepare($sql);
      $query->bindParam(':regCode', $regCode);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }
    function get_City($provCode){
      $sql = "SELECT * FROM refcitymun WHERE provCode = :provCode ORDER BY citymunDesc ASC;";
      $query=$this->db->connect()->prepare($sql);
      $query->bindParam(':provCode', $provCode);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }
    function get_brgy($citymunCode){
      $sql = "SELECT * FROM refbrgy WHERE citymunCode = :citymunCode ORDER BY brgyDesc ASC;";
      $query=$this->db->connect()->prepare($sql);
      $query->bindParam(':citymunCode', $citymunCode);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }

    /* Get reference location with all data */
    function get_provinced(){
      $sql = "SELECT * FROM refprovince;";
      $query=$this->db->connect()->prepare($sql);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }
    function get_Citys(){
      $sql = "SELECT * FROM refcitymun;";
      $query=$this->db->connect()->prepare($sql);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }
    function get_brgay(){
      $sql = "SELECT * FROM refbrgy;";
      $query=$this->db->connect()->prepare($sql);
      if($query->execute()){
          $data = $query->fetchAll();
      }
      return $data;
    }
    
  
    /* Reference by code to view Desc */
    function get_region_by_code($regCode) {
      $sql = "SELECT * FROM refregion;";
      $query = $this->db->connect()->prepare($sql);
      $query->execute();
      $regions = $query->fetchAll();
  
      foreach ($regions as $region) {
          if ($region['regCode'] == $regCode) {
              return $region['regDesc'];
          }
      }
      return '';
    }
    function get_province_by_code($provCode) {
        $sql = "SELECT * FROM refprovince;";
        $query = $this->db->connect()->prepare($sql);
        $query->execute();
        $provinces = $query->fetchAll();
    
        foreach ($provinces as $province) {
            if ($province['provCode'] == $provCode) {
                return $province['provDesc'];
            }
        }
        return '';
    }
    function get_city_by_code($citymunCode) {
        $sql = "SELECT * FROM refcitymun;";
        $query = $this->db->connect()->prepare($sql);
        $query->execute();
        $cities = $query->fetchAll();
    
        foreach ($cities as $city) {
            if ($city['citymunCode'] == $citymunCode) {
                return $city['citymunDesc'];
            }
        }
        return '';
    }
  

/* Reversed Reference */
function get_barangay() {
  $sql = "SELECT * FROM refbrgy;";
  $query = $this->db->connect()->prepare($sql);
  if ($query->execute()) {
      $data = $query->fetchAll();
  }
  return $data;
}

function get_city_by_brgy($brgyCode) {
  $sql = "SELECT c.* FROM refcitymun c
      JOIN refbrgy b ON c.citymunCode = b.citymunCode
      WHERE b.brgyCode = :brgyCode
      ORDER BY c.citymunDesc ASC;";
  $query = $this->db->connect()->prepare($sql);
  $query->bindParam(':brgyCode', $brgyCode);
  if ($query->execute()) {
      $data = $query->fetchAll();
  }
  return $data;
}

function get_province_by_city($citymunCode) {
  $sql = "SELECT p.* FROM refprovince p
      JOIN refcitymun c ON p.provCode = c.provCode
      WHERE c.citymunCode = :citymunCode
      ORDER BY p.provDesc ASC;";
  $query = $this->db->connect()->prepare($sql);
  $query->bindParam(':citymunCode', $citymunCode);
  if ($query->execute()) {
      $data = $query->fetchAll();
  }
  return $data;
}

function get_region_by_province($provCode) {
  $sql = "SELECT r.* FROM refregion r
      JOIN refprovince p ON r.regCode = p.regCode
      WHERE p.provCode = :provCode
      ORDER BY r.regDesc ASC;";
  $query = $this->db->connect()->prepare($sql);
  $query->bindParam(':provCode', $provCode);
  if ($query->execute()) {
      $data = $query->fetchAll();
  }
  return $data;
}


/* function getSelectedData($brgyCode) {
  $sql = "SELECT b.brgyCode, b.brgyDesc, b.citymunCode, c.citymunDesc, c.provCode, p.provDesc, p.regCode, r.regDesc
          FROM refbrgy b
          JOIN refcitymun c ON b.citymunCode = c.citymunCode
          JOIN refprovince p ON c.provCode = p.provCode
          JOIN refregion r ON p.regCode = r.regCode
          WHERE b.brgyCode = :brgyCode
          ORDER BY b.brgyDesc ASC;";

  $query = $this->db->connect()->prepare($sql);
  $query->bindParam(':brgyCode', $brgyCode);
  if ($query->execute()) {
      $data = $query->fetch();
  }
  return $data;
} */


  

  
  function get_main_pro(){
    $sql = "SELECT * FROM properties;";
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
  function get_unit_type_picture($unit_condition_id) {
    $sql = "SELECT unit_type_picture FROM unit_condition WHERE id = :id;";
    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':id', $unit_condition_id);
    if ($query->execute()) {
        $data = $query->fetch();
    }
    return $data['unit_type_picture'];
  }

}
