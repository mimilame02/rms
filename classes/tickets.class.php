<?php

require_once 'database.php';

class Ticket {
    public $id;
    public $raised_by;
    public $subject;
    public $date_created;
    public $status;
    public $messages;
    public $attachment;
    public $updated_at; 

    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function fetch($id = 0) {
        $sql = "SELECT * FROM tickets WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);

        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function show() {
        $sql = "SELECT * FROM tickets;";
        $query = $this->db->connect()->prepare($sql);

        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;
    }

    function fetch_tickets($id) {
        $sql = "SELECT * FROM tickets WHERE id = :id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);

        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function add_tickets() {
        $sql = "INSERT INTO tickets (raised_by, subject, date_created, status, messages, attachment) VALUES (:raised_by, :subject, :date_created, :status, :messages, :attachment)";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':raised_by', $this->raised_by);
        $query->bindParam(':subject', $this->subject);
        $query->bindParam(':date_created', $this->date_created);
        $query->bindParam(':status', $this->status);
        $query->bindParam(':messages', $this->messages);
        $query->bindParam(':attachment', $this->attachment);
    
        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    function update_tickets() {
        $sql = "UPDATE tickets SET messages = :messages, updated_at=CURRENT_TIMESTAMP() WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':messages', $this->messages);
        $query->bindParam(':id', $this->id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function delete_tickets($id) {
        $sql = "DELETE FROM tickets WHERE id = :id;";
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
