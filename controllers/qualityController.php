<?php
require_once('./util/database.php');

class qualityControl {
    private $db;

    function __construct() {
        $Database = new Database();
        $this->db = $Database->getConnection(); // Call the getConnection() method
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    function addQCEntry($qc_name, $qc_description, $qc_date, $qc_outcome)
    {
        $sql = "INSERT INTO qualityControl (qc_name, qc_description, qc_date, qc_outcome) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $qc_name, $qc_description, $qc_date, $qc_outcome);
        $stmt->execute();
        $stmt->close();
    }

    function editQCEntry($id, $name, $description, $date, $outcome)
    {
        $sql = "UPDATE qualityControl SET qc_name=?, qc_description=?, qc_date=?, qc_outcome=? WHERE qc_id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $name, $description, $date, $outcome, $id);
        $stmt->execute();
        $stmt->close();
    }
    
    function deleteQCEntry($id)
    {
        $sql = "DELETE FROM qualityControl WHERE qc_id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    function getQCEntry($id)
    {
        $sql = "SELECT * FROM qualityControl WHERE qc_id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    function getQCEntries()
    {
        $sql = "SELECT * FROM qualityControl";
        $result = $this->db->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    function __destruct()
    {
        $this->db->close();
    }
}