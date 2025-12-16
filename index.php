<!DOCTYPE html>
<html lang="id">
<head>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-SVRPPK2LMQ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-SVRPPK2LMQ');
</script>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Indo Ice Tea</title>

<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="image.png">

</head>
<body>

<header>
    <div class="logo">🍹 Indo Ice Tea</div> 
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="contact.html">Kontak</a></li>
        </ul>
    </nav>
</header>

<section class="hero">
    <h1>Selamat Datang di Ice Tea.</h1>
    <h1>Estehnya Indonesia</h1>
    <p>Pilihan minuman segar favorit Anda, diracik tanpa pemanis buatan!</p>
    <a href="menu.php" class="btn">Jelajahi Menu</a>
</section>

<footer>
    <span id="secretLoginTrigger" style="cursor:pointer;">
        2024 Indo Ice Tea. Semua Hak Dilindungi.
    </span>
</footer>

<script>
const trigger = document.getElementById('secretLoginTrigger');

// double click ke halaman login
trigger.addEventListener('dblclick', function () {
    window.location.href = 'login.php';
});
</script>

</body>
</html>
