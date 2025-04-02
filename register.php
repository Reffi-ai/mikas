<?php
session_start(); // Pastikan sesi dimulai
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = htmlspecialchars($_POST['full_name']);
    $warmindo_name = htmlspecialchars($_POST['warmindo_name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Periksa apakah email sudah ada
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "Email sudah terdaftar! Silakan gunakan email lain.";
    } else {
        // Jika email belum ada, lakukan registrasi
        $stmt = $conn->prepare("INSERT INTO users (full_name, warmindo_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $warmindo_name, $email, $password);
        if ($stmt->execute()) {
            // Simpan data ke sesi
            $_SESSION['full_name'] = $full_name;
            $_SESSION['warmindo_name'] = $warmindo_name;
            $_SESSION['email'] = $email;

            // Redirect ke halaman login
            header("Location: login.html");
            exit();
        } else {
            echo "Terjadi kesalahan saat registrasi.";
        }
        $stmt->close();
    }
    $check_email->close();
    $conn->close();
}
?>