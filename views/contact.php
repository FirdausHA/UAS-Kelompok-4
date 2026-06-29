<?php
session_start();

$base_path = '../';
$page_title = 'Hubungi Kami | Obsidian Studio';
$active_menu = 'contact';

$status = isset($_GET['status']) ? $_GET['status'] : '';

include $base_path . 'includes/header.php';
?>

<main class="contact-page">
    <div class="container">
        <div class="contact-layout">

            <!-- Info kiri -->
            <div class="contact-info">
                <h1 class="contact-title">Hubungi Kami</h1>
                <p class="contact-desc">
                    Tertarik dengan ruang kreatif kami? Tinggalkan pesan untuk pertanyaan seputar studio, peralatan, atau kerjasama eksklusif.
                </p>

                <div class="contact-info-item">
                    <div class="contact-icon">@</div>
                    <div>
                        <p class="contact-label">Email</p>
                        <p>hello@obsidian.studio</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-icon">&#9679;</div>
                    <div>
                        <p class="contact-label">Studio</p>
                        <p>Jakarta Selatan, Indonesia</p>
                    </div>
                </div>
            </div>

            <!-- Form kanan -->
            <div class="form-panel">
                <?php if ($status == 'success'): ?>
                <div class="alert alert-success">
                    Terima kasih! Pesan Anda telah terkirim. Kami akan menghubungi Anda segera.
                </div>
                <?php elseif ($status == 'error'): ?>
                <div class="alert alert-error">
                    Gagal mengirim pesan. Pastikan Nama dan Pesan sudah diisi.
                </div>
                <?php endif; ?>

                <form action="<?= $base_path ?>controllers/ContactController.php?action=simpan" method="POST">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email (Opsional)</label>
                            <input type="email" id="email" name="email" placeholder="email@contoh.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kota">Kota (Opsional)</label>
                        <input type="text" id="kota" name="kota" placeholder="Kota domisili Anda">
                    </div>

                    <div class="form-group">
                        <label for="pesan">Pesan *</label>
                        <textarea id="pesan" name="pesan" rows="5" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Kirim Pesan</button>
                </form>
            </div>

        </div>
    </div>
</main>

<?php include $base_path . 'includes/footer.php'; ?>
