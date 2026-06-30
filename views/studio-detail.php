<?php
session_start();

$base_path = '../';

require_once $base_path . 'config/database.php';
require_once $base_path . 'models/Studio.php';
require_once $base_path . 'includes/auth_guard.php';
require_once $base_path . 'includes/helpers.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: ' . $base_path . 'index.php#katalog');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);
$studio = $studioModel->getById($id);

if (!$studio) {
    header('Location: ' . $base_path . 'index.php#katalog');
    exit();
}

function formatHargaIDR($angka) {
    return 'IDR ' . number_format($angka, 0, ',', '.');
}

function formatLuasSqm($luas) {
    if (empty($luas)) return '—';
    return preg_replace('/[^0-9]/', '', $luas) . 'sqm';
}

function getDeskripsiDetail($studio) {
    if (!empty($studio['deskripsi_detail'])) {
        return $studio['deskripsi_detail'];
    }
    return $studio['deskripsi'] . ' Ruang ini dilengkapi peralatan lighting profesional, area makeup, dan lounge eksklusif untuk mendukung sesi produksi Anda dari awal hingga selesai.';
}

function getMaxKapasitas($studio) {
    if (!empty($studio['max_kapasitas'])) {
        return $studio['max_kapasitas'];
    }
    return 'Max 10 People';
}

function getAmenitas($studio) {
    $default = [
        ['icon' => 'wifi', 'label' => 'High-speed Fiber'],
        ['icon' => 'closet', 'label' => 'Private Fitting Room'],
        ['icon' => 'lounge', 'label' => 'Premium Lounge'],
    ];

    if (empty($studio['amenitas'])) {
        return $default;
    }

    $labels = array_map('trim', explode('|', $studio['amenitas']));
    $icons = ['wifi', 'closet', 'lounge'];
    $items = [];

    foreach ($labels as $i => $label) {
        if ($label === '') continue;
        $items[] = [
            'icon' => $icons[$i] ?? 'wifi',
            'label' => $label,
        ];
    }

    return count($items) > 0 ? $items : $default;
}

$amenitas = getAmenitas($studio);
$is_pelanggan = isPelanggan();
$page_title = htmlspecialchars($studio['nama']) . ' | Obsidian Studio';
$active_menu = 'catalog';
$extra_css = ['assets/css/detail.css'];
$extra_js = ['assets/js/detail.js'];
$load_main_js = false;

include $base_path . 'includes/header.php';
?>

<main class="detail-page" id="studioDetail"
      data-studio-id="<?= (int) $studio['id'] ?>"
      data-base-price="<?= (int) $studio['harga'] ?>"
      data-studio-name="<?= htmlspecialchars($studio['nama']) ?>"
      data-is-pelanggan="<?= $is_pelanggan ? '1' : '0' ?>">

    <div class="container detail-layout">

        <!-- Kolom kiri: info studio -->
        <div class="detail-main animate-on-load" data-animate="fade-up">
            <div class="detail-hero-img">
                <img
                    src="<?= htmlspecialchars(getStudioImageUrl($studio['gambar'])) ?>"
                    alt="<?= htmlspecialchars($studio['nama']) ?>"
                >
            </div>

            <h1 class="detail-title"><?= htmlspecialchars($studio['nama']) ?></h1>

            <div class="detail-specs">
                <div class="detail-spec">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
                    <span><?= htmlspecialchars(formatLuasSqm($studio['luas_area'])) ?></span>
                </div>
                <div class="detail-spec">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span><?= htmlspecialchars(getMaxKapasitas($studio)) ?></span>
                </div>
                <div class="detail-spec">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4"/><path d="m6.8 15-3.5 2"/><path d="m20.7 17-3.5-2"/><path d="M6.8 9 3.3 7"/><path d="m20.7 7-3.5 2"/><circle cx="12" cy="13" r="4"/></svg>
                    <span>Full AC</span>
                </div>
            </div>

            <p class="detail-desc"><?= htmlspecialchars(getDeskripsiDetail($studio)) ?></p>

            <div class="detail-amenities">
                <?php foreach ($amenitas as $item): ?>
                <div class="amenity-box">
                    <?php if ($item['icon'] === 'wifi'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                    <?php elseif ($item['icon'] === 'closet'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M12 4v16"/><path d="M8 8h.01"/><path d="M16 8h.01"/></svg>
                    <?php else: ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                    <?php endif; ?>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Kolom kanan: booking sidebar -->
        <aside class="detail-booking animate-on-load" data-animate="fade-left" data-delay="150">
            <div class="booking-panel">

                <div class="booking-section">
                    <h3 class="booking-section-title">Select Date</h3>
                    <div class="calendar" id="bookingCalendar">
                        <div class="calendar-header">
                            <button type="button" class="calendar-nav" id="calPrev" aria-label="Bulan sebelumnya">&lsaquo;</button>
                            <span class="calendar-month" id="calMonthLabel"></span>
                            <button type="button" class="calendar-nav" id="calNext" aria-label="Bulan berikutnya">&rsaquo;</button>
                        </div>
                        <div class="calendar-weekdays">
                            <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                        </div>
                        <div class="calendar-days" id="calDays"></div>
                    </div>
                </div>

                <div class="booking-section">
                    <h3 class="booking-section-title">Available Hours</h3>
                    <div class="time-slots" id="timeSlots">
                        <?php
                        $prefillWaktu = isset($_GET['waktu']) ? trim($_GET['waktu']) : '';
                        $hasPrefillWaktu = $prefillWaktu !== '';
                        $hours = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
                        foreach ($hours as $hour):
                            $isSelected = false;
                            if ($hasPrefillWaktu) {
                                $startHour = trim(explode('-', $prefillWaktu)[0]);
                                $isSelected = ($hour === $startHour);
                            }
                        ?>
                        <button
                            type="button"
                            class="time-slot<?= $isSelected ? ' is-selected' : '' ?>"
                            data-hour="<?= $hour ?>"
                        ><?= $hour ?></button>
                        <?php endforeach; ?>
                    </div>
                    <p class="time-slots-hint" id="timeSlotsHint">Pilih jam sesi yang Anda inginkan</p>
                </div>

                <div class="booking-section">
                    <h3 class="booking-section-title">Add-on Packages</h3>
                    <div class="addon-list" id="addonList">
                        <label class="addon-item is-selected">
                            <input type="radio" name="addon" value="0" data-label="Studio Only" checked>
                            <span class="addon-radio"></span>
                            <span class="addon-info">
                                <span class="addon-name">Studio Only</span>
                                <span class="addon-price">IDR 0</span>
                            </span>
                        </label>
                        <label class="addon-item">
                            <input type="radio" name="addon" value="250000" data-label="Graduation Bundle (+2h)">
                            <span class="addon-radio"></span>
                            <span class="addon-info">
                                <span class="addon-name">Graduation Bundle (+2h)</span>
                                <span class="addon-price">+250k</span>
                            </span>
                        </label>
                        <label class="addon-item">
                            <input type="radio" name="addon" value="450000" data-label="Family Portrait XL">
                            <span class="addon-radio"></span>
                            <span class="addon-info">
                                <span class="addon-name">Family Portrait XL</span>
                                <span class="addon-price">+450k</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="booking-total">
                    <span class="booking-total-label">Total Investment</span>
                    <span class="booking-total-price" id="totalPrice"><?= formatHargaIDR((int) $studio['harga']) ?></span>
                </div>

                <button type="button" class="btn btn-cta btn-payment" id="btnPayment">
                    Lanjut ke Pembayaran
                </button>

            </div>
        </aside>

    </div>
</main>

<?php include $base_path . 'includes/footer.php'; ?>
