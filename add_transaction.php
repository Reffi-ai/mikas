<?php
require 'functions.php';

// Fungsi ini menangani request yang dikirim dari form (melalui method POST) dan juga data dari sesi login.
function handleRequest($pdo, $postData, $sessionData)
{
    if ($postData["tipe"] && $postData["jumlah"] && $postData["deskripsi"] && isset($sessionData['user_id'])) { // Pemeriksaan data
        $userId = $sessionData['user_id']; // Mengambil user_id dari session
        $tipe = $postData['tipe']; // Mengambil data dari post
        $jumlah = $postData['jumlah'];
        $deskripsi = $postData['deskripsi'];

        $result = catatTransaksi($pdo, $userId, $tipe, $jumlah, $deskripsi); // Memanggil fungsi catatTransaksi() untuk menyimpan transaksi ke database

        return [
            'redirect' => 'laporan_keuangan.php',
            'success_message' => 'Transaksi berhasil ditambahkan!',
            'error_message' => null,
        ];
    }

    return [
        'redirect' => null,
        'success_message' => null,
        'error_message' => 'Data tidak lengkap atau user belum login',
    ];
}


session_start(); // memulai session
$response = handleRequest($pdo, $_POST, $_SESSION); // Memanggil fungsi handleRequest() dengan parameter yang sesuai

// Simpan pesan sukses ke session
if ($response['redirect']) {
    $_SESSION['success_message'] = $response['success_message'];
    header("Location: " . $response['redirect']); // Redirect ke halaman laporan_keuangan.php
    exit;
} else {
    echo $response['error_message'];
}
?>
