<?php

class Studio {
    private $conn;
    private $table_name = "studios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM " . $this->table_name);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . "
            (nama, deskripsi, gambar, harga, luas_area, rating, is_populer, status)
            VALUES (:nama, :deskripsi, :gambar, :harga, :luas_area, :rating, :is_populer, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':gambar', $data['gambar']);
        $stmt->bindParam(':harga', $data['harga'], PDO::PARAM_INT);
        $stmt->bindParam(':luas_area', $data['luas_area']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':is_populer', $data['is_populer'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET
            nama = :nama, deskripsi = :deskripsi, gambar = :gambar, harga = :harga,
            luas_area = :luas_area, rating = :rating, is_populer = :is_populer, status = :status
            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':gambar', $data['gambar']);
        $stmt->bindParam(':harga', $data['harga'], PDO::PARAM_INT);
        $stmt->bindParam(':luas_area', $data['luas_area']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':is_populer', $data['is_populer'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getStudioStatus($studio) {
        if (!empty($studio['status'])) {
            return $studio['status'];
        }
        return 'available';
    }
}
