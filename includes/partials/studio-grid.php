<?php
if (!isset($daftarStudio)) {
    $daftarStudio = [];
}
?>
<?php if (count($daftarStudio) > 0): ?>
<div class="studio-grid">
    <?php foreach ($daftarStudio as $index => $studio):
        $delay = min($index * 120, 480);
        if (!empty($detail_url_mode) && $detail_url_mode === 'relative') {
            $detailUrl = 'studio-detail.php?id=' . (int) $studio['id'];
        } else {
            $detailUrl = 'views/studio-detail.php?id=' . (int) $studio['id'];
        }
    ?>
    <article
        class="card studio-card animate-on-scroll"
        data-animate="fade-up"
        data-delay="<?= $delay ?>"
        data-studio="<?= htmlspecialchars($studio['nama']) ?>"
    >
        <div class="card-img-wrap">
            <img
                src="<?= htmlspecialchars($studio['gambar']) ?>"
                alt="<?= htmlspecialchars($studio['nama']) ?>"
                class="card-img"
                loading="lazy"
            >
            <?php if (!empty($studio['is_populer'])): ?>
            <span class="card-badge">Populer</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="card-title-row">
                <h3 class="card-title"><?= htmlspecialchars($studio['nama']) ?></h3>
                <button type="button" class="btn-favorite" aria-label="Tambah ke favorit" data-favorite>
                    <svg class="heart-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </button>
            </div>
            <div class="card-meta">
                <?php if (!empty($studio['rating'])): ?>
                <span class="rating">&#9733; <?= htmlspecialchars($studio['rating']) ?></span>
                <?php endif; ?>
                <?php if (!empty($studio['luas_area'])): ?>
                <span class="card-area"><?= htmlspecialchars($studio['luas_area']) ?></span>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="card-price-wrap">
                    <span class="card-price-label">Mulai Dari</span>
                    <span class="card-price"><?= formatHarga($studio['harga']) ?><small>/jam</small></span>
                </div>
                <a href="<?= htmlspecialchars($detailUrl) ?>" class="btn btn-outline btn-detail">Lihat Detail</a>
            </div>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php else: ?>
<p class="text-muted text-center animate-on-scroll" data-animate="fade-up">Belum ada studio tersedia. Data akan muncul setelah admin menambahkan studio.</p>
<?php endif; ?>
