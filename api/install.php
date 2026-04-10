<?php
// LeadFlow — Kurulum Scripti (Genel / Sektör-Bağımsız)
// İlk deploy'da bir kez çalıştırılır: tabloları oluşturur, varsayılan ayarları yükler
// Sektöre özel seed data (hizmetler, galeri, yorumlar) sektör install.php'sinden gelir
// Kurulumdan sonra bu dosyayı silmeniz önerilir

require_once __DIR__ . '/config.php';

// Basit güvenlik — sadece kurulum token'ı ile çalışsın
$installToken = $_GET['token'] ?? '';
if ($installToken !== ADMIN_API_TOKEN) {
    die('<!DOCTYPE html><html><body style="font-family:Inter,sans-serif;padding:40px;text-align:center;">
    <h2>Kurulum Korumalı</h2>
    <p>Kurulumu çalıştırmak için URL\'ye token ekleyin:</p>
    <code>install.php?token=ADMIN_API_TOKEN_DEGERI</code>
    </body></html>');
}

$db = getDB();
$messages = [];
$errors = [];

// ══════════════════════════════════════
// 1. TABLOLARI OLUŞTUR
// ══════════════════════════════════════
try {
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $stmt) {
        if (!empty($stmt) && stripos($stmt, 'CREATE') !== false) {
            $db->exec($stmt);
        }
    }
    $messages[] = 'Tablolar basariyla olusturuldu.';
} catch (Exception $e) {
    $errors[] = 'Tablo olusturma hatasi: ' . $e->getMessage();
}

// ══════════════════════════════════════
// 1.5 MIGRATION — Mevcut tablolara yeni kolonlar ekle
// ══════════════════════════════════════
$migrations = [
    "ALTER TABLE `blogs` ADD COLUMN `cover_image` VARCHAR(500) AFTER `tags`",
    "ALTER TABLE `services` ADD COLUMN `image` VARCHAR(500) AFTER `icon`",
    "ALTER TABLE `testimonials` ADD COLUMN `role` VARCHAR(255) DEFAULT '' AFTER `name`",
    "ALTER TABLE `blogs` ADD COLUMN `is_featured` TINYINT(1) DEFAULT 0 AFTER `status`",
];
foreach ($migrations as $mig) {
    try {
        $db->exec($mig);
        $messages[] = 'Migration: ' . substr($mig, 0, 60) . '...';
    } catch (Exception $e) {
        // Kolon zaten varsa hata verir, sorun değil
    }
}

// ══════════════════════════════════════
// 2. VARSAYILAN AYARLARI YÜKLE (boşsa)
// ══════════════════════════════════════
try {
    $forceReset = isset($_GET['force']);
    $count = $db->query('SELECT COUNT(*) FROM settings')->fetchColumn();
    if ($forceReset && (int)$count > 0) {
        $db->exec('DELETE FROM settings');
        $messages[] = 'Eski ayarlar silindi (force reset).';
        $count = 0;
    }
    if ((int)$count === 0) {
        // Defaults'ı data/settings.json'dan oku — site üretimi sırasında doldurulan gerçek değerler
        $settingsFile = __DIR__ . '/../data/settings.json';
        $jsonDefaults = [];
        if (file_exists($settingsFile)) {
            $raw = file_get_contents($settingsFile);
            $parsed = json_decode($raw, true);
            if (is_array($parsed)) {
                $jsonDefaults = $parsed;
            }
        }
        // Fallback değerler — settings.json yoksa veya eksik anahtar varsa kullanılır
        $defaults = array_merge([
            'business_name'      => 'İşletme Adı',
            'phone'              => '',
            'phone_raw'          => '',
            'email'              => '',
            'address'            => '',
            'working_hours'      => 'Pzt-Cmt: 08:00 - 20:00',
            'working_hours_short'=> 'Pzt-Cmt: 08:00 - 20:00',
            'whatsapp_number'    => '',
            'phone2'             => '',
            'phone2_raw'         => '',
            'hero_subtitle'      => '',
            'footer_description' => '',
            'cta_title'          => '',
            'cta_description'    => '',
            'map_embed_url'      => '',
            'map_link_url'       => '',
            'instagram'          => '',
            'facebook'           => '',
            'youtube'            => '',
            'blog_author_name'   => 'Uzman Ekibimiz',
            'blog_author_bio'    => 'Profesyonel ekibimiz, sektördeki bilgi birikimini sizlerle paylaşmayı amaçlamaktadır.',
            'admin_password_hash'=> '',
        ], $jsonDefaults);

        $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)');
        foreach ($defaults as $k => $v) {
            $stmt->execute([':k' => $k, ':v' => $v]);
        }
        $messages[] = 'Varsayilan ayarlar yuklendi (' . count($defaults) . ' kayit).';
    } else {
        $messages[] = 'Ayarlar zaten mevcut (' . $count . ' kayit), atlanıyor.';
    }
} catch (Exception $e) {
    $errors[] = 'Ayar yukleme hatasi: ' . $e->getMessage();
}

// ══════════════════════════════════════
// 3. VARSAYILAN WHATSAPP ŞABLONLARI
// ══════════════════════════════════════
try {
    $count = $db->query('SELECT COUNT(*) FROM whatsapp_templates')->fetchColumn();
    if ((int)$count === 0) {
        $templates = [
            ['Fiyat Bilgisi', 'Merhaba! Fiyat bilgisi almak istiyorum.'],
            ['Randevu Talebi', 'Merhaba! Randevu almak istiyorum. Uygun zamanlarinizi ogrenebilir miyim?'],
            ['Adres Tarifi', 'Merhaba! Isletmenizin adres tarifini alabilir miyim?'],
            ['Genel Bilgi', 'Merhaba! Web sitenizden ulasiyorum. Hizmetleriniz hakkinda bilgi almak istiyorum.'],
        ];
        $stmt = $db->prepare('INSERT INTO whatsapp_templates (title, message, sort_order) VALUES (:t, :m, :s)');
        foreach ($templates as $i => $t) {
            $stmt->execute([':t' => $t[0], ':m' => $t[1], ':s' => $i]);
        }
        $messages[] = 'WhatsApp sablonlari yuklendi (' . count($templates) . ' sablon).';
    }
} catch (Exception $e) {
    $errors[] = 'WhatsApp sablon yukleme hatasi: ' . $e->getMessage();
}

// ══════════════════════════════════════
// 3.2 Eski fiyat tablolarını temizle (artık service_items kullanılıyor)
// ══════════════════════════════════════
try {
    $db->exec('DROP TABLE IF EXISTS price_items');
    $db->exec('DROP TABLE IF EXISTS price_categories');
    $db->exec('DROP TABLE IF EXISTS price_list');
} catch (Exception $e) { /* yoksay */ }

// ══════════════════════════════════════
// 3.5 DOSYA İZİNLERİNİ DÜZELT
// ══════════════════════════════════════
$uploadDir = __DIR__ . '/../img/uploads/';
if (is_dir($uploadDir)) {
    $fixed = 0;
    foreach (glob($uploadDir . '*') as $file) {
        if (is_file($file)) {
            chmod($file, 0644);
            $fixed++;
        }
    }
    if ($fixed > 0) {
        $messages[] = 'Görsel dosya izinleri düzeltildi (' . $fixed . ' dosya).';
    }
} else {
    @mkdir($uploadDir, 0755, true);
    $messages[] = 'img/uploads/ dizini oluşturuldu.';
}

// ══════════════════════════════════════
// 4. SEKTÖRE ÖZEL SEED DATA (varsa)
// ══════════════════════════════════════
// Sektör install.php'si bu dosyanın yanında sector_seed.php olarak bulunabilir
$sectorSeed = __DIR__ . '/sector_seed.php';
if (file_exists($sectorSeed)) {
    include $sectorSeed;
    $messages[] = 'Sektöre özel veriler yüklendi.';
}

// ══════════════════════════════════════
// 5. SONUÇ EKRANI
// ══════════════════════════════════════
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>LeadFlow Kurulum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg max-w-lg w-full p-8">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#93B4F0] to-[#4F7DD8] flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">LeadFlow Kurulum</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                <h3 class="font-bold text-red-700 mb-2">Hatalar:</h3>
                <?php foreach ($errors as $err): ?>
                    <p class="text-red-600 text-sm"><?= htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($messages)): ?>
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                <h3 class="font-bold text-green-700 mb-2">Basarili:</h3>
                <?php foreach ($messages as $msg): ?>
                    <p class="text-green-600 text-sm"><?= htmlspecialchars($msg) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
            <p class="text-amber-800 text-sm font-semibold">Guvenlik Uyarisi:</p>
            <p class="text-amber-700 text-sm mt-1">Kurulum tamamlandiktan sonra bu dosyayi (install.php) sunucudan silin veya yeniden adlandirin.</p>
        </div>

        <div class="space-y-3">
            <a href="../" class="block w-full bg-gradient-to-r from-[#93B4F0] to-[#4F7DD8] text-white text-center font-bold py-3 rounded-xl hover:opacity-90 transition">Siteye Git</a>
            <a href="../admin/" class="block w-full bg-gray-100 text-gray-700 text-center font-bold py-3 rounded-xl hover:bg-gray-200 transition">Admin Paneli</a>
        </div>
    </div>
</body>
</html>
