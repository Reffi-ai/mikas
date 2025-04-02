<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.html"); // Redirect jika belum login
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi pengguna</title>
    <link rel="stylesheet" href="pengaturan.css">
</head>
<body>
    <div class="container">
        <button class="btn back" onclick="goBack()">&#8617; kembali</button>
        <h1 class="informasi-pengguna">Informasi pengguna</h1>
        
        <div class="profile-info">
            <p><strong>Nama Lengkap:</strong> 
                <input type="text" id="fullName" value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>" readonly>
            </p>
            <p><strong>Nama Warmindo:</strong> 
                <input type="text" id="warmindoName" value="<?php echo htmlspecialchars($_SESSION['warmindo_name']); ?>" readonly>
            </p>
            <p><strong>Email:</strong> 
                <input type="email" id="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
            </p>
        </div>
        
        <button class="btn exit">Keluar</button>
    </div>
    <script src="pengaturan.js"></script>
</body>
</html>