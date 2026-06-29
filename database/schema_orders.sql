-- Jalankan di phpMyAdmin, database: db_reservation
-- Tabel orders + sequence order ID (#ORD-2026-0001)

CREATE TABLE IF NOT EXISTS order_sequences (
    year INT PRIMARY KEY,
    last_seq INT NOT NULL DEFAULT 0
);

INSERT INTO order_sequences (year, last_seq) VALUES (2026, 0)
ON DUPLICATE KEY UPDATE year = year;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(20) NOT NULL,
    user_id INT NOT NULL,
    studio_id INT NOT NULL,
    tanggal DATE NOT NULL,
    waktu VARCHAR(30) NOT NULL,
    addon_label VARCHAR(150) DEFAULT 'Studio Only',
    total INT NOT NULL,
    bukti_file VARCHAR(255) DEFAULT NULL,
    payment_method VARCHAR(50) DEFAULT 'bank',
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_order_code (order_code),
    KEY idx_orders_user (user_id),
    KEY idx_orders_studio (studio_id),
    KEY idx_orders_status (status)
);

-- Kolom profil pelanggan (abaikan error jika sudah ada)
ALTER TABLE users ADD COLUMN no_telepon VARCHAR(20) DEFAULT NULL AFTER email;
ALTER TABLE users ADD COLUMN alamat TEXT DEFAULT NULL AFTER no_telepon;
ALTER TABLE users ADD COLUMN status_akun ENUM('active', 'blocked') NOT NULL DEFAULT 'active' AFTER role;
