<?php
session_start();
require_once '../config/database.php';
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil data kontrakan
$query = "SELECT * FROM kontrakan";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kontrakan - Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_kontrakan.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/js/index_kontrakan.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    
    <div class="container">
        <div class="header">
            <h2>Data Kontrakan</h2>
            <p>Kelola informasi kontrakan Anda dengan mudah dan efisien. Tambah, edit, atau hapus data kontrakan sesuai kebutuhan.</p>
        </div>

        <div class="content-grid">
            <!-- Form Section -->
            <div class="form-add-kontrakan">
                <h3>Tambah Kontrakan Baru</h3>
                <form action="../controllers/kontrakan.php" method="POST">
                    <div class="form-group">
                        <label for="nama_kontrakan">Nama Kontrakan</label>
                        <input type="text" id="nama_kontrakan" name="nama_kontrakan" required placeholder="Masukkan nama kontrakan">
                    </div>
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <textarea id="lokasi" name="lokasi" required placeholder="Masukkan lokasi lengkap"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_kamar">Jumlah Kamar</label>
                        <input type="number" id="jumlah_kamar" name="jumlah kamar" required placeholder="Masukkan jumlah kamar">
                    </div>
                    
                    <button type="submit" name="add_kontrakan" class="btn-submit">
                        <i class="fas fa-plus"></i> Tambah Kontrakan
                    </button>
                </form>
            </div>

            <div class="form-add-kamar">
    <h3>Tambah Data Kamar</h3>
    <form action="../controllers/kontrakan.php" method="POST">
        <div class="form-group">
            <label for="id_kontrakan">Kontrakan</label>
            <select id="id_kontrakan" name="id_kontrakan" required>
                <option value="" disabled selected>Pilih Kontrakan</option>
                <?php
                $kontrakanQuery = "SELECT id_kontrakan, nama_kontrakan FROM kontrakan";
                $kontrakanResult = mysqli_query($conn, $kontrakanQuery);
                while ($row = mysqli_fetch_assoc($kontrakanResult)) {
                    echo "<option value='{$row['id_kontrakan']}'>{$row['nama_kontrakan']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="nomor_kamar">Nomor Kamar</label>
            <input type="text" id="nomor_kamar" name="nomor_kamar" required placeholder="Masukkan nomor kamar">
        </div>
        <div class="form-group">
            <label for="harga_sewa">Harga Sewa</label>
            <input type="number" id="harga_sewa" name="harga_sewa" required placeholder="Masukkan harga sewa">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="terisi">Terisi</option>
                <option value="kosong">Kosong</option>
            </select>
        </div>
        <button type="submit" name="add_kamar" class="btn-submit">
            <i class="fas fa-plus"></i> Tambah Kamar
        </button>
    </form>
</div>


            <!-- Table Section -->
            <div class="table-section">
                <h3>Daftar Kontrakan</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kontrakan</th>
                            <th>Lokasi</th>
                            <th>Jumlah Kamar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;  // Menambahkan variabel $no untuk nomor urut
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td> <!-- Menampilkan nomor urut -->
                            <td><?= htmlspecialchars($row['nama_kontrakan']); ?></td>
                            <td><?= htmlspecialchars($row['lokasi']); ?></td>
                            <td><?= htmlspecialchars($row['jumlah_kamar']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_kontrakan.php?id=<?= $row['id_kontrakan']; ?>" class="edit-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="../controllers/kontrakan.php?delete=<?= $row['id_kontrakan']; ?>" 
                                       class="delete-btn" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus kontrakan ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-section">
    <h3>Daftar Kamar</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kontrakan</th>
                <th>Nomor Kamar</th>
                <th>Harga Sewa</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $kamarQuery = "
                SELECT k.id_kamar, kt.nama_kontrakan, k.nomor_kamar, k.harga_sewa, k.status 
                FROM kamar k
                JOIN kontrakan kt ON k.id_kontrakan = kt.id_kontrakan
            ";
            $kamarResult = mysqli_query($conn, $kamarQuery);
            $no = 1;
            while ($row = mysqli_fetch_assoc($kamarResult)): 
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_kontrakan']); ?></td>
                <td><?= htmlspecialchars($row['nomor_kamar']); ?></td>
                <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.'); ?></td>
                <td>
                    <span class="status-badge status-<?= strtolower($row['status']); ?>">
                        <?= ucfirst($row['status']); ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <!-- Edit Button -->
                        <a href="edit_kamar.php?id=<?= $row['id_kamar']; ?>" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <!-- Delete Button -->
                        <a href="../controllers/kamar.php?delete=<?= $row['id_kamar']; ?>" 
                           class="delete-btn" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data kamar ini?')">
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
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-exclamation-triangle warning-icon"></i>
            <h3>Konfirmasi Penghapusan</h3>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus data kontrakan ini?</p>
            <p class="modal-subtitle">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer">
            <button id="cancelDelete" class="btn-cancel">
                <i class="fas fa-times"></i> Batal
            </button>
            <button id="confirmDelete" class="btn-delete">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
    </div>
</div>
<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    animation: fadeIn 0.3s ease;
}

.modal-content {
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
    animation: slideIn 0.3s ease;
}

.modal-header {
    text-align: center;
    margin-bottom: 20px;
}

.warning-icon {
    color: #ff4444;
    font-size: 48px;
    margin-bottom: 15px;
}

.modal-body {
    text-align: center;
    margin-bottom: 20px;
}

.modal-subtitle {
    color: #666;
    font-size: 0.9em;
    margin-top: 8px;
}

.modal-footer {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-cancel, .btn-delete {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s ease;
}

.btn-cancel {
    background-color: #e0e0e0;
    color: #333;
}

.btn-cancel:hover {
    background-color: #d0d0d0;
}

.btn-delete {
    background-color: #ff4444;
    color: white;
}

.btn-delete:hover {
    background-color: #ff1111;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        transform: translate(-50%, -60%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, -50%);
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    let deleteUrl = '';

    // Update delete buttons to trigger modal
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.onclick = function(e) {
            e.preventDefault();
            deleteUrl = this.href;
            modal.style.display = 'block';
        };
    });

    // Handle cancel button
    document.getElementById('cancelDelete').onclick = function() {
        modal.style.display = 'none';
    };

    // Handle confirm button
    document.getElementById('confirmDelete').onclick = function() {
        window.location.href = deleteUrl;
    };

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});
</script>
</html>
