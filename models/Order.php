<?php

class Order {
    private $conn;
    private $table = 'orders';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateOrderCode() {
        $year = (int) date('Y');

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare('SELECT last_seq FROM order_sequences WHERE year = :year FOR UPDATE');
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $ins = $this->conn->prepare('INSERT INTO order_sequences (year, last_seq) VALUES (:year, 0)');
                $ins->bindParam(':year', $year, PDO::PARAM_INT);
                $ins->execute();
                $next = 1;
            } else {
                $next = (int) $row['last_seq'] + 1;
            }

            $upd = $this->conn->prepare('UPDATE order_sequences SET last_seq = :seq WHERE year = :year');
            $upd->bindParam(':seq', $next, PDO::PARAM_INT);
            $upd->bindParam(':year', $year, PDO::PARAM_INT);
            $upd->execute();

            $this->conn->commit();
            return 'ORD-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function createPending($data) {
        $query = "INSERT INTO {$this->table}
            (order_code, user_id, studio_id, tanggal, waktu, addon_label, total, payment_method, status)
            VALUES (:order_code, :user_id, :studio_id, :tanggal, :waktu, :addon_label, :total, :payment_method, 'pending')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_code', $data['order_code']);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':studio_id', $data['studio_id'], PDO::PARAM_INT);
        $stmt->bindParam(':tanggal', $data['tanggal']);
        $stmt->bindParam(':waktu', $data['waktu']);
        $stmt->bindParam(':addon_label', $data['addon_label']);
        $stmt->bindParam(':total', $data['total'], PDO::PARAM_INT);
        $stmt->bindParam(':payment_method', $data['payment_method']);

        if ($stmt->execute()) {
            return (int) $this->conn->lastInsertId();
        }
        return false;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCode($code) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE order_code = :code LIMIT 1");
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function confirmPayment($id, $bukti_file, $payment_method) {
        $query = "UPDATE {$this->table} SET
            bukti_file = :bukti,
            payment_method = :method,
            status = 'confirmed',
            updated_at = NOW()
            WHERE id = :id AND bukti_file IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':bukti', $bukti_file);
        $stmt->bindParam(':method', $payment_method);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getByUser($user_id, $filter = 'all') {
        $sql = "SELECT o.*, s.nama AS studio_nama, s.gambar AS studio_gambar
                FROM {$this->table} o
                JOIN studios s ON s.id = o.studio_id
                WHERE o.user_id = :user_id AND o.bukti_file IS NOT NULL";

        if ($filter === 'berjalan') {
            $sql .= " AND o.status IN ('pending', 'confirmed')";
        } elseif ($filter === 'selesai') {
            $sql .= " AND o.status = 'completed'";
        } elseif ($filter === 'batal') {
            $sql .= " AND o.status = 'cancelled'";
        }

        $sql .= ' ORDER BY o.created_at DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllForAdmin($search = '') {
        $sql = "SELECT o.*, s.nama AS studio_nama,
                       u.nama_lengkap AS customer_nama, u.email AS customer_email
                FROM {$this->table} o
                JOIN studios s ON s.id = o.studio_id
                JOIN users u ON u.id = o.user_id
                WHERE o.bukti_file IS NOT NULL";

        if ($search !== '') {
            $sql .= " AND (o.order_code LIKE :q OR u.nama_lengkap LIKE :q OR u.email LIKE :q OR s.nama LIKE :q)";
        }

        $sql .= ' ORDER BY o.created_at DESC';

        $stmt = $this->conn->prepare($sql);
        if ($search !== '') {
            $like = '%' . $search . '%';
            $stmt->bindParam(':q', $like);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) return false;

        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE bukti_file IS NOT NULL");
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function countToday() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE DATE(created_at) = CURDATE() AND bukti_file IS NOT NULL");
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function sumMonthlyRevenue() {
        $stmt = $this->conn->query("SELECT COALESCE(SUM(total), 0) AS rev FROM {$this->table}
            WHERE bukti_file IS NOT NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['rev'] ?? 0);
    }
}
