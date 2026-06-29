-- Jalankan di phpMyAdmin, database: db_reservation
-- Tabel studios (koordinasi sama Ibun buat CRUD admin)

CREATE TABLE IF NOT EXISTS studios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(500) NOT NULL,
    harga INT NOT NULL,
    luas_area VARCHAR(50) DEFAULT NULL,
    rating DECIMAL(2,1) DEFAULT 5.0,
    is_populer TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data contoh buat testing beranda
INSERT INTO studios (nama, deskripsi, gambar, harga, luas_area, rating, is_populer) VALUES
('Studio Aruna - Tema Rustic', 'Ruang hangat dengan dinding bata ekspos dan nuansa vintage, cocok untuk foto portrait.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDUmZeqlsPgdbLB_RD1uiekzLmqQpdr8afs_vNjJu9mPi4ndOithPN2YTz7jzX5EPnl8JN9BA9RIySRlU3ExpX9S51DwfUPv9Hg2_VY1KR6_yJw2pZrAiVOLHiC9lwnE_jIaPH3fQJmSq8WY0F34jrisqJlIgm2pMQbGGDF5jJQ3MKHzW4x8CJ6O3shzmHQTSauRPyHbgQCCnc93PuWxFxjiHyFHuXx-SQzhBsaEjiHKlSl8GYaMbuTp4vyMuoJssLacFTPaTujMuY', 450000, '50m² Area', 4.9, 1),
('Studio Zenith - High Key', 'Studio all-white dengan cyclorama bersih, ideal untuk foto komersial high-key.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB2N6DuRPHBQ6Fx4EUa_Uh6Yqmu01eG4c6NDV22xMTi6JnuTnvlKuNQeRDJQLBBa_WK0-3l8_NmZ2isYJDAnI5dRl5f1GOyUMXOkGz5rIZyMmGqVnj6v4pm5A-Fp05-em_FcsyOEMNc6pzm2ooDcNQElqwJnbm3Sa6mSFlAWKR8HoowZMke8RmnUb1GMwVNuSsTC5ZcdsWqdY2P0qLjpDa23zOLtGYMk2Ahsp0jnOCWzztxxo0z0XuiwSoMLJ7SgkcO_FLkTxchroA', 600000, '75m² Area', 4.8, 0),
('Studio Noir - Industrial', 'Nuansa industrial dengan lighting neon, cocok untuk video musik dan editorial bold.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHYp3hFxwVCit_TrRf2aN1AwVNSIXfL3LKyIkbWK3njHhcr5TyFdUpntlp1NMrQdWLGSmWsPyTTRA5UbON4fWc0glNnOHXRVHvN-8ydbMIWOMv3rrRDJHCNoFEM35hVXfdPz4ROpqTprIwMM084Uce1Qt7MAY6tstKW2zyjGt3nsJxBDQUwB7FZfUWOItjhqXS-rWbdFfU2QBblz7pBZJKv9auOxQdyEAiG0WyGsAXwV6lqyW2DlWcxI-7QGsmQYdWvA_g2msZsOo', 850000, '120m² Area', 5.0, 0);
