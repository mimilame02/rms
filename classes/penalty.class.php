<?php

require_once 'database.php';

class Penalty {
    public $id;
    public $name;
    public $amount;

    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function fetch($id = 0) {
        $sql = "SELECT * FROM penalty WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);

        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show() {
        $sql = "SELECT * FROM penalty;";
        $query = $this->db->connect()->prepare($sql);

        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function fetch_penalty($id) {
        $sql = "SELECT * FROM penalty WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);

        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function add_penalty() {
        $sql = "INSERT INTO penalty (name, amount) VALUES (:name, :amount)";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':amount', $this->amount);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function update_penalty() {
        $sql = "UPDATE penalty SET name = :name, amount = :amount WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $this->id);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':amount', $this->amount);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function delete_penalty($id) {
        $sql = "DELETE FROM penalty WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
