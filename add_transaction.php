<?php
require 'functions.php';

function handleRequest($pdo, $postData, $sessionData)
{
    if ($postData["tipe"] && $postData["jumlah"] && $postData["deskripsi"] && isset($sessionData['user_id'])) {
        $userId = $sessionData['user_id'];
        $tipe = $postData['tipe'];
        $jumlah = $postData['jumlah'];
        $deskripsi = $postData['deskripsi'];

        $result = catatTransaksi($pdo, $userId, $tipe, $jumlah, $deskripsi);

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

// --- Eksekusi kode procedural minimal ---
session_start();
$response = handleRequest($pdo, $_POST, $_SESSION);

if ($response['redirect']) {
    $_SESSION['success_message'] = $response['success_message'];
    header("Location: " . $response['redirect']);
    exit;
} else {
    echo $response['error_message'];
}
?>
