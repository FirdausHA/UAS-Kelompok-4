<?php
session_start();

require_once '../config/database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

if (isset($_GET['action'])) {
    
    if ($_GET['action'] == 'register') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nama = $_POST['nama_lengkap'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            if ($userModel->register($nama, $username, $hashed_password)) {
                header("Location: ../views/auth/login.php?status=success");
                exit();
            } else {
                header("Location: ../views/auth/register.php?status=failed");
                exit();
            }
        }
    }

    else if ($_GET['action'] == 'login') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userData = $userModel->getUserByUsername($username);

            if ($userData && password_verify($password, $userData['password_hash'])) {
                
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['username'] = $userData['username'];
                $_SESSION['role'] = $userData['role']; 

                if ($_SESSION['role'] == 'admin') {
                    header("Location: ../views/admin/dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                header("Location: ../views/auth/login.php?error=kredensial_salah");
                exit();
            }
        }
    }
    
    else if ($_GET['action'] == 'logout') {
        session_destroy();
        header("Location: ../index.php");
        exit();
    }
}
?>