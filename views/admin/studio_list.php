<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../config/database.php';
require_once '../../models/Studio.php';

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);
$studios = $studioModel->getAllStudios();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Studio - Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <h1>Daftar Studio</h1>
    <a href="add_studio.php">+ Tambah Studio Baru</a>
    
    <table border="1">
        <tr>
            <th>Nama Studio</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($studios as $studio): ?>
        <tr>
            <td><?php echo htmlspecialchars($studio['nama_studio']); ?></td>
            <td>Rp <?php echo number_format($studio['harga_per_jam'], 0, ',', '.'); ?></td>
            <td>
                <a href="../../controllers/AdminController.php?action=delete_studio&id=<?php echo $studio['id']; ?>" 
                   onclick="return confirm('Yakin ingin menghapus studio ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>