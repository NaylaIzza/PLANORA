<?php
// ============================================
// api/controllers/UserController.php
// Menangani: update profil
// ============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/response.php';

setCorsHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error('Method tidak diizinkan.', 405);
}

$data        = getBody();
$user_id     = intval($data['user_id']     ?? 0);
$nama        = trim($data['nama']          ?? '');
$email       = trim($data['email']         ?? '');
$telegram_id = trim($data['telegram_id']   ?? '');

if (!$user_id || !$nama || !$email) {
    error('Data tidak lengkap.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error('Format email tidak valid.');
}

$db    = getDB();
$check = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$check->bind_param('si', $email, $user_id);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    error('Email sudah dipakai akun lain.');
}

$stmt = $db->prepare("UPDATE users SET nama = ?, email = ?, telegram_id = ? WHERE id = ?");
$stmt->bind_param('sssi', $nama, $email, $telegram_id, $user_id);

if (!$stmt->execute()) error('Gagal update profil.', 500);

$db->close();
success([
    'user' => [
        'id'          => $user_id,
        'nama'        => $nama,
        'email'       => $email,
        'telegram_id' => $telegram_id
    ]
], 'Profil berhasil diperbarui.');
