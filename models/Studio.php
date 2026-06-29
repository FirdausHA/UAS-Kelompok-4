<?php

class Studio {
    private $conn;
    private $table_name = "studios";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ambil semua data studio buat ditampilin di beranda
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
