<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Studio Baru</title>
</head>
<body>
    <h2>Tambah Studio</h2>
    <form action="../../controllers/AdminController.php?action=add_studio" method="POST" enctype="multipart/form-data">
        <label>Nama Studio:</label><br>
        <input type="text" name="nama_studio" required><br><br>
        
        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" required></textarea><br><br>
        
        <label>Harga per Jam:</label><br>
        <input type="number" name="harga_per_jam" required><br><br>
        
        <label>Gambar Studio:</label><br>
        <input type="file" name="gambar_studio" accept="image/*" required><br><br>
        
        <button type="submit">Simpan Studio</button>
    </form>
</body>
</html>