<?php
// ============================================
// api/controllers/TaskController.php
// Menangani: list, tambah, selesai tugas
// ============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/response.php';

setCorsHeaders();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

match (true) {
    $method === 'GET'  && $action === 'list'   => handleList(),
    $method === 'POST' && $action === 'tambah' => handleTambah(),
    $method === 'POST' && $action === 'selesai'=> handleSelesai(),
    default => error('Aksi tidak dikenali.', 404)
};

// ---------- LIST ----------
function handleList(): void {
    $user_id = intval($_GET['user_id'] ?? 0);
    if (!$user_id) error('User ID diperlukan.');

    $db   = getDB();
    $stmt = $db->prepare("
        SELECT id, judul, matkul, deadline, kesulitan, prioritas, warna, bg
        FROM tasks
        WHERE user_id = ?
        ORDER BY FIELD(prioritas, 'KRUSIAL', 'SEDANG', 'NORMAL'), id ASC
    ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    $tugas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Ambil total selesai dari user_stats
    $s    = $db->prepare("SELECT total_selesai FROM user_stats WHERE user_id = ?");
    $s->bind_param('i', $user_id);
    $s->execute();
    $stat = $s->get_result()->fetch_assoc();

    $db->close();
    success([
        'tugas'          => $tugas,
        'total_selesai'  => $stat ? intval($stat['total_selesai']) : 0
    ]);
}

// ---------- TAMBAH ----------
function handleTambah(): void {
    $data      = getBody();
    $user_id   = intval($data['user_id']   ?? 0);
    $judul     = trim($data['judul']       ?? '');
    $matkul    = trim($data['matkul']      ?? '');
    $deadline  = trim($data['deadline']    ?? '');
    $kesulitan = trim($data['kesulitan']   ?? '');

    if (!$user_id || !$judul || !$matkul || !$deadline || !$kesulitan) {
        error('Semua field wajib diisi.');
    }

    // Kalkulasi prioritas
    $prioritas = 'SEDANG'; $warna = 'warning'; $bg = '#fffbeb';
    if ($deadline === 'Mendesak' || ($deadline === 'Sedang' && $kesulitan === 'Tinggi')) {
        $prioritas = 'KRUSIAL'; $warna = 'danger'; $bg = '#fef2f2';
    } elseif ($deadline === 'Senggang' && $kesulitan === 'Rendah') {
        $prioritas = 'NORMAL'; $warna = 'success'; $bg = '#f0fdf4';
    }

    $db   = getDB();
    $stmt = $db->prepare("
        INSERT INTO tasks (user_id, judul, matkul, deadline, kesulitan, prioritas, warna, bg)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param('isssssss', $user_id, $judul, $matkul, $deadline, $kesulitan, $prioritas, $warna, $bg);

    if (!$stmt->execute()) error('Gagal menyimpan tugas.', 500);

    $db->close();
    success(['prioritas' => $prioritas], 'Tugas berhasil ditambahkan.');
}

// ---------- SELESAI ----------
function handleSelesai(): void {
    $data    = getBody();
    $id      = intval($data['id']      ?? 0);
    $user_id = intval($data['user_id'] ?? 0);

    if (!$id || !$user_id) error('ID tugas dan user diperlukan.');

    $db  = getDB();
    $del = $db->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $del->bind_param('ii', $id, $user_id);

    if (!$del->execute() || $del->affected_rows === 0) {
        error('Tugas tidak ditemukan.', 404);
    }

    // Update counter selesai
    $upd = $db->prepare("
        INSERT INTO user_stats (user_id, total_selesai) VALUES (?, 1)
        ON DUPLICATE KEY UPDATE total_selesai = total_selesai + 1
    ");
    $upd->bind_param('i', $user_id);
    $upd->execute();

    $db->close();
    success([], 'Tugas diselesaikan.');
}
