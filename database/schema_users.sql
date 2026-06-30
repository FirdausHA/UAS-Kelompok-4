-- Jalankan di phpMyAdmin, database: db_reservation
-- Tabel users untuk autentikasi admin & pelanggan

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(150) NOT NULL,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'pelanggan') NOT NULL DEFAULT 'pelanggan',
    no_telepon VARCHAR(20) DEFAULT NULL,
    alamat TEXT DEFAULT NULL,
    status_akun ENUM('active', 'blocked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_users_username (username),
    UNIQUE KEY uk_users_email (email)
);

-- ============================================================
-- Jika tabel users SUDAH ADA, jalankan ALTER TABLE berikut
-- untuk menambah kolom yang kurang (skip jika sudah ada):
-- ============================================================
-- ALTER TABLE users ADD COLUMN no_telepon VARCHAR(20) DEFAULT NULL AFTER role;
-- ALTER TABLE users ADD COLUMN alamat TEXT DEFAULT NULL AFTER no_telepon;
-- ALTER TABLE users ADD COLUMN status_akun ENUM('active','blocked') NOT NULL DEFAULT 'active' AFTER alamat;

-- Akun admin default
-- Email: adminobsidian@gmail.com | Password: admin12345
INSERT INTO users (nama_lengkap, username, email, password_hash, role) VALUES
('Administrator Obsidian', 'adminobsidian', 'adminobsidian@gmail.com',
 '$2y$10$23T/bR0jIPguFKyk/60GD.UOEqU2bFhxWbWKwPsTTMSW4kW0qpnOy', 'admin')
ON DUPLICATE KEY UPDATE
    nama_lengkap = VALUES(nama_lengkap),
    password_hash = VALUES(password_hash),
    role = 'admin';
