<?php
require 'functions.php';
session_start();

// Fungsi murni untuk menghapus transaksi berdasarkan array ID
function hapusTransaksi(PDO $pdo, array $ids): bool {
    if (empty($ids)) {
        return false;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $query = "DELETE FROM transaksi WHERE id IN ($placeholders)";
    $stmt = $pdo->prepare($query);

    return $stmt->execute($ids);
}

// Fungsi murni untuk menentukan pesan berdasarkan hasil
function hasilHapus(bool $berhasil): string {
    return $berhasil ? "Data berhasil dihapus." : "Terjadi kesalahan saat menghapus data.";
}

// Fungsi murni untuk redirect
function redirectKe(string $location): void {
    header("Location: $location");
    exit;
}

// Main Execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['ids'] ?? [];

    if (!empty($ids)) {
        $berhasil = hapusTransaksi($pdo, $ids);
        $_SESSION['success_message'] = hasilHapus($berhasil);
    } else {
        $_SESSION['error_message'] = "Tidak ada data yang dipilih.";
    }
}

redirectKe('laporan_keuangan.php');
?>
