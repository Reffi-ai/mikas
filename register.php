<?php
session_start();
include 'config.php';

function sanitizeInput(string $input): string {
    return htmlspecialchars($input);
}

function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

function isEmailRegistered(mysqli $conn, string $email): bool {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $isRegistered = $stmt->num_rows > 0;
    $stmt->close();
    return $isRegistered;
}

function registerUser(mysqli $conn, string $fullName, string $warmindoName, string $email, string $hashedPassword): bool {
    $stmt = $conn->prepare("INSERT INTO users (full_name, warmindo_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $warmindoName, $email, $hashedPassword);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function setSessionData(string $fullName, string $warmindoName, string $email): void {
    $_SESSION['full_name'] = $fullName;
    $_SESSION['warmindo_name'] = $warmindoName;
    $_SESSION['email'] = $email;
}

function handleRegistration(mysqli $conn): void {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return;
    }

    $fullName = sanitizeInput($_POST['full_name']);
    $warmindoName = sanitizeInput($_POST['warmindo_name']);
    $email = sanitizeInput($_POST['email']);
    $password = hashPassword($_POST['password']);

    if (isEmailRegistered($conn, $email)) {
        echo "Email sudah terdaftar! Silakan gunakan email lain.";
        return;
    }

    if (registerUser($conn, $fullName, $warmindoName, $email, $password)) {
        setSessionData($fullName, $warmindoName, $email);
        header("Location: login.html");
        exit();
    } else {
        echo "Terjadi kesalahan saat registrasi.";
    }
}

// Eksekusi utama
handleRegistration($conn);

// Tutup koneksi setelah semua selesai
$conn->close();
?>
