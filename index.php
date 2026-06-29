<?php
session_start();

$base_path = '';

require_once 'config/database.php';
require_once 'models/Studio.php';

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);
$daftarStudio = $studioModel->getAll();

$page_title = 'Obsidian Studio | Premium Creative Spaces';
$active_menu = 'home';
$nav_mode = 'scroll';
$status = isset($_GET['status']) ? $_GET['status'] : '';

function formatHarga($angka) {
    if ($angka >= 1000) {
        return 'Rp ' . number_format($angka / 1000, 0, ',', '.') . 'k';
    }
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

$studioDefault = count($daftarStudio) > 0 ? $daftarStudio[0]['nama'] : 'Pilih Studio';

include 'includes/header.php';
?>

<!-- Home / Hero -->
<section class="hero" id="home">
    <div class="hero-bg" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDkyIuPzi4mQ9NbMN1P2CCOsRlQ_yAFCiONelG5msQAS5xtHXs2SLPEVtyU08MamdXcB2NqTckr00XwCi_7F2LjcpMFNyfn1s1838Mo-Jfe-zt8FdtPefEzYwuvXTDyu6iOrlg6YN71k8qT0XXiIcdaTrp1ptWCMh1rmDLJjdXYIDwqB2NojvR9t3Ada0IBDp5rdjqow3e5P8s8Hi_T1bn56Yr2rwnPRprGCSjHcPa_0JKtfRjaqRTj9OFfvMV57mDufOzQZIocBDg');"></div>
    <div class="hero-overlay"></div>
    <div class="container hero-content animate-on-load" data-animate="fade-up">
        <span class="hero-label">Eksklusif &amp; Profesional</span>
        <h1>Abadikan Momen Terbaik Anda di <span>Studio Profesional</span> Kami.</h1>
        <p>Ruang kreatif yang dirancang untuk presisi. Temukan studio dengan peralatan terbaik dan atmosfer sinematik untuk hasil karya luar biasa.</p>
        <div class="hero-buttons">
            <a href="#katalog" class="btn btn-primary">Jelajahi Studio</a>
            <a href="#katalog" class="btn btn-secondary">Lihat Katalog</a>
        </div>
    </div>
</section>

<!-- Katalog Studio -->
<section class="section section-katalog" id="katalog">
    <div class="container">
        <div class="section-header animate-on-scroll" data-animate="fade-up">
            <div>
                <h2 class="section-title">Katalog Studio</h2>
                <p class="section-desc">Pilih dari berbagai pilihan ruang dengan tema unik yang telah dikurasi khusus untuk kebutuhan produksi Anda.</p>
            </div>
            <div class="section-controls">
                <button type="button" class="control-btn" aria-label="Filter studio" title="Filter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                </button>
                <button type="button" class="control-btn" aria-label="Tampilan grid" title="Grid">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </button>
            </div>
        </div>

        <?php if (count($daftarStudio) > 0): ?>
        <?php include 'includes/partials/studio-grid.php'; ?>

        <div class="katalog-footer animate-on-scroll" data-animate="fade-up" data-delay="200">
            <a href="views/katalog.php" class="link-arrow">Lihat Semua Koleksi Studio &rarr;</a>
        </div>
        <?php else: ?>
        <p class="text-muted text-center animate-on-scroll" data-animate="fade-up">Belum ada studio tersedia. Data akan muncul setelah admin menambahkan studio.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Booking CTA -->
<section class="section section-booking" id="booking">
    <div class="container">
        <div class="booking-inner animate-on-scroll" data-animate="fade-up">
            <div class="booking-text">
                <h2 class="booking-title">Siap Untuk Sesi Pemotretan Berikutnya?</h2>
                <p class="booking-desc">Pilih tanggal, waktu, dan studio favorit Anda. Tim kami siap membantu mewujudkan visi kreatif Anda dengan mudah dan cepat.</p>
            </div>

            <form class="booking-bar" id="bookingForm" action="views/studio-detail.php" method="get">
                <div class="booking-field">
                    <span class="booking-field-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <div class="booking-field-content">
                        <label for="booking-date">Pilih Tanggal</label>
                        <input type="date" id="booking-date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="booking-divider" aria-hidden="true"></div>

                <div class="booking-field">
                    <span class="booking-field-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    <div class="booking-field-content">
                        <label for="booking-time">Waktu</label>
                        <select id="booking-time" name="waktu">
                            <option value="08:00-12:00">08:00 - 12:00</option>
                            <option value="10:00-14:00" selected>10:00 - 14:00</option>
                            <option value="14:00-18:00">14:00 - 18:00</option>
                            <option value="18:00-22:00">18:00 - 22:00</option>
                        </select>
                    </div>
                </div>

                <div class="booking-divider" aria-hidden="true"></div>

                <div class="booking-field">
                    <span class="booking-field-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <div class="booking-field-content">
                        <label for="booking-studio">Tipe Studio</label>
                        <select id="booking-studio" name="id">
                            <?php if (count($daftarStudio) > 0): ?>
                                <?php foreach ($daftarStudio as $studioItem): ?>
                                <option value="<?= (int) $studioItem['id'] ?>"><?= htmlspecialchars($studioItem['nama']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Belum ada studio</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-cta">Cek Ketersediaan</button>
            </form>
        </div>
    </div>
</section>

<!-- Contact -->
<section class="section section-contact" id="contact">
    <div class="container">
        <div class="contact-layout">
            <div class="contact-info animate-on-scroll" data-animate="fade-right">
                <h2 class="contact-title">Hubungi Kami</h2>
                <p class="contact-desc">
                    Tertarik dengan ruang kreatif kami? Tinggalkan pesan untuk pertanyaan seputar studio, peralatan, atau kerjasama eksklusif.
                </p>

                <div class="contact-info-item">
                    <div class="contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div>
                        <p class="contact-label">Email</p>
                        <p>hello@obsidian.studio</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <p class="contact-label">Studio</p>
                        <p>Jakarta Selatan, Indonesia</p>
                    </div>
                </div>
            </div>

            <div class="form-panel animate-on-scroll" data-animate="fade-left" data-delay="150">
                <?php if ($status == 'success'): ?>
                <div class="alert alert-success animate-alert">
                    Terima kasih! Pesan Anda telah terkirim. Kami akan menghubungi Anda segera.
                </div>
                <?php elseif ($status == 'error'): ?>
                <div class="alert alert-error animate-alert">
                    Gagal mengirim pesan. Pastikan Nama dan Pesan sudah diisi.
                </div>
                <?php endif; ?>

                <form action="<?= $base_path ?>controllers/ContactController.php?action=simpan" method="POST" id="contactForm">
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
</section>

<?php include 'includes/footer.php'; ?>
