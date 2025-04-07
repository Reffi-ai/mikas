<?php
require 'functions.php'; // Pastikan file ini sudah ada koneksi $pdo
session_start();

// Pastikan PDO sudah ada
if (!isset($pdo)) {
    die('Koneksi database gagal.');
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'] ?? null;

// Jika user belum login, arahkan ke login page
if ($user_id === null) {
    header('Location: login.php');
    exit;
}

// Fungsi untuk menghitung total berdasarkan tipe
function hitungTotalTransaksi(PDO $pdo, int $user_id, string $tipe): int {
    $stmt = $pdo->prepare("SELECT SUM(jumlah) AS total FROM transaksi WHERE tipe = :tipe AND user_id = :user_id");
    $stmt->execute([':tipe' => $tipe, ':user_id' => $user_id]);
    return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
}

// Fungsi untuk menghitung saldo akhir
function hitungSaldoAkhir(int $totalPemasukan, int $totalPengeluaran): int {
    return $totalPemasukan - $totalPengeluaran;
}

// Hitung data
$total_pemasukan = hitungTotalTransaksi($pdo, $user_id, 'pemasukan');
$total_pengeluaran = hitungTotalTransaksi($pdo, $user_id, 'pengeluaran');
$saldo_akhir = hitungSaldoAkhir($total_pemasukan, $total_pengeluaran);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Keuangan</title>
    <link rel="stylesheet" href="dasboard.css"> <!-- Sudah diperbaiki nama file -->
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
                    <a href="add_transaction.html" class="btn orange">Tambah Transaksi</a>
                    <a href="laporan_keuangan.php" class="btn green">Laporan Keuangan</a>
                    <a href="pengaturan.php" class="btn dark">Pengaturan Akun</a>
                </div>
            </section>

            <section class="ringkasan-keuangan">
                <h2>Ringkasan Keuangan</h2>
                <p>Tinjau pemasukan, pengeluaran, dan saldo dalam satu tampilan.</p>

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

    <script src="dasboard.js"></script> <!-- Sudah diperbaiki nama file -->
</body>
</html>
