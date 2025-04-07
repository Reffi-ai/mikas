<?php
session_start();
include 'config.php'; // (kalau perlu, kalau tidak perlu bisa dihapus)

// Fungsi untuk menghapus semua data sesi
function clearSession() {
    session_unset();
}

// Fungsi untuk menghancurkan sesi
function destroySession() {
    session_destroy();
}

// Fungsi untuk redirect ke halaman lain
function redirectTo($location) {
    header("Location: $location");
    exit();
}

// Fungsi utama untuk logout
function handleLogout() {
    clearSession();
    destroySession();
    redirectTo('index.html');
}

// Jalankan logout
handleLogout();
?>
