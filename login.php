<?php
session_start();
include 'config.php';

// Mencari data user berdasarkan email dari database users.
function getUserByEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT id, full_name, warmindo_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $stmt->close();
    return $user;
}

// Fungsi untuk memverifikasi password
function verifyPassword($inputPassword, $hashedPassword) {
    return password_verify($inputPassword, $hashedPassword);
}

// Fungsi untuk menyimpan data user ke session
function setSessionData($user, $email) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['warmindo_name'] = $user['warmindo_name'];
    $_SESSION['email'] = $email;
}

// Fungsi untuk menangani login
function handleLogin($conn, $email, $password) {
    $user = getUserByEmail($conn, $email);

    if (!$user) {
        return "Email tidak ditemukan!";
    }

    if (!verifyPassword($password, $user['password'])) {
        return "Password salah!";
    }

    setSessionData($user, $email);
    redirectToDashboard();
}

// Mengarahkan pengguna ke halaman dasboard.php setelah login sukses.
function redirectToDashboard() {
    header("Location: dasboard.php");
    exit();
}

// Fungsi utama untuk menjalankan login
function processLogin($conn) {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return;
    }

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $error = handleLogin($conn, $email, $password);

    if ($error) {
        echo $error;
    }
}

// Jalankan proses login
processLogin($conn);

// Menutup koneksi database setelah selesai.
$conn->close();
?>
