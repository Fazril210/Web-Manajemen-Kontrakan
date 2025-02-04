    <?php
    session_start();
    require_once '../config/database.php';
    if (!isset($_SESSION['admin'])) {
        header('Location: login.php');
        exit;
    }

    // Ambil data penyewa
    $query_penyewa = "SELECT penyewa.*, kamar.nomor_kamar, kontrakan.nama_kontrakan 
                    FROM penyewa
                    JOIN kamar ON penyewa.id_kamar = kamar.id_kamar
                    JOIN kontrakan ON kamar.id_kontrakan = kontrakan.id_kontrakan";
    $result_penyewa = mysqli_query($conn, $query_penyewa);

    if (!$result_penyewa) {
        die("Error: " . mysqli_error($conn));
    }

    // Ambil data kamar kosong
    $query_kamar = "SELECT kamar.*, kontrakan.nama_kontrakan 
                    FROM kamar 
                    JOIN kontrakan ON kamar.id_kontrakan = kontrakan.id_kontrakan 
                    WHERE kamar.status = 'kosong'";
    $result_kamar = mysqli_query($conn, $query_kamar);

    ?>



    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Penyewa - Manajemen Kontrakan</title>
        <link rel="stylesheet" href="../assets/css/styles_penyewa.css">
        <link rel="stylesheet" href="../assets/js/index_penyewa.js">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>
    <body>
        <?php include 'templates/sidebar.php'; ?>
        
        <div class="container">
            <div class="header">
                <h2>Data Penyewa</h2>
                <p>Kelola informasi penyewa kontrakan Anda dengan mudah dan efisien. Tambah, edit, atau hapus data penyewa sesuai kebutuhan.</p>
            </div>

            <div class="content-grid">
                <!-- Form Section -->
                <div class="form-add-penyewa">
                    <h3>Tambah Penyewa Baru</h3>
                    <form action="../controllers/penyewa.php" method="POST">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
                        </div>
                        
                        <div class="form-group">
                            <label for="no_hp">Nomor Handphone</label>
                            <input type="text" id="no_hp" name="no_hp" required placeholder="Contoh: 08123456789">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required placeholder="Contoh: nama@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" required placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div class="form-group">
        <label for="kamar">Pilih Kamar</label>
        <select id="kamar" name="kamar" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);">
            <option value="">-- Pilih Kamar --</option>
            <?php while ($row_kamar = mysqli_fetch_assoc($result_kamar)): ?>
                <option value="<?= $row_kamar['id_kamar']; ?>">
                    <?= htmlspecialchars($row_kamar['nama_kontrakan']) . " - " . htmlspecialchars($row_kamar['nomor_kamar']); ?>
                    (Rp <?= number_format($row_kamar['harga_sewa'], 0, ',', '.'); ?>)
                </option>
            <?php endwhile; ?>
        </select>
    </div>

                        
                        <button type="submit" name="add_penyewa" class="btn-submit">
                            <i class="fas fa-plus"></i> Tambah Penyewa
                        </button>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="table-section">
                    <h3>Daftar Penyewa</h3>
                    <table>
                    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Kamar</th>
            <th>Kontrakan</th> <!-- Tambahkan kolom Kontrakan -->
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($result_penyewa)): 
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['no_hp']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['alamat']); ?></td>
            <td><?= htmlspecialchars($row['nomor_kamar']); ?></td>
            <td><?= htmlspecialchars($row['nama_kontrakan']); ?></td> <!-- Tampilkan nama kontrakan -->
            <td>
                <div class="action-buttons">
                    <a href="edit_penyewa.php?id=<?= $row['id_penyewa']; ?>" class="edit-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="../controllers/penyewa.php?delete=<?= $row['id_penyewa']; ?>" 
                    class="delete-btn" 
                    onclick="return confirm('Apakah Anda yakin ingin menghapus penyewa ini?')">
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
                <p>Apakah Anda yakin ingin menghapus data penyewa ini?</p>
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
