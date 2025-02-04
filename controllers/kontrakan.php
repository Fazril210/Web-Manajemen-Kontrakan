<?php
require_once '../config/database.php';

// Create kontrakan
if (isset($_POST['add_kontrakan'])) {
    $nama_kontrakan = $_POST['nama_kontrakan'];
    $lokasi = $_POST['lokasi'];
    $jumlah_kamar = $_POST['jumlah_kamar'];

    // Validasi input
    if (empty($nama_kontrakan) || empty($lokasi) || empty($jumlah_kamar) || !is_numeric($jumlah_kamar)) {
        echo "Semua data harus diisi dengan benar!";
        exit;
    }

    // Sanitasi input untuk mencegah SQL Injection
    $nama_kontrakan = mysqli_real_escape_string($conn, $nama_kontrakan);
    $lokasi = mysqli_real_escape_string($conn, $lokasi);
    $jumlah_kamar = (int)$jumlah_kamar;  // Pastikan jumlah_kamar adalah integer

    // Query untuk memasukkan data ke dalam database
    $query = "INSERT INTO kontrakan (nama_kontrakan, lokasi, jumlah_kamar) VALUES ('$nama_kontrakan', '$lokasi', '$jumlah_kamar')";
    if (mysqli_query($conn, $query)) {
        header('Location: ../views/kontrakan.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Update kontrakan
if (isset($_POST['edit_kontrakan'])) {
    $id_kontrakan = intval($_POST['id_kontrakan']);
    $nama_kontrakan = $_POST['nama_kontrakan'];
    $lokasi = $_POST['lokasi'];
    $jumlah_kamar = $_POST['jumlah_kamar'];

    // Validasi input
    if (empty($nama_kontrakan) || empty($lokasi) || empty($jumlah_kamar) || !is_numeric($jumlah_kamar)) {
        echo "Semua data harus diisi dengan benar!";
        exit;
    }

    // Sanitasi input untuk mencegah SQL Injection
    $nama_kontrakan = mysqli_real_escape_string($conn, $nama_kontrakan);
    $lokasi = mysqli_real_escape_string($conn, $lokasi);
    $jumlah_kamar = (int)$jumlah_kamar;  // Pastikan jumlah_kamar adalah integer

    // Query untuk memperbarui data kontrakan
    $query = "UPDATE kontrakan SET nama_kontrakan = ?, lokasi = ?, jumlah_kamar = ? WHERE id_kontrakan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssi', $nama_kontrakan, $lokasi, $jumlah_kamar, $id_kontrakan);

    if ($stmt->execute()) {
        header('Location: ../views/kontrakan.php?success=updated');
    } else {
        header('Location: ../views/edit_kontrakan.php?id=' . $id_kontrakan . '&error=failed');
    }
    exit;
}

// Delete kontrakan
if (isset($_GET['delete'])) {
    $id_kontrakan = (int)$_GET['delete'];  // Pastikan id_kontrakan adalah integer

    // Query untuk menghapus data kontrakan
    $query = "DELETE FROM kontrakan WHERE id_kontrakan = '$id_kontrakan'";
    if (mysqli_query($conn, $query)) {
        header('Location: ../views/kontrakan.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


// Create kamar
if (isset($_POST['add_kamar'])) {
    $id_kontrakan = intval($_POST['id_kontrakan']);
    $nomor_kamar = $_POST['nomor_kamar'];
    $harga_sewa = $_POST['harga_sewa'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($id_kontrakan) || empty($nomor_kamar) || empty($harga_sewa) || empty($status) || !is_numeric($harga_sewa)) {
        echo "Semua data kamar harus diisi dengan benar!";
        exit;
    }

    // Sanitasi input
    $nomor_kamar = mysqli_real_escape_string($conn, $nomor_kamar);
    $harga_sewa = (int)$harga_sewa;
    $status = mysqli_real_escape_string($conn, $status);

    // Query untuk menyimpan data kamar
    $query = "INSERT INTO kamar (id_kontrakan, nomor_kamar, harga_sewa, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isis', $id_kontrakan, $nomor_kamar, $harga_sewa, $status);

    if ($stmt->execute()) {
        header('Location: ../views/kontrakan.php?success=add_kamar');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Edit kamar
if (isset($_POST['edit_kamar'])) {
    $id_kamar = intval($_POST['id_kamar']);
    $id_kontrakan = intval($_POST['id_kontrakan']);
    $nomor_kamar = $_POST['nomor_kamar'];
    $harga_sewa = $_POST['harga_sewa'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($id_kamar) || empty($id_kontrakan) || empty($nomor_kamar) || empty($harga_sewa) || empty($status) || !is_numeric($harga_sewa)) {
        echo "Semua data kamar harus diisi dengan benar!";
        exit;
    }

    // Sanitasi input
    $nomor_kamar = mysqli_real_escape_string($conn, $nomor_kamar);
    $harga_sewa = (int)$harga_sewa;
    $status = mysqli_real_escape_string($conn, $status);

    // Query untuk memperbarui data kamar
    $query = "UPDATE kamar SET id_kontrakan = ?, nomor_kamar = ?, harga_sewa = ?, status = ? WHERE id_kamar = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isisi', $id_kontrakan, $nomor_kamar, $harga_sewa, $status, $id_kamar);

    if ($stmt->execute()) {
        header('Location: ../views/kontrakan.php?success=edit_kamar');
    } else {
        header('Location: ../views/edit_kamar.php?id=' . $id_kamar . '&error=failed');
    }
    exit;
}


?>
