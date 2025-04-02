<?php
require 'functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Buat query untuk menghapus data berdasarkan ID
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("DELETE FROM transaksi WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    $stmt = $pdo->prepare("DELETE FROM transaksi WHERE id IN ($placeholders)");
    if ($stmt->execute($ids)) {
        // Set pesan sukses
        $_SESSION['success_message'] = "Data berhasil dihapus.";
    } else {
        // Set pesan gagal
        $_SESSION['error_message'] = "Terjadi kesalahan saat menghapus data.";
    }
} else {
    $_SESSION['error_message'] = "Tidak ada data yang dipilih.";
}


// Redirect kembali ke index.php
header('Location: laporan_keuangan.php');
exit;
?>