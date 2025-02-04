<?php
require_once '../config/database.php';

// Create penyewa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $email = trim($_POST['email']);
    $alamat = trim($_POST['alamat']);
    $id_kamar = intval($_POST['kamar']); // Ensure this is an integer

    // Basic validation
    if (empty($nama) || empty($no_hp) || empty($email) || empty($alamat) || empty($id_kamar)) {
        header('Location: ../views/penyewa.php?error=emptyfields');
        exit;
    }

    // Validate phone number format
    if (!preg_match('/^[0-9]{10,15}$/', $no_hp)) {
        header('Location: ../views/penyewa.php?error=invalidphone');
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../views/penyewa.php?error=invalidemail');
        exit;
    }

    // Prepare and execute the query safely
    // Prepare and execute the query safely
$query = "INSERT INTO penyewa (nama, no_hp, email, alamat, id_kamar) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssssi', $nama, $no_hp, $email, $alamat, $id_kamar);

if ($stmt->execute()) {
    // Update the status of the room to 'terisi'
    $update_kamar_query = "UPDATE kamar SET status = 'terisi' WHERE id_kamar = ?";
    $update_stmt = $conn->prepare($update_kamar_query);
    $update_stmt->bind_param('i', $id_kamar);
    $update_stmt->execute();

    header('Location: ../views/penyewa.php?success=added');
} else {
    header('Location: ../views/penyewa.php?error=failed');
}
exit;

}


// Update penyewa
if (isset($_POST['edit_penyewa'])) {
    $id_penyewa = intval($_POST['id_penyewa']);
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $id_kamar_baru = intval($_POST['id_kamar']);

    // Ambil kamar lama
    $query_kamar_lama = "SELECT id_kamar FROM penyewa WHERE id_penyewa = ?";
    $stmt_kamar_lama = $conn->prepare($query_kamar_lama);
    $stmt_kamar_lama->bind_param('i', $id_penyewa);
    $stmt_kamar_lama->execute();
    $result_kamar_lama = $stmt_kamar_lama->get_result();
    $kamar_lama = $result_kamar_lama->fetch_assoc();

    // Update data penyewa
    $update_penyewa = "UPDATE penyewa SET nama = ?, no_hp = ?, email = ?, alamat = ?, id_kamar = ? WHERE id_penyewa = ?";
    $stmt_update = $conn->prepare($update_penyewa);
    $stmt_update->bind_param('ssssii', $nama, $no_hp, $email, $alamat, $id_kamar_baru, $id_penyewa);

    if ($stmt_update->execute()) {
        // Perbarui status kamar
        if ($kamar_lama['id_kamar'] != $id_kamar_baru) {
            $conn->query("UPDATE kamar SET status = 'kosong' WHERE id_kamar = " . $kamar_lama['id_kamar']);
            $conn->query("UPDATE kamar SET status = 'terisi' WHERE id_kamar = " . $id_kamar_baru);
        }
        header('Location: ../views/penyewa.php?success=updated');
    } else {
        header('Location: ../views/penyewa.php?error=updatefailed');
    }
    exit;
}



// Delete penyewa
if (isset($_GET['delete'])) {
    $id_penyewa = intval($_GET['delete']);

    // Retrieve the id_kamar of the penyewa being deleted
    $get_kamar_query = "SELECT id_kamar FROM penyewa WHERE id_penyewa = ?";
    $get_kamar_stmt = $conn->prepare($get_kamar_query);
    $get_kamar_stmt->bind_param('i', $id_penyewa);
    $get_kamar_stmt->execute();
    $get_kamar_result = $get_kamar_stmt->get_result();
    $kamar_data = $get_kamar_result->fetch_assoc();

    if ($kamar_data) {
        $id_kamar = $kamar_data['id_kamar'];

        // Delete the penyewa
        $delete_query = "DELETE FROM penyewa WHERE id_penyewa = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param('i', $id_penyewa);

        if ($delete_stmt->execute()) {
            // Update the kamar status back to 'kosong'
            $update_kamar_query = "UPDATE kamar SET status = 'kosong' WHERE id_kamar = ?";
            $update_stmt = $conn->prepare($update_kamar_query);
            $update_stmt->bind_param('i', $id_kamar);
            $update_stmt->execute();

            header('Location: ../views/penyewa.php?success=deleted');
        } else {
            header('Location: ../views/penyewa.php?error=deletefailed');
        }
    } else {
        header('Location: ../views/penyewa.php?error=kamarnotfound');
    }
    exit;
}

?>
