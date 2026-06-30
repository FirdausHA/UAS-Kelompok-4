<?php
session_start();

require_once '../config/database.php';
require_once '../models/BukuTamu.php';

$database = new Database();
$db = $database->getConnection();
$bukuTamuModel = new BukuTamu($db);

if (isset($_GET['action']) && $_GET['action'] == 'simpan') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $kota = trim($_POST['kota']);
        $pesan = trim($_POST['pesan']);

        // validasi server-side: cuma nama & pesan yang wajib
        if ($nama == '' || $pesan == '') {
            header("Location: ../index.php?status=error#contact");
            exit();
        }

        // kalau email/kota kosong, simpan NULL
        $email = ($email != '') ? $email : null;
        $kota = ($kota != '') ? $kota : null;

        if ($bukuTamuModel->simpan($nama, $email, $kota, $pesan)) {
            header("Location: ../index.php?status=success#contact");
            exit();
        } else {
            header("Location: ../index.php?status=error#contact");
            exit();
        }
    }
}
?>
