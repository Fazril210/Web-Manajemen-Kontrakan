<?php
    session_start();
    require_once '../config/database.php';
    if (!isset($_SESSION['admin'])) {
        header('Location: login.php');
        exit;
    }

    // Ambil data admin dari database
$result_admin = mysqli_query($conn, "SELECT * FROM admin");


    ?>



    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Admin - Manajemen Kontrakan</title>
        <link rel="stylesheet" href="../assets/css/styles_admin.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>
    <body>
        <?php include 'templates/sidebar.php'; ?>
        
        <div class="container">
            <div class="header">
                <h2>Data Admin</h2>
                <p>Kelola informasi admin kontrakan Anda dengan mudah dan efisien. Tambah, edit, atau hapus data admin sesuai kebutuhan.</p>
            </div>

            <div class="content-grid">
                <!-- Form Section -->
                <div class="form-add-admin">
                    <h3>Tambah Admin Baru</h3>
                    <form action="../controllers/admin.php" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required placeholder="Masukkan username">
                        </div>
                        
                        <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password">
                </div>
                        
                <div class="form-group">
                    <label for="nama_admin">Nama Admin</label>
                    <input type="text" id="nama_admin" name="nama_admin" required placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan email">
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor Telepon</label>
                    <input type="text" id="no_hp" name="no_hp" required placeholder="Masukkan nomor telepon">
                </div>

                <div class="form-group" >
                    <label for="role">Role</label>
                    <select id="role" name="role" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);">
                        <option value="">-- Pilih Role --</option>    
                        <option value="admin">Admin</option>
                        <option value="super admin">Super Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Daftar</button>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="table-section">
                    <h3>Daftar Admin</h3>
                    <table>
                    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama Admin</th>
            <th>Email</th>
            <th>Nomor Handphone</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($result_admin)): 
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= htmlspecialchars($row['nama_admin']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['no_hp']); ?></td>
            <td><?= htmlspecialchars($row['role']); ?></td>
            <td>
                <div class="action-buttons">
                    <a href="edit_admin.php?id=<?= $row['id_admin']; ?>" class="edit-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="../controllers/admin.php?delete=<?= $row['id_admin']; ?>" 
                    class="delete-btn" 
                    onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
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
                <p>Apakah Anda yakin ingin menghapus data admin ini?</p>
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
