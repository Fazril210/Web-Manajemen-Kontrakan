<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2196F3;
            --accent-color: #1976D2;
            --background-color: #f5f7fa;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .container {
            margin-left: 280px;
            padding: 40px;
            max-width: 800px;
            animation: fadeIn 0.8s ease-in-out;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        h3 {
            color: var(--primary-color);
            font-size: 28px;
            margin: 0 0 20px;
            position: relative;
            padding-bottom: 10px;
        }

        h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 250px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }

        p {
            color: #666;
            line-height: 1.6;
            font-size: 16px;
            margin-bottom: 30px;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 25px;
            animation: slideIn 0.5s ease-out;
        }

        label {
            display: block;
            color: #444;
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease;
            background: white;
        }

        input[type="date"]:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        button {
            background: var(--primary-color);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33,150,243,0.3);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        footer {
            margin-top: 40px;
            color: #777;
            text-align: center;
            font-size: 14px;
            animation: fadeIn 1s ease-in-out;
        }
    </style>
</head>
<body>
    <?php include 'templates/sidebar.php'; ?>
    <div class="container">
        <div class="card">
            <h3 class="animate__animated animate__fadeInDown">Laporan Transaksi</h3>
            <p class="animate__animated animate__fadeIn">Cetak laporan transaksi berdasarkan rentang tanggal yang dipilih.</p>

            <form action="../controllers/laporan.php" method="POST" target="_blank">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai:</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai:</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" required>
                </div>
                
                <button type="submit" class="animate__animated animate__fadeIn">Cetak Laporan</button>
            </form>
        </div>
    </div>
    <?php include 'templates/footer.php'; ?>
</body>
</html>