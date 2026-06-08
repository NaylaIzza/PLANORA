<?php
// ============================================
// api/config/database.php — Koneksi Database
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Default XAMPP
define('DB_PASS', '');            // Default XAMPP (kosong)
define('DB_NAME', 'db_planora');

function getDB(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Koneksi database gagal: ' . $conn->connect_error
        ]);
        exit();
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
