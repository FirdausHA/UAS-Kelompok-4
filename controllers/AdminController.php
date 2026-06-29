<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/database.php';
require_once '../models/Studio.php';

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);

if (isset($_GET['action'])) {
    
    if ($_GET['action'] == 'add_studio') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nama = $_POST['nama_studio'];
            $deskripsi = $_POST['deskripsi'];
            $harga = $_POST['harga_per_jam'];

            $gambar_nama = $_FILES['gambar_studio']['name'];
            $gambar_tmp = $_FILES['gambar_studio']['tmp_name'];
            $gambar_error = $_FILES['gambar_studio']['error'];
            
            $direktori_tujuan = "../public/uploads/";
            
            $nama_file_baru = uniqid() . '-' . basename($gambar_nama);
            $target_file = $direktori_tujuan . $nama_file_baru;

            if ($gambar_error === 0) {
                if (move_uploaded_file($gambar_tmp, $target_file)) {
                    if ($studioModel->insertStudio($nama, $deskripsi, $harga, $nama_file_baru)) {
                        header("Location: ../views/admin/studio_list.php?status=success");
                        exit();
                    }
                }
            }
            
            header("Location: ../views/admin/studio_list.php?status=failed");
            exit();
        }
    }

    else if ($_GET['action'] == 'delete_studio') {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            if ($studioModel->deleteStudio($id)) {
                header("Location: ../views/admin/studio_list.php?status=deleted");
            } else {
                header("Location: ../views/admin/studio_list.php?status=failed");
            }
            exit();
        }
    }
}
?>