<?php

class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($nama_lengkap, $username, $password_hash) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nama_lengkap, username, password_hash, role) 
                  VALUES (:nama, :username, :password, 'pelanggan')";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nama', $nama_lengkap);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password_hash);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>