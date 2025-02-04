<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

/// Ambil data penyewa dan kamar
$query_penyewa = "SELECT penyewa.*, kamar.nomor_kamar, kamar.harga_sewa, kamar.id_kamar 
                  FROM penyewa
                  JOIN kamar ON penyewa.id_kamar = kamar.id_kamar";
$result_penyewa = mysqli_query($conn, $query_penyewa);

if (!$result_penyewa) {
    die("Error: " . mysqli_error($conn));
}

// Konversi data penyewa ke array untuk JavaScript
$penyewa_data = [];
while ($row = mysqli_fetch_assoc($result_penyewa)) {
    $penyewa_data[] = $row;
}


// Ambil data transaksi
$query_transaksi = "SELECT transaksi.*, penyewa.nama, kamar.nomor_kamar, kontrakan.nama_kontrakan, 
                    (kamar.harga_sewa - transaksi.jumlah_bayar) AS sisa_bayar
                    FROM transaksi
                    JOIN penyewa ON transaksi.id_penyewa = penyewa.id_penyewa
                    JOIN kamar ON transaksi.id_kamar = kamar.id_kamar
                    JOIN kontrakan ON kamar.id_kontrakan = kontrakan.id_kontrakan";

$result_transaksi = mysqli_query($conn, $query_transaksi);

if (!$result_transaksi) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_transaksi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    
    <div class="container">
        <div class="header">
            <h2>Data Transaksi</h2>
            <p>Kelola informasi transaksi kontrakan Anda dengan mudah dan efisien. Tambah, edit, atau hapus data transaksi sesuai kebutuhan.</p>
        </div>

        <div class="content-grid">
            <!-- Form Section -->
            <div class="form-add-transaksi">
                <h3>Tambah Transaksi Baru</h3>
                <form action="../controllers/transaksi.php" method="POST">
                    <!-- Nama Penyewa -->
                    <div class="form-group">
                        <label for="id_penyewa">Nama Penyewa</label>
                        <select id="id_penyewa" name="id_penyewa" onchange="updateKamar()" required>
                            <option value="">-- Pilih Penyewa --</option>
                            <?php foreach ($penyewa_data as $penyewa): ?>
                                <option value="<?= htmlspecialchars($penyewa['id_penyewa']); ?>">
                                    <?= htmlspecialchars($penyewa['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ID Kamar dan Nomor Kamar -->
                    <div class="form-group">
                        <label for="id_kamar">ID Kamar</label>
                        <input type="hidden" id="id_kamar" name="id_kamar" readonly>
                        <input type="text" id="nomor_kamar" placeholder="Nomor Kamar" readonly>
                    </div>

                    <!-- Harga Kamar -->
                    <div class="form-group">
                        <label for="harga_sewa">Harga Kamar</label>
                        <input type="text" id="harga_sewa" name="harga_sewa" placeholder="Harga Kamar" readonly>
                    </div>

                    <!-- Tanggal Sewa -->
                    <div class="form-group">
                        <label for="tanggal_sewa">Tanggal Sewa</label>
                        <input type="date" id="tanggal_sewa" name="tanggal_sewa" value="<?= date('Y-m-d'); ?>" readonly>
                    </div>

                    <!-- Tanggal Jatuh Tempo -->
                    <div class="form-group">
                        <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                        <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="<?= date('Y-m-d', strtotime('+30 days')); ?>" readonly>
                    </div>

                    <!-- Jumlah Bayar -->
                    <div class="form-group">
                        <label for="jumlah_bayar">Jumlah Bayar</label>
                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" required>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="lunas">Lunas</option>
                            <option value="belum_lunas">Belum Lunas</option>
                        </select>
                    </div>

                    <!-- Tombol Tambah -->
                    <button type="submit" name="add_transaksi" class="btn-submit">
                        <i class="fas fa-plus"></i> Tambah Transaksi
                    </button>
                </form>
            </div>

            <!-- Table Section -->
            <div class="table-section">
    <h3>Daftar Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penyewa</th>
                <th>Nomor Kamar</th>
                <th>Nama Kontrakan</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Jumlah Bayar</th>
                <th>Sisa Bayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result_transaksi)): 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['nomor_kamar']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kontrakan']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_sewa']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_jatuh_tempo']); ?></td>
                    <td><?= 'Rp. '. number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                    <td><?= 'Rp. '. number_format($row['sisa_bayar'], 0, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit_transaksi.php?id=<?= $row['id_transaksi']; ?>" class="edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="../controllers/transaksi.php?delete=<?= $row['id_transaksi']; ?>" 
                               class="delete-btn" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

        </div>
    </div>
</body>
<script>
    // Data penyewa dari PHP ke JavaScript
    const penyewaData = <?= json_encode($penyewa_data); ?>;
    
    function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka, 10).toLocaleString('id-ID');
}

function updateKamar() {
    const penyewaSelect = document.getElementById('id_penyewa');
    const kamarInput = document.getElementById('id_kamar');
    const nomorKamar = document.getElementById('nomor_kamar');
    const hargaKamar = document.getElementById('harga_sewa');

    // Cari penyewa yang sesuai
    const selectedPenyewa = penyewaData.find(penyewa => penyewa.id_penyewa === penyewaSelect.value);

    if (selectedPenyewa) {
        kamarInput.value = selectedPenyewa.id_kamar;
        nomorKamar.value = selectedPenyewa.nomor_kamar;
        hargaKamar.value = formatRupiah(selectedPenyewa.harga_sewa); // Format harga ke Rupiah
    } else {
        kamarInput.value = '';
        nomorKamar.value = '';
        hargaKamar.value = '';
    }
}
</script>

</html>
