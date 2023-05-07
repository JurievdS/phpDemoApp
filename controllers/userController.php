<?php

require_once './util/database.php';

class UserController {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function loginUser($email, $password) {
        $query = "SELECT id, email, password, permission FROM users WHERE email=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        if ($row) {
            if (password_verify($password, $row['password'])) {
                // Save the user ID and user level in the session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['permission'] = $row['permission'];
                // Redirect the user to the homepage
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "User not found.";
        }
    
        return isset($error_message) ? $error_message : '';
    }
    
    public function registerUser($email, $password) {
    
        // Check if email already exists
        $query = "SELECT COUNT(*) as count FROM users WHERE email=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        if ($count > 0) {
            return "Email already exists.";
        }
    
        // Insert the user into the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $email, $hashed_password);
        $stmt->execute();
        $stmt->close();
    
        $this->db->close();
    
        return '';
    }
}
?>