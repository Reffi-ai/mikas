<?php
session_start();
require 'functions.php';

// Pure function untuk mendapatkan user_id, tanpa die()
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Pure function untuk mengambil dan menghapus notifikasi
function fetchNotification(string $key): ?string {
    if (!isset($_SESSION[$key])) {
        return null;
    }
    $message = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $message;
}

// Pure function untuk membuat alert HTML
function renderAlert(string $type, string $strongText, string $message): string {
    return <<<HTML
    <div id="{$type}-alert" class="alert alert-{$type} alert-dismissible position-fixed top-0 end-0 m-3 shadow" role="alert">
        <strong>{$strongText}</strong> {$message}
        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';" aria-label="Close"></button>
    </div>
    HTML;
}

// Pure function untuk membuat baris transaksi
function renderTransactionRow(array $item): string {
    $formattedJumlah = number_format($item['jumlah'], 0, ',', '.');
    $formattedTipe = ucfirst($item['tipe']);
    return <<<HTML
    <tr>
        <td><input type="checkbox" name="ids[]" value="{$item['id']}"></td>
        <td>{$item['tanggal']}</td>
        <td>{$formattedTipe}</td>
        <td>Rp{$formattedJumlah}</td>
        <td>{$item['deskripsi']}</td>
    </tr>
    HTML;
}

// Fungsi untuk memproses seluruh transaksi jadi HTML
function renderTransactionRows(array $transactions): string {
    return implode('', array_map('renderTransactionRow', $transactions));
}

// --- Program Utama ---

$user_id = getUserId();
if (is_null($user_id)) {
    header('Location: login.php');
    exit();
}

$totalPemasukan = totalPemasukan($pdo, $user_id);
$totalPengeluaran = totalPengeluaran($pdo, $user_id);
$saldoAkhir = saldoAkhir($pdo, $user_id);
$transaksi = getTransaksi($pdo, $user_id);

$successMessage = fetchNotification('success_message');
$errorMessage = fetchNotification('error_message');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <link rel="stylesheet" href="laporan_keuangan.css">
</head>
<body class="bg-light">
    <div class="container">
        <a href="dasboard.php" class="btn kembali kuning">&#8617; Kembali</a>
        <h2 class="text-center">Laporan Keuangan</h2>
        <!-- Notifikasi -->
        <?= $successMessage ? renderAlert('success', 'Berhasil', $successMessage) : '' ?>
        <?= $errorMessage ? renderAlert('danger', 'Gagal', $errorMessage) : '' ?>
        <form method="post" action="delete_multiple.php">
            <div class="button-group">
                <button type="submit" class="btn delete merah" onclick="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')">
                    🗑️ Hapus Data Terpilih
                </button>
                <a href="download_pdf.php" class="btn Download-PDF biru">📄 Download PDF</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="checkbox-column"><input type="checkbox" id="select-all" onclick="toggleCheckboxes(this)"></th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transaksi)) : ?>
                            <?= renderTransactionRows($transaksi) ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Tidak ada data transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="summary">
            <div class="card hijau">
                <h3>Total Pemasukan</h3>
                <p>Rp<?= number_format($totalPemasukan, 0, ',', '.') ?></p>
            </div>
            <div class="card merah">
                <h3>Total Pengeluaran</h3>
                <p>Rp<?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
            </div>
            <div class="card biru">
                <h3>Saldo Akhir</h3>
                <p>Rp<?= number_format($saldoAkhir, 0, ',', '.') ?></p>
            </div>
        </div>
        <a href="add_transaction.html" class="tambah-transaksi kuning">&#128722; Tambah Transaksi</a>
    </div>
    <script src="laporan_keuangan.js"></script>
</body>
</html>