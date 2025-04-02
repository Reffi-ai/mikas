<?php
require 'functions.php'; // Pastikan file ini berisi koneksi ke database
session_start();
$user_id = $_SESSION['user_id']; // Ambil user_id dari sesi

// Hitung total pemasukan berdasarkan user_id
$stmt = $pdo->prepare("SELECT SUM(jumlah) AS total_pemasukan FROM transaksi WHERE tipe = 'pemasukan' AND user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$total_pemasukan = $stmt->fetch(PDO::FETCH_ASSOC)['total_pemasukan'] ?? 0;

// Hitung total pengeluaran berdasarkan user_id
$stmt = $pdo->prepare("SELECT SUM(jumlah) AS total_pengeluaran FROM transaksi WHERE tipe = 'pengeluaran' AND user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$total_pengeluaran = $stmt->fetch(PDO::FETCH_ASSOC)['total_pengeluaran'] ?? 0;

// Hitung saldo akhir
$saldo_akhir = $total_pemasukan - $total_pengeluaran;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Keuangan</title>
    <link rel="stylesheet" href="dasboard.css">
</head>
<body>
    <div class="dashboard">
        <main class="content">
            <header>
                <h1><span class="highlight">MIKAS</span><span class="miee"> - Mie Kenyal Keuangan Digital</span></h1>
            </header>
            <section class="menu-aplikasi">
                <h2>Menu Aplikasi</h2>
                <p>Kelola transaksi dan atur keuangan bisnis Anda</p>
                <div class="menu-buttons">
                    <!-- <button onclick="location.href='add_transaction.html'" class="btn orange">Tambah Transaksi</button>
                    <button onclick="location.href='index.php'" class="btn green">Laporan Keuangan</button>
                    <button onclick="location.href='pengaturan.php'" class="btn dark">Pengaturan Akun</button> -->
                    <a href="add_transaction.html" class="btn orange">Tambah Transaksi</a>
                    <a href="laporan_keuangan.php" class="btn green">Laporan Keuangan</a>
                    <a href="pengaturan.php" class="btn dark">Pengaturan Akun</a>
                </div>
            </section>
            <section class="ringkasan-keuangan">
                <h2>Ringkasan Keuangan</h2>
                <p>Tinjau pemasukan, pengeluaran, dan saldo dalam satu tampilan.</p>
                <!-- Menu Total Pemasukan, Pengeluaran, dan Saldo -->
                <div class="summary">
                    <div class="card green">
                        <h3>Total Pemasukan</h3>
                        <p>Rp<?= number_format($total_pemasukan, 0, ',', '.') ?></p>
                    </div>
                    <div class="card red">
                        <h3>Total Pengeluaran</h3>
                        <p>Rp<?= number_format($total_pengeluaran, 0, ',', '.') ?></p>
                    </div>
                    <div class="card blue">
                        <h3>Saldo Akhir</h3>
                        <p>Rp<?= number_format($saldo_akhir, 0, ',', '.') ?></p>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="dasboard.js"></script>
</body>
</html>