<?php
require_once '../config/database.php';
session_start();  // Pastikan session dimulai

// Cek apakah pengguna sudah login (admin)
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}


$query_reminder = "SELECT t.id_transaksi, p.nama, k.nomor_kamar, t.tanggal_sewa, t.tanggal_jatuh_tempo, t.jumlah_bayar
    FROM transaksi t
    JOIN penyewa p ON t.id_penyewa = p.id_penyewa
    JOIN kamar k ON t.id_kamar = k.id_kamar
    WHERE t.tanggal_jatuh_tempo <= DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
    AND t.status = 'Belum Lunas'";

$result_reminder = mysqli_query($conn, $query_reminder);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="welcome-section">
            <h1>Selamat Datang, <?= isset($_SESSION['admin']['nama_admin']) ? htmlspecialchars($_SESSION['admin']['nama_admin']) : 'Admin'; ?></h1>
            <p>Selamat datang di sistem manajemen kontrakan. Gunakan menu di samping untuk mengelola properti, penyewa, dan transaksi Anda.</p>
        </div>
        
        <div class="stats-grid">

        <div class="reminder-section">
    <h2><i class="fas fa-bell"></i> Pengingat Jatuh Tempo</h2>
    <?php if (mysqli_num_rows($result_reminder) > 0): ?>
        <table class="reminder-table">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Penyewa</th>
                    <th>Nomor Kamar</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_reminder)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['nomor_kamar']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_sewa']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_jatuh_tempo']); ?></td>
                        <td>Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada transaksi yang mendekati jatuh tempo dalam 7 hari ke depan.</p>
    <?php endif; ?>
</div>


        <div class="stat-card total-kontrakan">
        <h3><i class="fas fa-home"></i> Total Kontrakan</h3>
                <div class="number">
                    <?php
                    $query = "SELECT COUNT(*) as total FROM kontrakan";
                    $result = mysqli_query($conn, $query);
                    $data = mysqli_fetch_assoc($result);
                    echo $data['total'];
                    ?>
                </div>
            </div>
            
            <div class="stat-card total-penyewa">
            <h3><i class="fas fa-users"></i> Total Penyewa</h3>
                <div class="number">
                    <?php
                    $query = "SELECT COUNT(*) as total FROM penyewa";
                    $result = mysqli_query($conn, $query);
                    $data = mysqli_fetch_assoc($result);
                    echo $data['total'];
                    ?>
                </div>
            </div>
            
            <div class="stat-card transaksi-bulan-ini">
            <h3><i class="fas fa-calendar-alt"></i> Transaksi Bulan Ini</h3>    
                <div class="number">
                    <?php
                    $query = "SELECT COUNT(*) as total FROM transaksi WHERE MONTH(tanggal_sewa) = MONTH(CURRENT_DATE())";
                    $result = mysqli_query($conn, $query);
                    $data = mysqli_fetch_assoc($result);
                    echo $data['total'];
                    ?>
                </div>
            </div>



            <div class="stat-card total-pembayaran">
            <h3><i class="fas fa-wallet"></i> Total Pembayaran Bulan Ini</h3>
                <div class="number">
                    <?php
                    $query = "SELECT SUM(jumlah_bayar) as total FROM transaksi WHERE MONTH(tanggal_sewa) = MONTH(CURRENT_DATE())";
                    $result = mysqli_query($conn, $query);
                    $data = mysqli_fetch_assoc($result);
                    echo number_format(isset($data['total']) ? $data['total'] : 0, 0, ',', '.');
                    ?>
                </div>
            </div>

            <div class="stat-card kamar-kosong">
            <h3><i class="fas fa-bed"></i> Jumlah Kamar Tersedia</h3>
                <div class="number">
                    <?php
                    $query = "SELECT COUNT(*) as total FROM kamar WHERE status = 'kosong'";
                    $result = mysqli_query($conn, $query);
                    $data = mysqli_fetch_assoc($result);
                    echo $data['total'];
                    ?>
                </div>
            </div>

            <div class="stat-card kamar-terisi">
            <h3><i class="fas fa-bed"></i> Jumlah Kamar Terisi</h3>
                <div class="number">
                    <?php
                    $query = "SELECT COUNT(*) as total FROM kamar WHERE status = 'Terisi'";
                    $result = mysqli_query($conn, $query);
                    $data = mysqli_fetch_assoc($result);
                    echo $data['total'];
                    ?>
                </div>
            </div>
        </div>  
    </div>
</body>
</html>
