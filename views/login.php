<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            overflow: hidden;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 440px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        h2 {
            color: #1e3a8a;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-subtext {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.7rem;
            color: #334155;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group input {
            width: 100%;
            padding: 1.1rem;
            padding-left: 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: white;
            color: #1e293b;
        }

        .input-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .input-group .input-icon {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus + .input-icon {
            color: #3b82f6;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            color: #64748b;
        }

        button {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        button:active {
            transform: translateY(0);
        }

        /* Popup styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: popupIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .popup.active {
            display: block;
        }

        .login-container.hidden {
            display: none;
            animation: fadeOut 0.3s ease-out;
        }

        .popup .popup-message {
            color: #dc2626;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            line-height: 1.5;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .popup .popup-message i {
            font-size: 1.4rem;
        }

        @keyframes popupIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            100% {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 9998;
        }

        .overlay.active {
            display: block;
        }

        /* Loading animation for button */
        button .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        button.loading .text {
            visibility: hidden;
        }

        button.loading .loading {
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
                margin: 1rem;
            }

            h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay"></div>

    <div class="login-container" id="loginForm">
        <div class="login-header">
            <h2>Masuk</h2>
            <p class="login-subtext">Silakan masukkan kredensial Anda untuk melanjutkan</p>
        </div>
        <form action="../controllers/auth.php" method="POST" id="loginFormElement">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-group">
                    <input type="text" name="username" id="username" required placeholder="Masukkan username Anda">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" required placeholder="Masukkan password Anda">
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>
            <button type="submit" name="login">
                <span class="text">Masuk
                </span>
                <span class="loading"><i class="fas fa-spinner fa-spin"></i></span>
            </button>
        </form>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="popup" id="popup">
        <div class="popup-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error']; ?>
        </div>
        <button onclick="closePopup()">Try Again</button>
    </div>
    <script>
        // Show popup and hide login form immediately when error exists
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('popup');
            const loginForm = document.getElementById('loginForm');
            const overlay = document.getElementById('overlay');
            
            popup.classList.add('active');
            loginForm.classList.add('hidden');
            overlay.classList.add('active');
        });
    </script>
 <?php 
    // Clear the error message after displaying it
    unset($_SESSION['error']); 
    endif; 
    ?>

    <script>
        // Function to close the popup and show login form
        function closePopup() {
            const popup = document.getElementById('popup');
            const loginForm = document.getElementById('loginForm');
            const overlay = document.getElementById('overlay');
            
            // Hide popup and overlay
            popup.classList.remove('active');
            overlay.classList.remove('active');
            
            // Show login form
            loginForm.classList.remove('hidden');
            
            // Clear any input values if needed
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            
            // Focus on username field
            document.getElementById('username').focus();
        }

        // Function to toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Add loading animation to button on form submit
        document.getElementById('loginFormElement').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.classList.add('loading');
        });
    </script>
</body>
</html>