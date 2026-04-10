<?php
// LeadFlow — Settings API (PHP + MySQL)
// GET  /api/settings.php        → Public, tüm ayarları döndürür
// PUT  /api/settings.php        → Auth gerekli, ayarları günceller

require_once __DIR__ . '/config.php';
setCorsHeaders();

$method = $_SERVER['REQUEST_METHOD'];

// ── GET — Herkese açık ──
if ($method === 'GET') {
    $db = getDB();
    $stmt = $db->query('SELECT setting_key, setting_value FROM settings');
    $rows = $stmt->fetchAll();

    $settings = [];
    foreach ($rows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    jsonResponse($settings);
}

// ── PUT — Sadece admin ──
if ($method === 'PUT') {
    if (!authCheck()) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $data = getJsonBody();
    if (!$data || !is_array($data)) {
        jsonResponse(['error' => 'Gecersiz veri'], 400);
    }

    $db = getDB();
    $stmt = $db->prepare('REPLACE INTO settings (setting_key, setting_value) VALUES (:k, :v)');

    foreach ($data as $key => $value) {
        $stmt->execute([':k' => $key, ':v' => $value]);
    }

    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Method not allowed'], 405);
