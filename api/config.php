<?php
// LeadFlow — Veritabanı Bağlantı Ayarları
// Bu değerler hosting cPanel'den alınır (MySQL Veritabanları bölümü)

// ══════════════════════════════════════
// AYARLAR — Her müşteri sitesi için değiştir
// ══════════════════════════════════════
define('DB_HOST', 'localhost');         // Genelde: localhost
define('DB_NAME', 'anka7332_lidya_koltuk');         // cPanel'de oluşturduğun DB adı
define('DB_USER', 'anka7332_lidya_admin');         // cPanel'de oluşturduğun DB kullanıcısı
define('DB_PASS', '*******');         // DB kullanıcı şifresi

// Admin API güvenlik token'ı — rastgele uzun string
define('ADMIN_API_TOKEN', 'b8702e57af9c79650d67c2ca1abe24c8ffa639c58247cf85a7452daae0832113');

// ══════════════════════════════════════
// PDO BAĞLANTISI
// ══════════════════════════════════════
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(503);
            echo json_encode(['error' => 'Veritabani baglantisi kurulamadi']);
            exit;
        }
    }
    return $pdo;
}

// ══════════════════════════════════════
// YARDIMCI FONKSİYONLAR
// ══════════════════════════════════════

// JSON yanıt gönder
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// CORS başlıkları
function setCorsHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('X-Content-Type-Options: nosniff');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

// Bearer token doğrulama
function authCheck() {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    return $header === 'Bearer ' . ADMIN_API_TOKEN;
}

// POST/PUT body'yi JSON olarak oku
function getJsonBody() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        jsonResponse(['error' => 'Gecersiz JSON'], 400);
    }
    return $data;
}
