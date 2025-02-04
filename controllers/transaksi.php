<?php
require_once '../config/database.php';

if (isset($_POST['add_transaksi'])) {
    $id_penyewa = $_POST['id_penyewa'];
    $id_kamar = $_POST['id_kamar'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $status = $_POST['status'];

    // Query insert transaksi
    $query = "INSERT INTO transaksi (id_penyewa, id_kamar, tanggal_sewa, tanggal_jatuh_tempo, jumlah_bayar, status)
              VALUES ('$id_penyewa', '$id_kamar', '$tanggal_sewa', '$tanggal_jatuh_tempo', '$jumlah_bayar', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header('Location: ../views/transaksi.php?status=success');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


if (isset($_GET['delete'])) {
    $id_transaksi = $_GET['delete'];
    $query = "DELETE FROM transaksi WHERE id_transaksi = '$id_transaksi'";

    if (mysqli_query($conn, $query)) {
        header('Location: ../views/data_transaksi.php?deleted=1');
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_transaksi'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $id_penyewa = $_POST['id_penyewa'];
    $id_kamar = $_POST['id_kamar']; // Make sure the correct ID Kamar is passed
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $status = $_POST['status'];

    $query = "UPDATE transaksi 
              SET id_penyewa = '$id_penyewa', id_kamar = '$id_kamar', tanggal_sewa = '$tanggal_sewa', 
                  tanggal_jatuh_tempo = '$tanggal_jatuh_tempo', jumlah_bayar = '$jumlah_bayar', status = '$status' 
              WHERE id_transaksi = '$id_transaksi'";

    if (mysqli_query($conn, $query)) {
        header('Location: ../views/data_transaksi.php?updated=1');
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

?>
