<?php
session_start(); // digunakan untuk mengaktifkan session agar bisa mengakses $_SESSION.
require_once 'config.php';
require_once 'functions.php';

// Fungsi ini memastikan bahwa user sudah login.
function validasiLogin() {
    if (!isset($_SESSION['user_id'])) {
        die("Error: Anda harus login terlebih dahulu.");
    }
    return $_SESSION['user_id'];
}

// Fungsi ini menangani permintaan POST dari form yang ada di halaman
function tanganiPermintaanPost($pdo, $user_id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        if ($action === 'tambah') {
            tambahUtang($pdo, $user_id, $_POST['nama'], $_POST['jumlah'], $_POST['keterangan'], false);
        } elseif ($action === 'lunas') {
            tandaiUtangLunas($pdo, $user_id, $_POST['id']);
        } elseif ($action === 'hapus') {
            hapusUtangLunas($pdo, $user_id, $_POST['id']);
        }

        header('Location: utang_index.php'); // Setelah aksi selesai, pengguna diarahkan kembali ke halaman utang_index.php.
        exit;
    }
}

// Fungsi ini mengambil data utang dari database untuk ditampilkan di halaman.
function ambilDataUtang($pdo, $user_id) {
    return [
        'daftar_utang' => ambilSemuaUtang($pdo, $user_id), // Semua utang (ambilSemuaUtang)
        'total_per_pelanggan' => totalUtangPerPelanggan($pdo, $user_id) // Total utang per pelanggan (totalUtangPerPelanggan)
    ];
}

// Validasi login dan ambil user_id
$user_id = validasiLogin();

// Tangani permintaan POST
tanganiPermintaanPost($pdo, $user_id);

// Ambil data utang
$dataUtang = ambilDataUtang($pdo, $user_id);
$daftar_utang = $dataUtang['daftar_utang'];
$total_per_pelanggan = $dataUtang['total_per_pelanggan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utang Pelanggan</title>
    <link rel="stylesheet" href="utang_style.css">
</head>
<body>
    <div class="container">
        <a href="dasboard.php" class="kembali">&#8617; Kembali</a>
        <div class="centered utang">
            <h1>Pencatatan Utang</h1>
            <form method="POST"> <!-- Form ini dikirim dengan method POST ke server -->
                <input type="hidden" name="action" value="tambah">
                <input type="text" name="nama" placeholder="Nama" autocomplete="off" required>
                <input type="number" name="jumlah" step="1000" min="0" placeholder="Jumlah" required>
                <input type="text" name="keterangan" placeholder="Keterangan" autocomplete="off" required>
                <button type="submit" class="tambah">Tambah</button>
            </form>
        </div>
        <h2>Daftar Utang</h2>
        <div class="centered">
            <div class="table-responsive">
                <table>
                    <tr><th>Nama</th><th>Jumlah</th><th>Keterangan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                    <?php if (empty($daftar_utang)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada data transaksi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daftar_utang as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td>Rp<?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td>
                                <?php if ($row['status'] !== 'Lunas'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="lunas">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="lunas" onclick="return confirm('Utang lunas?')">Lunas</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="hapus">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="hapus" onclick="return confirm('Hapus utang ini?')">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <h2>Total Utang per Pelanggan</h2>
        <div class="centeredtotal">
            <div class="table-responsive">
                <table>
                    <tr><th>Nama</th><th>Total</th></tr>
                    <?php if (empty($total_per_pelanggan)): ?>
                        <tr>
                            <td colspan="2" style="text-align: center;">Tidak ada data transaksi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($total_per_pelanggan as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nama']) ?></td>
                            <td>Rp<?= number_format($item['total'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>  
    </div>
</body>
</html>