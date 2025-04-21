<?php
require 'config.php';

// Digunakan untuk menambahkan data transaksi baru (pemasukan/pengeluaran) ke tabel transaksi
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

// Mengambil seluruh data utang user dari tabel utang
function ambilSemuaUtang($pdo, $user_id) {
    $sql = "SELECT * FROM utang WHERE user_id = :user_id ORDER BY tanggal DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Menandai status sebuah utang menjadi Lunas dan menghapus transaksi pengeluaran yang berkaitan
function tandaiUtangLunas($pdo, $user_id, $id) {
    $sql = "SELECT nama, keterangan FROM utang WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':user_id' => $user_id
    ]);
    $utang = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utang) {
        // Tandai utang sebagai lunas
        $sql = "UPDATE utang SET status = 'Lunas' WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':user_id' => $user_id
        ]);

        // Hapus transaksi dengan deskripsi yang sesuai (pakai hapusTransaksiBerdasarkanDeskripsi())
        $deskripsi = "Utang {$utang['nama']}: {$utang['keterangan']}";
        hapusTransaksiBerdasarkanDeskripsi($pdo, $user_id, $deskripsi);
    }
}

// Mengelompokkan utang berdasarkan nama (pelanggan) dan menjumlahkan total utangnya yang belum lunas
function totalUtangPerPelanggan($pdo, $user_id) {
    $sql = "SELECT nama, SUM(jumlah) AS total 
            FROM utang 
            WHERE user_id = :user_id AND status != 'Lunas' 
            GROUP BY nama";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Menghapus data utang yang sudah lunas dari database
function hapusUtangLunas($pdo, $user_id, $id) {
    $sql = "DELETE FROM utang WHERE id = :id AND user_id = :user_id AND status = 'Lunas'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':user_id' => $user_id
    ]);
}

// fungsi ini memanggil catatTransaksi dengan tipe 'pengeluaran'
function catatPengeluaranDariUtang($pdo, $user_id, $jumlah, $deskripsi) {
    catatTransaksi($pdo, $user_id, 'pengeluaran', $jumlah, $deskripsi);
}

// Menambahkan data ke tabel utang dan juga langsung mencatat pengeluarannya sebagai transaksi
function tambahUtang($pdo, $user_id, $nama, $jumlah, $keterangan) {
    $sql = "INSERT INTO utang (user_id, nama, jumlah, keterangan, status, tanggal) 
            VALUES (:user_id, :nama, :jumlah, :keterangan, 'Belum Lunas', NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':nama' => $nama,
        ':jumlah' => $jumlah,
        ':keterangan' => $keterangan
    ]);

    // fungsi ini mencatat pengeluaran terkait utang ke tabel transaksi
    catatPengeluaranDariUtang($pdo, $user_id, $jumlah, "Utang $nama: $keterangan");
}

// Menghapus transaksi berdasarkan user_id dan deskripsi. Digunakan saat utang sudah lunas agar pengeluarannya juga dihapus
function hapusTransaksiBerdasarkanDeskripsi($pdo, $user_id, $deskripsi) {
    $sql = "DELETE FROM transaksi WHERE user_id = :user_id AND deskripsi = :deskripsi";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':deskripsi' => $deskripsi
    ]);
}