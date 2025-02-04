<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Validasi ID kamar
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: kontrakan.php');
    exit;
}

$id_kamar = intval($_GET['id']);

// Ambil data kamar berdasarkan ID
$query = "
    SELECT k.id_kamar, k.nomor_kamar, k.harga_sewa, k.status, kt.id_kontrakan, kt.nama_kontrakan
    FROM kamar k
    JOIN kontrakan kt ON k.id_kontrakan = kt.id_kontrakan
    WHERE k.id_kamar = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_kamar);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: kontrakan.php');
    exit;
}

$kamar = $result->fetch_assoc();

// Ambil daftar kontrakan untuk opsi dropdown
$kontrakanQuery = "SELECT id_kontrakan, nama_kontrakan FROM kontrakan";
$kontrakanResult = $conn->query($kontrakanQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamar - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_kontrakan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-door-open"></i> Edit Data Kamar</h2>
            <p>Perbarui informasi data kamar pada form di bawah ini.</p>
        </div>

        <div class="edit-form-container">
            <div class="edit-form-card">
                <form action="../controllers/kontrakan.php" method="POST" class="edit-form">
                    <input type="hidden" name="id_kamar" value="<?= htmlspecialchars($kamar['id_kamar']); ?>">

                    <div class="form-group">
                        <label for="id_kontrakan">
                            <i class="fas fa-building"></i> Kontrakan
                        </label>
                        <select name="id_kontrakan" id="id_kontrakan" required>
                            <?php while ($row = $kontrakanResult->fetch_assoc()): ?>
                                <option value="<?= $row['id_kontrakan']; ?>"
                                    <?= $row['id_kontrakan'] == $kamar['id_kontrakan'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($row['nama_kontrakan']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nomor_kamar">
                            <i class="fas fa-door-closed"></i> Nomor Kamar
                        </label>
                        <input type="text" name="nomor_kamar" id="nomor_kamar" 
                               value="<?= htmlspecialchars($kamar['nomor_kamar']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_sewa">
                            <i class="fas fa-money-bill-wave"></i> Harga Sewa
                        </label>
                        <input type="number" name="harga_sewa" id="harga_sewa" 
                               value="<?= htmlspecialchars($kamar['harga_sewa']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-info-circle"></i> Status
                        </label>
                        <select name="status" id="status" required>
                            <option value="kosong" <?= $kamar['status'] === 'kosong' ? 'selected' : ''; ?>>Kosong</option>
                            <option value="terisi" <?= $kamar['status'] === 'terisi' ? 'selected' : ''; ?>>Terisi</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="edit_kamar" class="btn-submit">
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
