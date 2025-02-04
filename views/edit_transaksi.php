<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Get the transaction ID from the URL
$id_transaksi = $_GET['id'];

// Fetch the transaction details
$query_transaksi = "SELECT transaksi.*, penyewa.nama, penyewa.id_penyewa, kamar.nomor_kamar, kamar.harga_sewa, kamar.id_kamar
                    FROM transaksi
                    JOIN penyewa ON transaksi.id_penyewa = penyewa.id_penyewa
                    JOIN kamar ON transaksi.id_kamar = kamar.id_kamar
                    WHERE transaksi.id_transaksi = '$id_transaksi'";

$result_transaksi = mysqli_query($conn, $query_transaksi);

if (!$result_transaksi || mysqli_num_rows($result_transaksi) == 0) {
    die("Error: " . mysqli_error($conn));
}

$row_transaksi = mysqli_fetch_assoc($result_transaksi);

// Fetch all penyewa data
$query_penyewa = "SELECT * FROM penyewa";
$result_penyewa = mysqli_query($conn, $query_penyewa);

if (!$result_penyewa) {
    die("Error: " . mysqli_error($conn));
}

// Convert penyewa data to an array for JavaScript
$penyewa_data = [];
while ($penyewa = mysqli_fetch_assoc($result_penyewa)) {
    $penyewa_data[] = $penyewa;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_transaksi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>

    <div class="container">
        <div class="header">
            <h2>Edit Transaksi</h2>
            <p>Edit informasi transaksi kontrakan Anda.</p>
        </div>

        <div class="form-edit-transaksi">
            <h3>Form Edit Transaksi</h3>
            <form action="../controllers/transaksi.php" method="POST">
                <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($row_transaksi['id_transaksi']); ?>">

                <!-- Nama Penyewa -->
                <div class="form-group">
                    <label for="id_penyewa">Nama Penyewa</label>
                    <select id="id_penyewa" name="id_penyewa" onchange="updateKamar()" required>
                        <option value="">-- Pilih Penyewa --</option>
                        <?php foreach ($penyewa_data as $penyewa): ?>
                            <option value="<?= htmlspecialchars($penyewa['id_penyewa']); ?>" 
                                    <?= $penyewa['id_penyewa'] == $row_transaksi['id_penyewa'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($penyewa['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ID Kamar dan Nomor Kamar -->
                <div class="form-group">
                    <label for="id_kamar">ID Kamar</label>
                    <input type="hidden" id="id_kamar" name="id_kamar" value="<?= htmlspecialchars($row_transaksi['id_kamar']); ?>" readonly>
                    <input type="text" id="nomor_kamar" value="<?= htmlspecialchars($row_transaksi['nomor_kamar']); ?>" placeholder="Nomor Kamar" readonly>
                </div>

                <!-- Harga Kamar -->
                <div class="form-group">
                    <label for="harga_sewa">Harga Kamar</label>
                    <input type="text" id="harga_sewa" name="harga_sewa" value="<?= htmlspecialchars($row_transaksi['harga_sewa']); ?>" placeholder="Harga Kamar" readonly>
                </div>

                <!-- Tanggal Sewa -->
                <div class="form-group">
                    <label for="tanggal_sewa">Tanggal Sewa</label>
                    <input type="date" id="tanggal_sewa" name="tanggal_sewa" value="<?= htmlspecialchars($row_transaksi['tanggal_sewa']); ?>" required>
                </div>

                <!-- Tanggal Jatuh Tempo -->
                <div class="form-group">
                    <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                    <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="<?= htmlspecialchars($row_transaksi['tanggal_jatuh_tempo']); ?>" required>
                </div>

                <!-- Jumlah Bayar -->
                <div class="form-group">
                    <label for="jumlah_bayar">Jumlah Bayar</label>
                    <input type="number" id="jumlah_bayar" name="jumlah_bayar" value="<?= htmlspecialchars($row_transaksi['jumlah_bayar']); ?>" required>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="lunas" <?= $row_transaksi['status'] == 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                        <option value="belum_lunas" <?= $row_transaksi['status'] == 'belum_lunas' ? 'selected' : ''; ?>>Belum Lunas</option>
                    </select>
                </div>

                <!-- Tombol Update -->
                <button type="submit" name="edit_transaksi" class="btn-submit">
                    <i class="fas fa-edit"></i> Update Transaksi
                </button>
            </form>
        </div>
    </div>

    <script>
        // Data penyewa dari PHP ke JavaScript
        const penyewaData = <?= json_encode($penyewa_data); ?>;

        function updateKamar() {
            const penyewaSelect = document.getElementById('id_penyewa');
            const kamarInput = document.getElementById('id_kamar');
            const nomorKamar = document.getElementById('nomor_kamar');
            const hargaKamar = document.getElementById('harga_sewa');

            // Find the selected penyewa
            const selectedPenyewa = penyewaData.find(penyewa => penyewa.id_penyewa === penyewaSelect.value);

            if (selectedPenyewa) {
                kamarInput.value = selectedPenyewa.id_kamar;
                nomorKamar.value = selectedPenyewa.nomor_kamar;
                hargaKamar.value = selectedPenyewa.harga_sewa; // Show harga kamar
            } else {
                kamarInput.value = '';
                nomorKamar.value = '';
                hargaKamar.value = '';
            }
        }
    </script>
</body>
</html>
