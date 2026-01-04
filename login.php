<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $user_input = mysqli_real_escape_string($conn, $_POST['username']);
    $pass_input = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username='$user_input'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($pass_input === $row['password']) {
            $_SESSION['admin'] = $row['username'];
            header("Location: menu.php");
            exit;
        } else { $error = "Password salah!"; }
    } else { $error = "Username tidak ditemukan!"; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            width: 90%;
            max-width: 380px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 8px solid #ff7e5f; /* Warna Orange Coral Website */
        }
        h2 { color: #333; margin-bottom: 25px; font-weight: 600; }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; border: 1px solid #fecaca; }
        
        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; font-size: 14px; margin-bottom: 8px; color: #555; font-weight: 600; }
        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        input:focus { outline: none; border-color: #ff7e5f; box-shadow: 0 0 5px rgba(255,126,95,0.2); }
        
        button {
            width: 100%;
            padding: 14px;
            background: #333; /* Hitam Navbar */
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #ff7e5f; /* Berubah jadi Orange saat hover */
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🍹 Admin Login</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username admin" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login">Masuk Sekarang</button>
        </form>
    </div>
</body>
</html>