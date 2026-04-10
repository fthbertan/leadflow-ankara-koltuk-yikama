<?php
// LeadFlow — Messages API (PHP + MySQL)
// POST   /api/messages.php          → Public, yeni mesaj kaydeder + e-posta gonderir
// GET    /api/messages.php          → Auth gerekli, tum mesajlari dondurur
// GET    /api/messages.php?unread=1 → Auth gerekli, okunmamis sayisini dondurur
// PUT    /api/messages.php          → Auth gerekli, okundu durumunu gunceller
// DELETE /api/messages.php?id=x     → Auth gerekli, mesaj siler

require_once __DIR__ . '/config.php';
setCorsHeaders();

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

// ── POST — Public (iletisim formu) ──
if ($method === 'POST') {
    $data = getJsonBody();

    $name = trim($data['name'] ?? '');
    if ($name === '') {
        jsonResponse(['error' => 'Isim alani zorunlu'], 400);
    }

    $stmt = $db->prepare('
        INSERT INTO messages (name, phone, service, preferred_date, preferred_time, notes)
        VALUES (:name, :phone, :service, :date, :time, :notes)
    ');
    $stmt->execute([
        ':name'    => $name,
        ':phone'   => trim($data['phone'] ?? ''),
        ':service' => trim($data['service'] ?? ''),
        ':date'    => trim($data['date'] ?? ''),
        ':time'    => trim($data['time'] ?? ''),
        ':notes'   => trim($data['notes'] ?? ''),
    ]);

    // E-posta bildirimi gonder (best-effort)
    try {
        $emailStmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'email'");
        $emailStmt->execute();
        $toEmail = $emailStmt->fetchColumn();

        if ($toEmail && filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            $subject = '=?UTF-8?B?' . base64_encode('Yeni Iletisim Talebi - ' . $name) . '?=';
            $body  = "<h2>Yeni Iletisim Talebi</h2>";
            $body .= "<p><strong>Ad Soyad:</strong> " . htmlspecialchars($name) . "</p>";
            $body .= "<p><strong>Telefon:</strong> " . htmlspecialchars($data['phone'] ?? '-') . "</p>";
            $body .= "<p><strong>Hizmet:</strong> " . htmlspecialchars($data['service'] ?? '-') . "</p>";
            $body .= "<p><strong>Tarih:</strong> " . htmlspecialchars($data['date'] ?? '-') . "</p>";
            $body .= "<p><strong>Saat:</strong> " . htmlspecialchars($data['time'] ?? '-') . "</p>";
            $body .= "<p><strong>Not:</strong> " . htmlspecialchars($data['notes'] ?? '-') . "</p>";
            $body .= "<hr><p><em>Bu mesaj web sitesi iletisim formundan gonderildi.</em></p>";

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: noreply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n";

            @mail($toEmail, $subject, $body, $headers);
        }
    } catch (Exception $e) {
        // E-posta gonderilemese bile mesaj kaydedildi, sessizce devam et
    }

    jsonResponse(['success' => true]);
}

// ── GET — Admin (tum mesajlar veya okunmamis sayisi) ──
if ($method === 'GET') {
    if (!authCheck()) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }

    // Sadece okunmamis sayisi
    if (isset($_GET['unread'])) {
        $count = $db->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();
        jsonResponse(['unread' => (int)$count]);
    }

    $stmt = $db->query('SELECT * FROM messages ORDER BY created_at DESC');
    $messages = $stmt->fetchAll();
    jsonResponse(['messages' => $messages]);
}

// ── PUT — Admin (okundu/okunmadi guncelle) ──
if ($method === 'PUT') {
    if (!authCheck()) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $data = getJsonBody();
    $id = $data['id'] ?? null;
    $isRead = $data['is_read'] ?? null;

    if (!$id || $isRead === null) {
        jsonResponse(['error' => 'id ve is_read alanlari zorunlu'], 400);
    }

    $stmt = $db->prepare('UPDATE messages SET is_read = :is_read WHERE id = :id');
    $stmt->execute([':is_read' => (int)$isRead, ':id' => (int)$id]);

    jsonResponse(['success' => true]);
}

// ── DELETE — Admin ──
if ($method === 'DELETE') {
    if (!authCheck()) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }

    $id = $_GET['id'] ?? null;
    if (!$id) {
        jsonResponse(['error' => 'id parametresi zorunlu'], 400);
    }

    $stmt = $db->prepare('DELETE FROM messages WHERE id = :id');
    $stmt->execute([':id' => (int)$id]);

    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Method not allowed'], 405);
