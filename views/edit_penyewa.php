<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Validasi ID penyewa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: penyewa.php');
    exit;
}

$id_penyewa = intval($_GET['id']);

// Ambil data penyewa berdasarkan ID
$query_penyewa = "SELECT * FROM penyewa WHERE id_penyewa = ?";
$stmt_penyewa = $conn->prepare($query_penyewa);
$stmt_penyewa->bind_param('i', $id_penyewa);
$stmt_penyewa->execute();
$result_penyewa = $stmt_penyewa->get_result();

if ($result_penyewa->num_rows === 0) {
    header('Location: penyewa.php');
    exit;
}

$penyewa = $result_penyewa->fetch_assoc();

// Ambil data kamar untuk dropdown
$query_kamar = "SELECT * FROM kamar";
$result_kamar = $conn->query($query_kamar);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penyewa - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_penyewa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-user-edit"></i> Edit Data Penyewa</h2>
            <p>Perbarui informasi data penyewa dan pilih kamar.</p>
        </div>

        <div class="edit-form-container">
            <div class="edit-form-card">
                <form action="../controllers/penyewa.php" method="POST" class="edit-form">
                    <input type="hidden" name="id_penyewa" value="<?= htmlspecialchars($penyewa['id_penyewa']); ?>">
                    
                    <div class="form-group">
                        <label for="nama">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" name="nama" id="nama" 
                               value="<?= htmlspecialchars($penyewa['nama']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="no_hp">
                            <i class="fas fa-phone"></i> Nomor Handphone
                        </label>
                        <input type="text" name="no_hp" id="no_hp" 
                               value="<?= htmlspecialchars($penyewa['no_hp']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" name="email" id="email" 
                               value="<?= htmlspecialchars($penyewa['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">
                            <i class="fas fa-home"></i> Alamat
                        </label>
                        <textarea name="alamat" id="alamat" required><?= htmlspecialchars($penyewa['alamat']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_kamar">
                            <i class="fas fa-bed"></i> Kamar
                        </label>
                        <select name="id_kamar" id="id_kamar" required>
                            <option value="" disabled>Pilih Kamar</option>
                            <?php while ($kamar = $result_kamar->fetch_assoc()): ?>
                                <option value="<?= $kamar['id_kamar']; ?>" 
                                    <?= $penyewa['id_kamar'] == $kamar['id_kamar'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($kamar['nomor_kamar']); ?> (<?= htmlspecialchars($kamar['status']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="edit_penyewa" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="penyewa.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
