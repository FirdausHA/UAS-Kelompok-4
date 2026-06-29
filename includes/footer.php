<?php if (!isset($base_path)) { $base_path = ''; } ?>
<footer class="footer">
    <div class="container">
        <div>
            <div class="footer-brand">Obsidian Studio</div>
            <p class="footer-copy">&copy; <?= date('Y') ?> Obsidian Studio. All rights reserved.</p>
        </div>
        <ul class="footer-links">
            <li><a href="<?= $base_path ?>views/contact.php">Contact</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
        </ul>
    </div>
</footer>
</body>
</html>
