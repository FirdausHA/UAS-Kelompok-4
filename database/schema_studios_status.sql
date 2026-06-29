-- Tambah kolom status pada tabel studios (jalankan sekali)
ALTER TABLE studios
    ADD COLUMN status ENUM('available', 'booked') NOT NULL DEFAULT 'available' AFTER is_populer;
