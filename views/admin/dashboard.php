<?php
session_start();

require_once '../../config/database.php';
require_once '../../models/Studio.php';
require_once '../../models/User.php';
require_once '../../models/Order.php';
require_once '../../includes/auth_guard.php';
require_once '../../includes/helpers.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);
$userModel = new User($db);
$orderModel = new Order($db);

$totalStudios = $studioModel->countAll();

$ordersToday = 0;
$todayRevenue = 0;
try {
    $ordersToday = $orderModel->countToday();
    $todayRevenue = $orderModel->sumTodayRevenue();
    $dailyData = $orderModel->getDailyChartData();
    $weeklyData = $orderModel->getWeeklyChartData();
    $monthlyData = $orderModel->getMonthlyChartData();
} catch (Exception $e) {
    $ordersToday = 0;
    $todayRevenue = 0;
    $dailyData = [];
    $weeklyData = [];
    $monthlyData = [];
}

// Prepare JSON data for charts
$chartData = [
    'daily' => $dailyData,
    'weekly' => $weeklyData,
    'monthly' => $monthlyData
];



$page_title = 'Studio Intelligence | Admin';
$admin_page = 'dashboard';
$status = $_GET['status'] ?? '';

include '../../includes/admin/header.php';
?>

<header class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Studio Intelligence</h1>
        <p class="admin-page-subtitle">Real-time performance metrics and asset control.</p>
    </div>
    <div class="admin-topbar-actions">
        <button type="button" class="admin-icon-btn" aria-label="Notifikasi">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </button>
        <button type="button" class="admin-icon-btn" aria-label="Pengaturan">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </button>
    </div>
</header>

<?php if ($status === 'created'): ?>
<div class="alert alert-success admin-alert">Studio baru berhasil ditambahkan.</div>
<?php elseif ($status === 'updated'): ?>
<div class="alert alert-success admin-alert">Data studio berhasil diperbarui.</div>
<?php elseif ($status === 'deleted'): ?>
<div class="alert alert-success admin-alert">Studio berhasil dihapus.</div>
<?php elseif ($status === 'error'): ?>
<div class="alert alert-error admin-alert">Gagal memproses data studio.</div>
<?php endif; ?>

<section class="admin-metrics">
    <div class="metric-card">
        <div class="metric-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </div>
        <div>
            <p class="metric-label">Total Studios</p>
            <p class="metric-value"><?= $totalStudios ?></p>
            <p class="metric-trend metric-trend-up">+2 This Quarter</p>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div>
            <p class="metric-label">Orders Today</p>
            <p class="metric-value"><?= max($ordersToday, 0) ?></p>
            <p class="metric-trend">4 slots remaining</p>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <p class="metric-label">Total Revenue Today</p>
            <p class="metric-value"><?= formatRupiah($todayRevenue) ?></p>
            <p class="metric-trend metric-trend-up">Real-time income</p>
        </div>
    </div>
</section>

<section class="admin-inventory" style="margin-top: 32px;">
    <div class="inventory-header">
        <h2 class="inventory-title">Grafik Penjualan</h2>
        <div class="admin-toolbar">
            <select id="chartPeriod" class="admin-select admin-select-sm">
                <option value="daily">Harian (7 hari)</option>
                <option value="weekly">Mingguan (12 minggu)</option>
                <option value="monthly">Bulanan (12 bulan)</option>
            </select>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="salesChart" height="300"></canvas>
    </div>
</section>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const chartData = <?php echo json_encode($chartData); ?>;
let currentChart = null;

function formatDate(dateStr, type) {
    if (type === 'daily') {
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }
    if (type === 'weekly') {
        const d = new Date(dateStr);
        return 'Minggu ' + d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }
    if (type === 'monthly') {
        const [year, month] = dateStr.split('-');
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        return monthNames[parseInt(month)-1] + ' ' + year;
    }
    return dateStr;
}

function renderChart(period) {
    let labels = [];
    let orderData = [];
    let revenueData = [];

    if (period === 'daily') {
        chartData.daily.forEach(item => {
            labels.push(formatDate(item.date, 'daily'));
            orderData.push(item.orders);
            revenueData.push(item.revenue);
        });
    } else if (period === 'weekly') {
        chartData.weekly.forEach(item => {
            labels.push(formatDate(item.date, 'weekly'));
            orderData.push(item.orders);
            revenueData.push(item.revenue);
        });
    } else if (period === 'monthly') {
        chartData.monthly.forEach(item => {
            labels.push(formatDate(item.month, 'monthly'));
            orderData.push(item.orders);
            revenueData.push(item.revenue);
        });
    }

    const ctx = document.getElementById('salesChart').getContext('2d');
    
    if (currentChart) {
        currentChart.destroy();
    }

    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Jumlah Order',
                    data: orderData,
                    borderColor: '#a3492f',
                    backgroundColor: 'rgba(163, 73, 47, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Total Pendapatan (Rp)',
                    data: revenueData,
                    borderColor: '#6fcf97',
                    backgroundColor: 'rgba(111, 207, 151, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { color: '#e8e1dc' }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#948e89' }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#948e89' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { 
                        color: '#948e89',
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
}

document.getElementById('chartPeriod').addEventListener('change', (e) => {
    renderChart(e.target.value);
});

// Initialize chart
renderChart('daily');
</script>

<?php include '../../includes/admin/footer.php'; ?>
