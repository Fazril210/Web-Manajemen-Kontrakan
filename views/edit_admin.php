<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: register.php');
    exit;
}

$id_admin = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM admin WHERE id_admin='$id_admin'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

if (!$admin) {
    header('Location: register.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    
    <div class="container">
    <a href="register.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Admin
            </a>
        <div class="header">
            <h2>Edit Admin</h2>
            <p>Ubah informasi admin sesuai dengan kebutuhan Anda.</p>
        </div>

        <div class="form-add-admin">
            <h3>Formulir Edit Admin</h3>
            <form action="../controllers/admin.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_admin" value="<?= $admin['id_admin']; ?>">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($admin['username']); ?>" required placeholder="Masukkan username">
                </div>

                <div class="form-group">
                    <label for="nama_admin">Nama Admin</label>
                    <input type="text" id="nama_admin" name="nama_admin" value="<?= htmlspecialchars($admin['nama_admin']); ?>" required placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email']); ?>" required placeholder="Masukkan email">
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor Telepon</label>
                    <input type="text" id="no_hp" name="no_hp" value="<?= htmlspecialchars($admin['no_hp']); ?>" required placeholder="Masukkan nomor telepon">
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="admin" <?= $admin['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="super admin" <?= $admin['role'] == 'super admin' ? 'selected' : ''; ?>>Super Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Perbarui Admin</button>
            </form>

           
        </div>
    </div>
</body>
</html>
