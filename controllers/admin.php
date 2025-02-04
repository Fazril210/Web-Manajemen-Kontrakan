<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../views/login.php');
    exit;
}

// Create
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));
    $nama_admin = mysqli_real_escape_string($conn, $_POST['nama_admin']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    $query = "INSERT INTO admin (username, password, nama_admin, email, no_hp, role) VALUES ('$username', '$password', '$nama_admin', '$email', '$no_hp', '$role')";
    if (mysqli_query($conn, $query)) {
        header('Location: ../views/register.php?success=Admin berhasil ditambahkan.');
    } else {
        header('Location: ../views/register.php?error=Gagal menambahkan admin: ' . mysqli_error($conn));
    }
}

// Read (Display data in register.php)
$result_admin = mysqli_query($conn, "SELECT * FROM admin");

// Update
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_admin = mysqli_real_escape_string($conn, $_POST['nama_admin']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    $query = "UPDATE admin SET username='$username', nama_admin='$nama_admin', email='$email', no_hp='$no_hp', role='$role' WHERE id_admin='$id_admin'";
    if (mysqli_query($conn, $query)) {
        header('Location: ../views/register.php?success=Admin berhasil diperbarui.');
    } else {
        header('Location: ../views/register.php?error=Gagal memperbarui admin: ' . mysqli_error($conn));
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id_admin = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM admin WHERE id_admin='$id_admin'";
    if (mysqli_query($conn, $query)) {
        header('Location: ../views/register.php?success=Admin berhasil dihapus.');
    } else {
        header('Location: ../views/register.php?error=Gagal menghapus admin: ' . mysqli_error($conn));
    }
}
?>
