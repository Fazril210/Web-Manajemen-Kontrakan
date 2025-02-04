<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Validasi ID kontrakan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: kontrakan.php');
    exit;
}

$id_kontrakan = intval($_GET['id']);

// Ambil data kontrakan berdasarkan ID
$query = "SELECT * FROM kontrakan WHERE id_kontrakan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_kontrakan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: kontrakan.php');
    exit;
}

$kontrakan = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kontrakan - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_kontrakan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-user-edit"></i> Edit Data Kontrakan</h2>
            <p>Perbarui informasi data kontrakan pada form di bawah ini.</p>
        </div>

        <div class="edit-form-container">
            <div class="edit-form-card">
                <form action="../controllers/kontrakan.php" method="POST" class="edit-form">
                    <input type="hidden" name="id_kontrakan" value="<?= htmlspecialchars($kontrakan['id_kontrakan']); ?>">
                    
                    <div class="form-group">
                        <label for="nama_kontrakan">
                            <i class="fas fa-user"></i> Nama Kontrakan
                        </label>
                        <input type="text" name="nama_kontrakan" id="nama_kontrakan" 
                               value="<?= htmlspecialchars($kontrakan['nama_kontrakan']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lokasi">
                            <i class="fas fa-home"></i> Lokasi
                        </label>
                        <textarea name="lokasi" id="lokasi" required><?= htmlspecialchars($kontrakan['lokasi']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_kamar">
                            <i class="fas fa-user"></i> Jumlah Kamar
                        </label>
                        <input type="number" name="jumlah_kamar" id="jumlah_kamar" 
                               value="<?= htmlspecialchars($kontrakan['jumlah_kamar']); ?>" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="edit_kontrakan" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="kontrakan.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>