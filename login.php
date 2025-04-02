<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Ambil data pengguna berdasarkan email
    $stmt = $conn->prepare("SELECT id, full_name, warmindo_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id']; // Simpan user_id ke sesi setelah login berhasil

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set data ke sesi
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['warmindo_name'] = $user['warmindo_name'];
            $_SESSION['email'] = $email;

            // Redirect ke halaman pengaturan
            header("Location: dasboard.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Email tidak ditemukan!";
    }

    $stmt->close();
    $conn->close();
}
?>