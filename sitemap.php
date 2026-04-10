<?php
// LeadFlow — Dinamik Sitemap Oluşturucu
// Statik sayfalar + DB'den blog yazıları + image sitemap

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$today = date('Y-m-d');

// DB bağlantısı
require_once __DIR__ . '/api/config.php';
$db = getDB();

// İşletme adını DB'den al
$businessName = 'İşletme';
try {
    $stmtS = $db->query("SELECT value FROM settings WHERE `key` = 'business_name' LIMIT 1");
    $rowS = $stmtS->fetch();
    if ($rowS) $businessName = $rowS['value'];
} catch (Exception $e) {}

// SEO lokasyon ve hizmet verileri
require_once __DIR__ . '/seo-data.php';
$locs = array_keys($locations);
$svcs = array_keys($services);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <!-- Ana Sayfa -->
    <url>
        <loc><?= $baseUrl ?>/</loc>
        <lastmod><?= $today ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
        <image:image>
            <image:loc><?= $baseUrl ?>/images/hero-1.webp</image:loc>
            <image:title><?= htmlspecialchars($businessName) ?></image:title>
        </image:image>
    </url>

    <!-- Galeri -->
    <url>
        <loc><?= $baseUrl ?>/galeri.html</loc>
        <lastmod><?= $today ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
<?php
// Galeri görsellerini ekle
$galleryImages = glob(__DIR__ . '/images/gallery-*.{webp,jpg,png}', GLOB_BRACE);
foreach ($galleryImages as $img) {
    $filename = basename($img);
    echo "        <image:image>\n";
    echo "            <image:loc>" . $baseUrl . "/images/" . htmlspecialchars($filename) . "</image:loc>\n";
    echo "            <image:title>" . htmlspecialchars($businessName) . " - " . htmlspecialchars(ucfirst(str_replace(['-', '.webp', '.jpg', '.png'], [' ', '', '', ''], $filename))) . "</image:title>\n";
    echo "        </image:image>\n";
}
?>
    </url>

    <!-- Blog Listesi -->
    <url>
        <loc><?= $baseUrl ?>/blog/</loc>
        <lastmod><?= $today ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Yasal Sayfalar -->
    <url>
        <loc><?= $baseUrl ?>/gizlilik-politikasi</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/kvkk</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/kullanim-sartlari</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

<?php
// Blog yazıları — DB'den
try {
    $stmt = $db->query("SELECT slug, updated_at, created_at, cover_image, title FROM blogs WHERE status = 'published' ORDER BY created_at DESC");
    $blogs = $stmt->fetchAll();
    foreach ($blogs as $blog) {
        $lastmod = !empty($blog['updated_at']) ? date('Y-m-d', strtotime($blog['updated_at'])) : date('Y-m-d', strtotime($blog['created_at']));
        echo "    <url>\n";
        echo "        <loc>" . $baseUrl . "/blog/" . htmlspecialchars($blog['slug']) . "</loc>\n";
        echo "        <lastmod>" . $lastmod . "</lastmod>\n";
        echo "        <changefreq>monthly</changefreq>\n";
        echo "        <priority>0.7</priority>\n";
        if (!empty($blog['cover_image'])) {
            echo "        <image:image>\n";
            echo "            <image:loc>" . $baseUrl . "/" . ltrim(htmlspecialchars($blog['cover_image']), '/') . "</image:loc>\n";
            echo "            <image:title>" . htmlspecialchars($blog['title']) . "</image:title>\n";
            echo "        </image:image>\n";
        }
        echo "    </url>\n";
    }
} catch (Exception $e) {
    // Blog tablosu yoksa sessizce devam et
}

// Lokasyon sayfaları — seo-data.php'den dinamik olarak yükleniyor
// $locs ve $svcs değişkenleri dosyanın başında include edilen seo-data.php'den gelir
foreach ($locs as $loc) {
    foreach ($svcs as $svc) {
        echo "    <url>\n";
        echo "        <loc>" . $baseUrl . "/" . $loc . "-" . $svc . "</loc>\n";
        echo "        <lastmod>" . $today . "</lastmod>\n";
        echo "        <changefreq>monthly</changefreq>\n";
        echo "        <priority>0.8</priority>\n";
        echo "    </url>\n";
    }
}
?>
</urlset>
