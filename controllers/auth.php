<?php
session_start();
require_once '../config/database.php';

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Pastikan hashing password sesuai kebutuhan

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = mysqli_fetch_assoc($result);
        header('Location: ../views/dashboard.php');
    } else {
        $_SESSION['error'] = "Login gagal.Periksa kembali username dan password Anda.";
        header('Location: ../views/login.php');
    }
}

// Handle register
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $nama_admin = $_POST['nama_admin'];

    $query = "INSERT INTO admin (username, password, nama_admin, role) VALUES ('$username', '$password', '$nama_admin', 'admin')";
    mysqli_query($conn, $query);
    header('Location: ../views/login.php');
}

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy();
    header('Location: ../views/login.php');
}
?>
