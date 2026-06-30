<?php if (!isset($base_path)) { $base_path = ''; } ?>
<footer class="footer">
    <div class="container">
        <div>
            <div class="footer-brand">Obsidian Studio</div>
            <p class="footer-copy">&copy; <?= date('Y') ?> Obsidian Studio. All rights reserved.</p>
        </div>
        <ul class="footer-links">
            <li><a href="<?= $base_path ?>index.php#home">Home</a></li>
            <li><a href="<?= $base_path ?>index.php#katalog">Katalog</a></li>
            <li><a href="<?= $base_path ?>index.php#contact">Contact</a></li>
        </ul>
    </div>
</footer>
<?php if (!isset($load_main_js) || $load_main_js !== false): ?>
<script src="<?= $base_path ?>assets/js/main.js"></script>
<?php endif; ?>
<?php if (!empty($extra_js)): ?>
    <?php foreach ($extra_js as $js_file): ?>
<script src="<?= $base_path . $js_file ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
