<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Indo Ice Tea</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card { background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; text-align: center; overflow: hidden; }
        .brand-header { background: #ff8c2d; color: white; padding: 20px; font-size: 20px; font-weight: bold; }
        .container { padding: 40px; }
        h1 { color: #ff5e00; margin: 10px 0; }
        .btn-logout { display: inline-block; margin-top: 20px; padding: 10px 25px; background: #333; color: white; text-decoration: none; border-radius: 25px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand-header">🧋 Indo Ice Tea - Member Area</div>
        <div class="container">
            <p>Selamat Datang,</p>
            <h1><?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Nikmati kesegaran es teh terbaik hanya di toko kami.</p>
            <a href="logout.php" class="btn-logout">LOGOUT</a>
        </div>
    </div>
</body>
</html>