<?php

class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($nama_lengkap, $username, $email, $password_hash) {
        $query = "INSERT INTO " . $this->table_name . "
                  (nama_lengkap, username, email, password_hash, role)
                  VALUES (:nama, :username, :email, :password, 'pelanggan')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $nama_lengkap);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);

        return $stmt->execute();
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :login LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':login', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :login LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':login', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByLogin($login) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE username = :login OR email = :login2 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':login2', $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function usernameExists($username) {
        return (bool) $this->getUserByUsername($username);
    }

    public function emailExists($email) {
        return (bool) $this->getUserByEmail($email);
    }

    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM " . $this->table_name);
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function countByRole($role) {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name . " WHERE role = :role";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function countActivePelanggan() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM " . $this->table_name . "
            WHERE role = 'pelanggan' AND (status_akun = 'active' OR status_akun IS NULL)");
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function countNewThisWeek() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM " . $this->table_name . "
            WHERE role = 'pelanggan' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function getAllPelanggan($search = '') {
        $sql = "SELECT id, nama_lengkap, username, email, role,
                       COALESCE(status_akun, 'active') AS status_akun, created_at
                FROM " . $this->table_name . " WHERE role = 'pelanggan'";

        if ($search !== '') {
            $sql .= " AND (nama_lengkap LIKE :q OR email LIKE :q OR username LIKE :q)";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        if ($search !== '') {
            $like = '%' . $search . '%';
            $stmt->bindParam(':q', $like);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllForAdmin($search = '', $role = '') {
        $sql = "SELECT id, nama_lengkap, username, email, role,
                       COALESCE(status_akun, 'active') AS status_akun, created_at
                FROM " . $this->table_name . " WHERE 1=1";

        if ($role !== '' && $role !== 'all') {
            $sql .= " AND role = :role";
        }
        if ($search !== '') {
            $sql .= " AND (nama_lengkap LIKE :q OR email LIKE :q OR username LIKE :q)";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        if ($role !== '' && $role !== 'all') {
            $stmt->bindParam(':role', $role);
        }
        if ($search !== '') {
            $like = '%' . $search . '%';
            $stmt->bindParam(':q', $like);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $nama, $email, $telepon, $alamat) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET
            nama_lengkap = :nama, email = :email, no_telepon = :telepon, alamat = :alamat
            WHERE id = :id AND role = 'pelanggan'");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updatePassword($id, $hash) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET password_hash = :hash WHERE id = :id");
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function toggleStatus($id) {
        $user = $this->getById($id);
        if (!$user || $user['role'] !== 'pelanggan') return false;

        $new = ($user['status_akun'] ?? 'active') === 'active' ? 'blocked' : 'active';
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET status_akun = :status WHERE id = :id");
        $stmt->bindParam(':status', $new);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
