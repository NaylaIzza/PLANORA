<?php
// ============================================
// api/controllers/AuthController.php
// Menangani: register & login
// ============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/response.php';

setCorsHeaders();

$action = $_GET['action'] ?? '';

if ($action === 'register') {
    handleRegister();
} elseif ($action === 'login') {
    handleLogin();
} else {
    error('Aksi tidak dikenali.', 404);
}

// ---------- REGISTER ----------
function handleRegister(): void {
    $data  = getBody();
    $nama  = trim($data['nama']     ?? '');
    $email = trim($data['email']    ?? '');
    $pass  = trim($data['password'] ?? '');

    if (!$nama || !$email || !$pass) {
        error('Semua field wajib diisi.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error('Format email tidak valid.');
    }
    if (strlen($pass) < 6) {
        error('Password minimal 6 karakter.');
    }

    $db = getDB();

    $check = $db->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param('s', $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        error('Email sudah terdaftar.');
    }

    $hashed = password_hash($pass, PASSWORD_BCRYPT);
    $stmt   = $db->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $nama, $email, $hashed);

    if (!$stmt->execute()) {
        error('Gagal mendaftar, coba lagi.', 500);
    }

    $new_id = $db->insert_id;

    // Buat row user_stats
    $stat = $db->prepare("INSERT INTO user_stats (user_id, total_selesai) VALUES (?, 0)");
    $stat->bind_param('i', $new_id);
    $stat->execute();

    $db->close();
    success([], 'Pendaftaran berhasil! Silakan login.');
}

// ---------- LOGIN ----------
function handleLogin(): void {
    $data  = getBody();
    $email = trim($data['email']    ?? '');
    $pass  = trim($data['password'] ?? '');

    if (!$email || !$pass) {
        error('Email dan password wajib diisi.');
    }

    $db   = getDB();
    $stmt = $db->prepare("SELECT id, nama, email, password, telegram_id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($pass, $user['password'])) {
        error('Email atau password salah.', 401);
    }

    $db->close();
    success([
        'user' => [
            'id'          => $user['id'],
            'nama'        => $user['nama'],
            'email'       => $user['email'],
            'telegram_id' => $user['telegram_id'] ?? ''
        ]
    ], 'Login berhasil.');
}
