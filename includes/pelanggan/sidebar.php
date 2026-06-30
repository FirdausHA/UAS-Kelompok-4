<?php
if (!isset($user)) return;
$initials = userInitials($user['nama_lengkap']);
?>
<aside class="pelanggan-sidebar">
    <div class="pelanggan-avatar"><?= htmlspecialchars($initials) ?></div>
    <h2 class="pelanggan-name"><?= htmlspecialchars($user['nama_lengkap']) ?></h2>
    <p class="pelanggan-email"><?= htmlspecialchars($user['email']) ?></p>

    <div class="pelanggan-sidebar-actions">
        <a href="profil.php" class="btn btn-primary btn-sidebar<?= ($pelanggan_page ?? '') === 'profil' ? ' is-active' : '' ?>">Ubah Profil</a>
        <a href="riwayat.php" class="btn btn-secondary btn-sidebar<?= ($pelanggan_page ?? '') === 'riwayat' ? ' is-active' : '' ?>">Riwayat Transaksi</a>
    </div>

    <div class="membership-box">
        <span class="membership-label">Status Keanggotaan</span>
        <div class="membership-row">
            <span>Premium Member</span>
            <span class="badge badge-available">Aktif</span>
        </div>
    </div>
</aside>
