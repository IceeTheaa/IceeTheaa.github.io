<?php
session_start();
require 'config/db.php';

// Jika sudah login, langsung ke menu
if (isset($_SESSION['login'])) {
    header("Location: menu.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user  = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['role']  = $user['role'];
        $_SESSION['user']  = $user['username'];

        header("Location: menu.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Admin - Indo Ice Tea</title>
<style>
body{
    font-family:Poppins, sans-serif;
    background:#f6f6f6;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.login-box{
    background:#fff;
    padding:30px;
    width:320px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}
input,button{
    width:100%;
    padding:10px;
    margin-top:10px;
}
button{
    background:#2ecc71;
    border:none;
    color:white;
    cursor:pointer;
}
.error{color:red;margin-top:10px;}
</style>
</head>
<body>

<div class="login-box">
    <h2>Login Admin</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
</div>

</body>
</html>
