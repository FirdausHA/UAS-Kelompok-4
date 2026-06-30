<?php

class BukuTamu {
    private $conn;
    private $table_name = "buku_tamu";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function simpan($nama, $email, $kota, $pesan) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nama, email, kota, pesan) 
                  VALUES (:nama, :email, :kota, :pesan)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':kota', $kota);
        $stmt->bindParam(':pesan', $pesan);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
