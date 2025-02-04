    <!DOCTYPE html>
<html>
<head>
    <title>Manajemen Kontrakan</title>
    <link rel="stylesheet" href="../assets/css/styles_sidebar.css">
    <link rel="stylesheet" href="../assets/css/styles_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

        /* Enhanced Popup Styles */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .popup.visible {
            opacity: 1;
            visibility: visible;
        }

        .popup-content {
            background: linear-gradient(135deg, #ffffff, #f4f6f9);
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: popupShow 0.4s ease-out;
            max-width: 400px;
            width: 100%;
        }

        .popup-content h3 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #1a237e;
            font-weight: bold;
        }

        .popup-content p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
            line-height: 1.5;
        }

        .popup-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-confirm, .btn-cancel {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-confirm {
            background-color: #4caf50;
            color: white;
        }

        .btn-confirm:hover {
            background-color: #43a047;
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(76, 175, 80, 0.3);
        }

        .btn-cancel {
            background-color: #f44336;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #e53935;
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(244, 67, 54, 0.3);
        }

        /* Button Icon Styling */
        .btn-confirm i, .btn-cancel i {
            font-size: 18px;
        }

        /* Popup Animation */
        @keyframes popupShow {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar Toggle Button -->
        <button id="toggleSidebar" class="sidebar-toggle-btn">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <div class="sidebar" id="mainSidebar">
            <h2><i class="fas fa-home"></i> <span>Manajemen Kontrakan</span></h2>

            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="penyewa.php"><i class="fas fa-users"></i> <span>Penyewa</span></a></li>
                <li><a href="kontrakan.php"><i class="fas fa-building"></i> <span>Kontrakan</span></a></li>
                <li><a href="transaksi.php"><i class="fas fa-money-check-alt"></i> <span>Transaksi</span></a></li>
                <li><a href="laporan.php"><i class="fas fa-file-alt"></i> <span>Laporan</span></a></li>
                <li><a href="register.php"><i class="fas fa-user-cog"></i> <span>Admin</span></a></li>
                <li>
                    <a href="../controllers/auth.php?logout=true" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
                </li>
            </ul>
        </div>

        <!-- Logout Confirmation Popup -->
        <div id="logoutPopup" class="popup">
            <div class="popup-content">
                <h3>Konfirmasi Logout</h3>
                <p>Apakah Anda yakin untuk keluar dari aplikasi?</p>
                <div class="popup-actions">
                    <button id="confirmLogout" class="btn-confirm">
                        <i class="fas fa-check"></i> Ya, Logout
                    </button>
                    <button id="cancelLogout" class="btn-cancel">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Wait for the DOM to be fully loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Select the elements
                const toggleSidebarBtn = document.getElementById('toggleSidebar');
                const sidebar = document.getElementById('mainSidebar');
                const wrapper = document.querySelector('.wrapper');

                // Function to toggle sidebar
                function toggleSidebar() {
                    if (sidebar && wrapper) {
                        sidebar.classList.toggle('sidebar-hidden');
                        wrapper.classList.toggle('sidebar-collapsed');
                        
                        // Save sidebar state in localStorage
                        const isSidebarHidden = sidebar.classList.contains('sidebar-hidden');
                        localStorage.setItem('sidebarHidden', isSidebarHidden);
                    }
                }

                // Restore sidebar state from localStorage
                function restoreSidebarState() {
                    if (sidebar && wrapper) {
                        const isSidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
                        if (isSidebarHidden) {
                            sidebar.classList.add('sidebar-hidden');
                            wrapper.classList.add('sidebar-collapsed');
                        }
                    }
                }

                // Add click event listener to toggle button
                if (toggleSidebarBtn) {
                    toggleSidebarBtn.addEventListener('click', toggleSidebar);
                }

                // Restore sidebar state when page loads
                restoreSidebarState();
            });

            // Select elements
            const logoutBtn = document.getElementById('logoutBtn');
            const logoutPopup = document.getElementById('logoutPopup');
            const confirmLogout = document.getElementById('confirmLogout');
            const cancelLogout = document.getElementById('cancelLogout');

            // Show popup on logout button click
            logoutBtn.addEventListener('click', function (event) {
                event.preventDefault();
                logoutPopup.classList.add('visible');
            });

            // Close popup when cancel button is clicked
            cancelLogout.addEventListener('click', function () {
                logoutPopup.classList.remove('visible');
            });

            // Redirect to logout URL when confirm button is clicked
            confirmLogout.addEventListener('click', function () {
                window.location.href = '../controllers/auth.php?logout=true';
            });
        </script>
