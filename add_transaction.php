<?php
require 'functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Ambil user_id dari sesi
    $tipe = $_POST['tipe'];
    $jumlah = $_POST['jumlah'];
    $deskripsi = $_POST['deskripsi'];
    
    // Panggil fungsi catatTransaksi dengan user_id
    $hasil = catatTransaksi($pdo, $user_id, $tipe, $jumlah, $deskripsi);

    // Simpan pesan sukses ke dalam session
    $_SESSION['success_message'] = "Transaksi berhasil ditambahkan!";
    
    header("Location: laporan_keuangan.php");
    exit;
}
?>