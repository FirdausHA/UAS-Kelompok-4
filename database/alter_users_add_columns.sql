-- ============================================================
-- JALANKAN FILE INI jika tabel 'users' SUDAH ADA di database
-- dan belum memiliki kolom: no_telepon, alamat, status_akun
-- ============================================================

-- Tambah kolom no_telepon (jika belum ada)
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS no_telepon VARCHAR(20) DEFAULT NULL AFTER role;

-- Tambah kolom alamat (jika belum ada)
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS alamat TEXT DEFAULT NULL AFTER no_telepon;

-- Tambah kolom status_akun (jika belum ada)
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS status_akun ENUM('active','blocked') NOT NULL DEFAULT 'active' AFTER alamat;

-- Verifikasi kolom sudah ada
DESCRIBE users;
