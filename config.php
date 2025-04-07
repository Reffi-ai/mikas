<?php

function createMysqliConnection(
    string $servername,
    string $username,
    string $password,
    string $dbname
): mysqli {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log("Koneksi gagal: " . $conn->connect_error);
        throw new Exception("Koneksi MySQLi gagal.");
    }

    return $conn;
}

function createPdoConnection(
    string $servername,
    string $username,
    string $password,
    string $dbname
): PDO {
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Koneksi PDO gagal: " . $e->getMessage());
    }
}

// Konfigurasi diambil dari environment atau default
function getDatabaseConfig(): array {
    return [
        'servername' => getenv('DB_SERVER') ?: 'localhost',
        'username'   => getenv('DB_USERNAME') ?: 'root',
        'password'   => getenv('DB_PASSWORD') ?: '',
        'dbname'     => getenv('DB_NAME') ?: 'mikass',
    ];
}

// Contoh cara pakai fungsi-fungsinya:
$config = getDatabaseConfig();

try {
    $conn = createMysqliConnection(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );

    $pdo = createPdoConnection(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );
} catch (Exception $e) {
    error_log($e->getMessage());
    die("Koneksi database gagal. Silakan coba lagi nanti.");
}

?>
