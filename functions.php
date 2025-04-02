<?php
require 'config.php';
function catatTransaksi($pdo, $user_id, $tipe, $jumlah, $deskripsi) {
    try {
        $stmt = $pdo->prepare("INSERT INTO transaksi (user_id, tipe, jumlah, deskripsi, tanggal) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $tipe, $jumlah, $deskripsi]);
        echo "Data berhasil disimpan!";
        return "Transaksi berhasil ditambahkan!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return "Error: " . $e->getMessage();
    }
}

function getTransaksi($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM transaksi WHERE user_id = ? ORDER BY tanggal DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function totalPemasukan($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT SUM(jumlah) AS total FROM transaksi WHERE tipe = 'pemasukan' AND user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function totalPengeluaran($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT SUM(jumlah) AS total FROM transaksi WHERE tipe = 'pengeluaran' AND user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function saldoAkhir($pdo, $user_id) {
    return totalPemasukan($pdo, $user_id) - totalPengeluaran($pdo, $user_id);
}
?>
