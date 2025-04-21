<?php
session_start();
include 'config.php';

// Membersihkan input dari karakter berbahaya (menghindari XSS).
function sanitizeInput(string $input): string {
    return htmlspecialchars($input);
}

// Mengenkripsi password sebelum disimpan ke database menggunakan algoritma hashing bawaan PHP.
function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Mengecek apakah email sudah pernah terdaftar sebelumnya.
function isEmailRegistered(mysqli $conn, string $email): bool {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $isRegistered = $stmt->num_rows > 0;
    $stmt->close();
    return $isRegistered;
}

// Menyimpan data pengguna baru ke dalam database.
function registerUser(mysqli $conn, string $fullName, string $warmindoName, string $email, string $hashedPassword): bool {
    $stmt = $conn->prepare("INSERT INTO users (full_name, warmindo_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $warmindoName, $email, $hashedPassword);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Setelah berhasil menyimpan data di database
// fungsi setSessionData() dipanggil untuk menyimpan data pengguna ke dalam variabel sesi ($_SESSION).
function setSessionData(string $fullName, string $warmindoName, string $email): void {
    $_SESSION['full_name'] = $fullName;
    $_SESSION['warmindo_name'] = $warmindoName;
    $_SESSION['email'] = $email;
}

function handleRegistration(mysqli $conn): void {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return;
    }

    // Mengambil input dari form HTML (full_name, warmindo_name, email, password).
    $fullName = sanitizeInput($_POST['full_name']); // menggunakan fungsi sanitizeInput() untuk mencegah serangan XSS.
    $warmindoName = sanitizeInput($_POST['warmindo_name']);
    $email = sanitizeInput($_POST['email']);
    $password = hashPassword($_POST['password']);

    // memeriksa apakah email sudah ada di database.
    if (isEmailRegistered($conn, $email)) {
        echo "Email sudah terdaftar! Silakan gunakan email lain.";
        return;
    }

    // Menyimpan data pengguna baru ke dalam database.
    if (registerUser($conn, $fullName, $warmindoName, $email, $password)) {
        setSessionData($fullName, $warmindoName, $email);
        header("Location: login.html");
        exit();
    } else {
        echo "Terjadi kesalahan saat registrasi.";
    }
}

// Menjalankan fungsi registrasi.
handleRegistration($conn);

// Tutup koneksi setelah semua selesai
$conn->close();
?>
