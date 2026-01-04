<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $result = $stmt->fetch();

    // Verifikasi: Hanya login jika username ditemukan dan password cocok
    if ($result && password_verify($pass, $result['password'])) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['role'] = 'admin'; // Menandai bahwa ini adalah admin
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Akses Ditolak: Kredensial Admin Salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Indo Ice Tea</title>
    <style>
        body {
            margin: 0; font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=1500');
            background-size: cover; display: flex; justify-content: center; align-items: center; min-height: 100vh;
        }
        .card {
            background: white; border-radius: 15px; overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4); width: 100%; max-width: 380px;
        }
        .brand-header {
            background: #e65500; color: white; padding: 25px;
            text-align: center; font-size: 22px; font-weight: bold; letter-spacing: 1px;
        }
        .container { padding: 40px 30px; text-align: center; }
        label { display: block; text-align: left; margin-bottom: 5px; font-weight: bold; color: #555; }
        input {
            width: 100%; padding: 12px; margin-bottom: 20px;
            border: 2px solid #ddd; border-radius: 8px; box-sizing: border-box;
        }
        input:focus { border-color: #ff8c2d; outline: none; }
        button {
            width: 100%; padding: 14px; background: #ff5e00; color: white;
            border: none; border-radius: 25px; font-size: 16px; font-weight: bold;
            cursor: pointer; transition: 0.3s;
        }
        button:hover { background: #333; transform: scale(1.02); }
        .error { color: #fff; background: #c0392b; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
        .footer-note { margin-top: 25px; font-size: 12px; color: #aaa; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand-header">🔐 ADMIN PANEL</div>
        <div class="container">
            <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="POST">
                <label>Username Admin</label>
                <input type="text" name="username" placeholder="Masukkan Username" required>
                
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password" required>
                
                <button type="submit">LOGIN ADMIN</button>
            </form>
            <div class="footer-note">Indo Ice Tea Management System</div>
        </div>
    </div>
</body>
</html>