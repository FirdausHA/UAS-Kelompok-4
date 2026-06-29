<?php

class Studio {
    private $conn;
    private $table_name = "studios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllStudios() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudioById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertStudio($nama, $deskripsi, $harga, $gambar) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nama_studio, deskripsi, harga_per_jam, gambar_studio) 
                  VALUES (:nama, :deskripsi, :harga, :gambar)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':gambar', $gambar);

        return $stmt->execute();
    }

    public function deleteStudio($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>