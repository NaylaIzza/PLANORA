<?php
// ============================================
// api/helpers/response.php — Response Helper
// ============================================

function setCorsHeaders(): void {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

function success(array $data = [], string $message = 'OK'): void {
    echo json_encode(array_merge(['status' => 'success', 'message' => $message], $data));
    exit();
}

function error(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

function getBody(): array {
    return json_decode(file_get_contents('php://input'), true) ?? [];
}
