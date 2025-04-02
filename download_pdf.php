<?php
require 'functions.php';
require 'vendor/autoload.php'; // Pastikan menggunakan library seperti Dompdf

use Dompdf\Dompdf;

// Mulai sesi
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Error: Anda harus login terlebih dahulu.");
}

$user_id = $_SESSION['user_id'];

// Ambil data transaksi berdasarkan user_id
$transaksi = getTransaksi($pdo, $user_id);

// Hitung total pemasukan, pengeluaran, dan saldo akhir
$totalPemasukan = totalPemasukan($pdo, $user_id);
$totalPengeluaran = totalPengeluaran($pdo, $user_id);
$saldoAkhir = saldoAkhir($pdo, $user_id);

// Muat file CSS eksternal
$css = file_get_contents('download_pdf.css');

// Buat konten HTML untuk PDF
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        ' . $css . '
    </style>
</head>
<body>
    <h2>Laporan Keuangan</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>';

if (!empty($transaksi)) {
    foreach ($transaksi as $item) {
        $html .= '
            <tr>
                <td>' . htmlspecialchars($item['tanggal']) . '</td>
                <td>' . ucfirst(htmlspecialchars($item['tipe'])) . '</td>
                <td>Rp' . number_format($item['jumlah'], 0, ',', '.') . '</td>
                <td>' . htmlspecialchars($item['deskripsi']) . '</td>
            </tr>';
    }
} else {
    $html .= '
            <tr>
                <td colspan="4" style="text-align: center;">Tidak ada data transaksi.</td>
            </tr>';
}
$html .= '
        </tbody>
    </table>
    <div class="summary">
        <div class="summary pemasukan"><strong>Total Pemasukan:</strong> Rp' . number_format($totalPemasukan, 0, ',', '.') . '</div>
        <div class="summary pengeluaran"><strong>Total Pengeluaran:</strong> Rp' . number_format($totalPengeluaran, 0, ',', '.') . '</div>
        <div class="summary total"><strong>Saldo Akhir:</strong> Rp' . number_format($saldoAkhir, 0, ',', '.') . '</div>
    </div>
</body>
</html>';

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Kirim file PDF ke browser
$dompdf->stream("laporan_keuangan.pdf", ["Attachment" => true]);
?>