<?php
require 'config.php';

// Fungsi untuk mencatat transaksi baru
function catatTransaksi($pdo, $user_id, $tipe, $jumlah, $deskripsi) {
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO transaksi (user_id, tipe, jumlah, deskripsi, tanggal) 
            VALUES (:user_id, :tipe, :jumlah, :deskripsi, NOW())"
        );
        $stmt->execute([
            ':user_id'   => $user_id,
            ':tipe'      => $tipe,
            ':jumlah'    => $jumlah,
            ':deskripsi' => $deskripsi
        ]);
        return [
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan!'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

// Fungsi untuk mengambil semua transaksi user
function getTransaksi($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare(
            "SELECT * FROM transaksi WHERE user_id = :user_id ORDER BY tanggal DESC"
        );
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// Fungsi untuk menghitung total pemasukan
function totalPemasukan($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare(
            "SELECT SUM(jumlah) AS total FROM transaksi 
            WHERE tipe = 'pemasukan' AND user_id = :user_id"
        );
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Fungsi untuk menghitung total pengeluaran
function totalPengeluaran($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare(
            "SELECT SUM(jumlah) AS total FROM transaksi 
            WHERE tipe = 'pengeluaran' AND user_id = :user_id"
        );
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Fungsi untuk menghitung saldo akhir
function saldoAkhir($pdo, $user_id) {
    $pemasukan   = totalPemasukan($pdo, $user_id);
    $pengeluaran = totalPengeluaran($pdo, $user_id);
    return $pemasukan - $pengeluaran;
}