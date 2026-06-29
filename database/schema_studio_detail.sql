-- Opsional: kolom tambahan untuk halaman detail studio
-- Jalankan sekali di phpMyAdmin (abaikan error jika kolom sudah ada)

ALTER TABLE studios
    ADD COLUMN max_kapasitas VARCHAR(50) DEFAULT NULL AFTER luas_area;

ALTER TABLE studios
    ADD COLUMN deskripsi_detail TEXT DEFAULT NULL AFTER deskripsi;

ALTER TABLE studios
    ADD COLUMN amenitas TEXT DEFAULT NULL AFTER deskripsi_detail;

-- Update contoh data (sesuaikan jika kolom sudah ada)
UPDATE studios SET
    max_kapasitas = 'Max 10 People',
    deskripsi_detail = 'Ruang hangat dengan dinding bata ekspos dan nuansa vintage. Dirancang untuk portrait editorial, lookbook fashion, dan produksi konten kreatif dengan pencahayaan hangat alami.',
    amenitas = 'High-speed Fiber|Private Fitting Room|Premium Lounge'
WHERE id = 1;

UPDATE studios SET
    max_kapasitas = 'Max 12 People',
    deskripsi_detail = 'Studio all-white dengan cyclorama bersih dan kapasitas lighting profesional. Ideal untuk foto komersial high-key, kampanye brand, dan video produk dengan latar seamless.',
    amenitas = 'High-speed Fiber|Private Fitting Room|Premium Lounge'
WHERE id = 2;

UPDATE studios SET
    max_kapasitas = 'Max 15 People',
    deskripsi_detail = 'Ruang sinematik berlangit-langit tinggi untuk fashion editorial dan produksi video komersial. Dilengkapi cyclorama hitam-putih seamless, kapasitas lighting 12.5k, dan makeup suite khusus.',
    amenitas = 'High-speed Fiber|Private Fitting Room|Premium Lounge'
WHERE id = 3;
