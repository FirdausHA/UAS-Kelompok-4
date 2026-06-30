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
<<<<<<< HEAD
            (nama, deskripsi, gambar, harga, luas_area, rating, is_populer, status)
            VALUES (:nama, :deskripsi, :gambar, :harga, :luas_area, :rating, :is_populer, :status)";
=======
            (nama, deskripsi, gambar, harga, luas_area, rating, is_populer)
            VALUES (:nama, :deskripsi, :gambar, :harga, :luas_area, :rating, :is_populer)";
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':gambar', $data['gambar']);
        $stmt->bindParam(':harga', $data['harga'], PDO::PARAM_INT);
        $stmt->bindParam(':luas_area', $data['luas_area']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':is_populer', $data['is_populer'], PDO::PARAM_INT);
<<<<<<< HEAD
        $stmt->bindParam(':status', $data['status']);
=======
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET
            nama = :nama, deskripsi = :deskripsi, gambar = :gambar, harga = :harga,
<<<<<<< HEAD
            luas_area = :luas_area, rating = :rating, is_populer = :is_populer, status = :status
=======
            luas_area = :luas_area, rating = :rating, is_populer = :is_populer
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
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
<<<<<<< HEAD
        $stmt->bindParam(':status', $data['status']);
=======
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
<<<<<<< HEAD

    public function getStudioStatus($studio) {
        if (!empty($studio['status'])) {
            return $studio['status'];
        }
        return 'available';
    }
=======
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
}
