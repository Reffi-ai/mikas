<?php
session_start();

// Mengecek apakah ada email yang tersimpan di $_SESSION, yang mengindikasikan bahwa user sudah login.
function isUserLoggedIn(): bool {
    return isset($_SESSION['email']);
}

// Jika user belum login, akan langsung diarahkan ke halaman login menggunakan header() lalu exit() untuk menghentikan eksekusi lebih lanjut.
function redirectIfNotLoggedIn(string $redirectUrl): void {
    if (!isUserLoggedIn()) {
        header("Location: $redirectUrl");
        exit();
    }
}

// Mengambil data dari $_SESSION dengan keamanan tambahan melalui htmlspecialchars agar aman ditampilkan di HTML (mencegah XSS).
function getSessionData(string $key): string {
    return htmlspecialchars($_SESSION[$key] ?? '');
}

// untuk memastikan hanya user yang sudah login bisa mengakses halaman ini.
redirectIfNotLoggedIn('login.html');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pengguna</title>
    <link rel="stylesheet" href="pengaturan.css">
</head>
<body>
    <div class="container">
        <button class="btn back" onclick="goBack()">&#8617; kembali</button>
        <h1 class="informasi-pengguna">Informasi Pengguna</h1>
        
        <div class="profile-info">
            <p><strong>Nama Lengkap:</strong> 
                <input type="text" id="fullName" value="<?= getSessionData('full_name'); ?>" readonly>
            </p>
            <p><strong>Nama Warmindo:</strong> 
                <input type="text" id="warmindoName" value="<?= getSessionData('warmindo_name'); ?>" readonly>
            </p>
            <p><strong>Email:</strong> 
                <input type="email" id="email" value="<?= getSessionData('email'); ?>" readonly>
            </p>
        </div>
        
        <button class="btn exit">Keluar</button>
    </div>
    <script src="pengaturan.js"></script>
</body>
</html>
