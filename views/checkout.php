<?php
session_start();

$base_path = '../';

require_once $base_path . 'config/database.php';
<<<<<<< HEAD
=======
require_once $base_path . 'config/midtrans.php';
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
require_once $base_path . 'models/Studio.php';
require_once $base_path . 'models/Order.php';
require_once $base_path . 'includes/auth_guard.php';

requirePelanggan();

$studio_id = isset($_GET['studio_id']) ? (int) $_GET['studio_id'] : 0;
$tanggal = isset($_GET['tanggal']) ? trim($_GET['tanggal']) : '';
$waktu = isset($_GET['waktu']) ? trim($_GET['waktu']) : '';
$addon_label = isset($_GET['addon_label']) ? trim($_GET['addon_label']) : 'Studio Only';
$total = isset($_GET['total']) ? (int) $_GET['total'] : 0;

if ($studio_id <= 0) {
    header('Location: ' . $base_path . 'index.php#katalog');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);
$studio = $studioModel->getById($studio_id);

if (!$studio) {
    header('Location: ' . $base_path . 'index.php#katalog');
    exit();
}

if ($total <= 0) {
    $total = (int) $studio['harga'];
}

if ($tanggal === '') {
    $tanggal = date('Y-m-d');
}

if ($waktu === '') {
    $waktu = '14:00-18:00';
}

function formatHargaIDR($angka) {
    return 'IDR ' . number_format($angka, 0, ',', '.');
}

function formatTanggalIndo($tanggal) {
    $ts = strtotime($tanggal);
    if (!$ts) return $tanggal;
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $d = (int) date('j', $ts);
    $m = (int) date('n', $ts);
    $y = date('Y', $ts);
    return $d . ' ' . $bulan[$m] . ' ' . $y;
}

$orderModel = new Order($db);
$status = isset($_GET['status']) ? $_GET['status'] : '';
$order_code = '';
$order_db_id = isset($_GET['order_db_id']) ? (int) $_GET['order_db_id'] : 0;

if ($status === 'success') {
    $order_code = isset($_GET['order_code']) ? trim($_GET['order_code']) : '';
    if ($order_code === '' && $order_db_id > 0) {
        $paidOrder = $orderModel->getById($order_db_id);
        $order_code = $paidOrder['order_code'] ?? '';
    }
} else {
    try {
        $reuse = false;
        if ($order_db_id > 0) {
            $existing = $orderModel->getById($order_db_id);
            if ($existing
                && (int) $existing['user_id'] === (int) $_SESSION['user_id']
                && empty($existing['bukti_file'])
                && (int) $existing['studio_id'] === $studio_id
            ) {
                $order_code = $existing['order_code'];
                $reuse = true;
            }
        }

        if (!$reuse) {
            $order_code = $orderModel->generateOrderCode();
            $order_db_id = $orderModel->createPending([
                'order_code' => $order_code,
                'user_id' => (int) $_SESSION['user_id'],
                'studio_id' => $studio_id,
                'tanggal' => $tanggal,
                'waktu' => $waktu,
                'addon_label' => $addon_label,
                'total' => $total,
                'payment_method' => 'bank',
            ]);
            $_SESSION['checkout_order_id'] = $order_db_id;
        }
    } catch (Exception $e) {
        $order_code = 'ORD-' . date('Y') . '-TEMP';
        $order_db_id = 0;
    }
}

$page_title = 'Checkout | Obsidian Studio';
$active_menu = 'catalog';
$extra_css = ['assets/css/checkout.css'];
$extra_js = ['assets/js/checkout.js'];
$load_main_js = false;

include $base_path . 'includes/header.php';
?>

<main class="checkout-page">
    <div class="container">

        <header class="checkout-header animate-on-load" data-animate="fade-up">
            <h1 class="checkout-title">Checkout</h1>
            <p class="checkout-subtitle">Selesaikan pembayaran untuk mengamankan slot studio Anda.</p>
        </header>

        <?php if ($status === 'success'): ?>
        <div class="alert alert-success checkout-alert animate-on-load" data-animate="fade-up">
            Pembayaran berhasil dikonfirmasi! Order <strong>#<?= htmlspecialchars($order_code) ?></strong> sedang diverifikasi dalam 1×24 jam.
        </div>
        <?php elseif ($status === 'error'): ?>
        <div class="alert alert-error checkout-alert animate-on-load" data-animate="fade-up">
            Gagal mengunggah bukti pembayaran. Pastikan file valid (PNG, JPG, PDF, maks. 5MB).
        </div>
        <?php endif; ?>

        <div class="checkout-top-grid animate-on-load" data-animate="fade-up" data-delay="100">

            <!-- Ringkasan booking -->
            <div class="checkout-summary-card">
                <div class="summary-thumb">
                    <img src="<?= htmlspecialchars($studio['gambar']) ?>" alt="<?= htmlspecialchars($studio['nama']) ?>">
                </div>
                <div class="summary-details">
                    <h2 class="summary-studio"><?= htmlspecialchars($studio['nama']) ?></h2>
                    <ul class="summary-meta">
                        <li><span class="meta-label">Order ID</span> <span>#<?= htmlspecialchars($order_code) ?></span></li>
                        <li><span class="meta-label">Tanggal</span> <span><?= htmlspecialchars(formatTanggalIndo($tanggal)) ?></span></li>
                        <li><span class="meta-label">Waktu</span> <span><?= htmlspecialchars(str_replace('-', ' - ', $waktu)) ?></span></li>
                        <li><span class="meta-label">Paket</span> <span><?= htmlspecialchars($addon_label) ?></span></li>
                        <li><span class="meta-label">Lokasi</span> <span>Obsidian Hub, Central — Jakarta Selatan</span></li>
                    </ul>
                </div>
                <div class="summary-total-wrap">
                    <span class="summary-total-label">Total</span>
                    <span class="summary-total-price"><?= formatHargaIDR($total) ?></span>
                </div>
            </div>

            <!-- Sidebar kanan -->
            <aside class="checkout-sidebar">
                <div class="sidebar-card sidebar-secure">
                    <div class="sidebar-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <h3>Secure Transaction</h3>
                        <p>Transaksi Anda dilindungi enkripsi SSL dan diproses melalui gateway pembayaran terpercaya.</p>
                    </div>
                </div>

                <div class="sidebar-card sidebar-support">
                    <div class="sidebar-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                    </div>
                    <div>
                        <h3>24/7 Priority Support</h3>
                        <p>Butuh bantuan? Hubungi tim kami kapan saja melalui WhatsApp atau email.</p>
                    </div>
                </div>

                <div class="sidebar-card sidebar-map">
                    <div class="map-placeholder">
                        <iframe
                            title="Lokasi Obsidian Studio"
                            src="https://maps.google.com/maps?q=Jl.+Senopati+No.+88+Jakarta+Selatan&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                    <p class="map-address">Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan</p>
                </div>
            </aside>
        </div>

        <!-- Metode pembayaran -->
        <div class="checkout-payment-grid animate-on-load" data-animate="fade-up" data-delay="200">
            <div class="payment-card">
                <h3 class="payment-card-title">Payment Method</h3>
                <div class="payment-methods" id="paymentMethods">
                    <label class="payment-method is-selected">
<<<<<<< HEAD
                        <input type="radio" name="payment_method" value="bank" checked>
                        <span class="payment-radio"></span>
                        <span>Bank Transfer (BCA, Mandiri, BNI)</span>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="ewallet">
                        <span class="payment-radio"></span>
                        <span>E-Wallet (GoPay, OVO, ShopeePay)</span>
=======
                        <input type="radio" name="payment_method" value="midtrans" checked>
                        <span class="payment-radio"></span>
                        <span>Midtrans (All Payment Methods: Transfer, E-Wallet, QRIS)</span>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="manual">
                        <span class="payment-radio"></span>
                        <span>Manual Transfer (Bank)</span>
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
                    </label>
                </div>
            </div>

<<<<<<< HEAD
            <div class="payment-card" id="bankDetails">
=======
            <!-- Midtrans Section -->
            <div class="payment-card" id="midtransDetails">
                <h3 class="payment-card-title">Bayar via Midtrans</h3>
                <p class="midtrans-desc">Klik tombol di bawah untuk membuka halaman pembayaran Midtrans. Anda bisa memilih berbagai metode pembayaran: Transfer Bank, E-Wallet, QRIS, dll.</p>
                <button type="button" class="btn btn-cta btn-midtrans" id="btnMidtransPay">
                    Bayar Sekarang
                </button>
            </div>

            <!-- Manual Transfer Section -->
            <div class="payment-card" id="manualDetails" hidden>
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
                <h3 class="payment-card-title">Transfer Details</h3>
                <div class="bank-info">
                    <div class="bank-row">
                        <span class="bank-label">Bank</span>
                        <span class="bank-value">BCA</span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">No. Rekening</span>
                        <span class="bank-value bank-account">
                            <span id="accountNumber">5830 1925 14</span>
                            <button type="button" class="btn-copy" id="btnCopyAccount">COPY</button>
                        </span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">Atas Nama</span>
                        <span class="bank-value">PT Obsidian Kreatif Studio</span>
                    </div>
                </div>
                <ol class="bank-instructions">
                    <li>Transfer sesuai jumlah <strong><?= formatHargaIDR($total) ?></strong> ke rekening di atas.</li>
                    <li>Simpan bukti transfer (screenshot atau PDF).</li>
                    <li>Upload bukti pembayaran di bawah, lalu klik Konfirmasi Pembayaran.</li>
                </ol>
            </div>
<<<<<<< HEAD

            <div class="payment-card payment-card-ewallet" id="ewalletDetails" hidden>
                <h3 class="payment-card-title">E-Wallet Details</h3>
                <p class="ewallet-desc">Scan QR atau transfer ke nomor berikut:</p>
                <div class="bank-info">
                    <div class="bank-row">
                        <span class="bank-label">GoPay / OVO</span>
                        <span class="bank-value">0812-3456-7890</span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-label">ShopeePay</span>
                        <span class="bank-value">0812-3456-7890</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload & konfirmasi -->
        <?php if ($status !== 'success'): ?>
        <form
            class="checkout-confirm animate-on-load"
            data-animate="fade-up"
            data-delay="300"
            action="<?= $base_path ?>controllers/PaymentController.php?action=konfirmasi"
            method="POST"
            enctype="multipart/form-data"
            id="checkoutForm"
        >
            <input type="hidden" name="order_db_id" value="<?= (int) $order_db_id ?>">
            <input type="hidden" name="order_code" value="<?= htmlspecialchars($order_code) ?>">
            <input type="hidden" name="studio_id" value="<?= (int) $studio_id ?>">
            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
            <input type="hidden" name="waktu" value="<?= htmlspecialchars($waktu) ?>">
            <input type="hidden" name="addon_label" value="<?= htmlspecialchars($addon_label) ?>">
            <input type="hidden" name="total" value="<?= (int) $total ?>">
            <input type="hidden" name="payment_method" id="paymentMethodInput" value="bank">

            <div class="upload-zone" id="uploadZone">
                <input type="file" name="bukti" id="buktiInput" accept=".png,.jpg,.jpeg,.pdf" hidden required>
                <div class="upload-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <p class="upload-text">Klik untuk upload atau drag &amp; drop</p>
                <p class="upload-hint">PNG, JPG atau PDF (maks. 5MB)</p>
                <p class="upload-filename" id="uploadFilename" hidden></p>
            </div>

            <button type="submit" class="btn btn-cta btn-confirm" id="btnConfirm">
                Konfirmasi Pembayaran
            </button>
        </form>
=======
        </div>

        </div>
        <!-- End Manual Upload Section -->

        <!-- Success Section (for both payment methods) -->
        <?php if ($status !== 'success'): ?>
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
        <?php else: ?>
        <div class="checkout-success-actions animate-on-load" data-animate="fade-up">
            <a href="<?= $base_path ?>views/pelanggan/riwayat.php" class="btn btn-primary">Lihat Riwayat Transaksi</a>
            <a href="<?= $base_path ?>index.php" class="btn btn-secondary">Kembali ke Beranda</a>
        </div>
        <?php endif; ?>

    </div>
</main>

<<<<<<< HEAD
=======
<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= htmlspecialchars(MIDTRANS_CLIENT_KEY) ?>"></script>

<script>
// Checkout page JS
document.addEventListener('DOMContentLoaded', function() {
    const orderDbId = <?= (int) $order_db_id ?>;
    const basePath = '<?= $base_path ?>';
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const midtransDetails = document.getElementById('midtransDetails');
    const manualDetails = document.getElementById('manualDetails');
    const manualUploadSection = document.getElementById('manualUploadSection');
    const btnMidtransPay = document.getElementById('btnMidtransPay');

    // Show/hide payment method sections
    paymentMethods.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('is-selected'));
            this.closest('.payment-method').classList.add('is-selected');

            if (this.value === 'midtrans') {
                midtransDetails.hidden = false;
                manualDetails.hidden = true;
                manualUploadSection.hidden = true;
            } else {
                midtransDetails.hidden = true;
                manualDetails.hidden = false;
                manualUploadSection.hidden = false;
            }
        });
    });

    // Handle Midtrans payment
    if (btnMidtransPay) {
        btnMidtransPay.addEventListener('click', async function() {
            try {
                btnMidtransPay.disabled = true;
                btnMidtransPay.textContent = 'Loading...';

                const response = await fetch(basePath + 'controllers/MidtransController.php?action=create-snap', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'order_db_id': orderDbId
                    })
                });

                const data = await response.json();

                if (data.success && data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            // Payment success, redirect to finish
                            window.location.href = basePath + 'controllers/MidtransController.php?action=finish&order_id=' + result.order_id;
                        },
                        onPending: function(result) {
                            // Payment pending, redirect to unfinish
                            window.location.href = basePath + 'controllers/MidtransController.php?action=unfinish&order_id=' + result.order_id;
                        },
                        onError: function(result) {
                            // Payment error, redirect to error
                            window.location.href = basePath + 'controllers/MidtransController.php?action=error&order_id=' + result.order_id;
                        },
                        onClose: function() {
                            btnMidtransPay.disabled = false;
                            btnMidtransPay.textContent = 'Bayar Sekarang';
                        }
                    });
                } else {
                    alert('Gagal membuat transaksi. Silakan coba lagi.');
                    btnMidtransPay.disabled = false;
                    btnMidtransPay.textContent = 'Bayar Sekarang';
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                btnMidtransPay.disabled = false;
                btnMidtransPay.textContent = 'Bayar Sekarang';
            }
        });
    }

    // Existing checkout JS (for manual upload)
    const uploadZone = document.getElementById('uploadZone');
    const buktiInput = document.getElementById('buktiInput');
    const uploadFilename = document.getElementById('uploadFilename');

    if (uploadZone && buktiInput) {
        uploadZone.addEventListener('click', () => buktiInput.click());

        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('drag-over');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('drag-over');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                buktiInput.files = e.dataTransfer.files;
                updateFilename();
            }
        });

        buktiInput.addEventListener('change', updateFilename);
    }

    function updateFilename() {
        if (!buktiInput || !uploadFilename) return;
        if (buktiInput.files.length) {
            uploadFilename.textContent = 'Selected: ' + buktiInput.files[0].name;
            uploadFilename.hidden = false;
        } else {
            uploadFilename.hidden = true;
        }
    }

    // Copy account number
    const btnCopyAccount = document.getElementById('btnCopyAccount');
    const accountNumber = document.getElementById('accountNumber');
    if (btnCopyAccount && accountNumber) {
        btnCopyAccount.addEventListener('click', () => {
            navigator.clipboard.writeText(accountNumber.textContent.replace(/\s/g, '')).then(() => {
                const originalText = btnCopyAccount.textContent;
                btnCopyAccount.textContent = 'COPIED!';
                setTimeout(() => {
                    btnCopyAccount.textContent = originalText;
                }, 1500);
            });
        });
    }
});
</script>

>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
<?php include $base_path . 'includes/footer.php'; ?>
