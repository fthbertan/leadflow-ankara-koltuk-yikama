<?php
// LeadFlow — Dinamik Hizmet SEO Sayfası (Genel Şablon)
// .htaccess ile /hizmet-slug → seo-service.php?svc=hizmet-slug yönlendirmesi
// Her hizmet için benzersiz SEO sayfası üretir (fiyat kalemleri dahil)
// $services ve $slugAliases seo-data.php'den gelir

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/seo-data.php';  // $locations, $services, $slugAliases
require_once __DIR__ . '/api/config.php';

$svcSlug = $_GET['svc'] ?? '';

// SVG ikon helper
function svgIcon($name, $class = '', $size = '1em') {
    $icons = [
        'call' => 'M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z',
        'local_shipping' => 'M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z',
        'dry_cleaning' => 'M19.56 11.36L13 8.44V7c0-.55-.45-1-1-1s-1 .45-1 1v1.44l-6.56 2.92c-.88.39-.88 1.63 0 2.02L11 16.36V19c0 .55.45 1 1 1s1-.45 1-1v-2.64l6.56-2.92c.88-.39.88-1.63 0-2.08zM12 14.3l-4.74-2.12L12 10.06l4.74 2.12L12 14.3z',
        'verified' => 'M23 12l-2.44-2.79.34-3.69-3.61-.82-1.89-3.2L12 2.96 8.6 1.5 6.71 4.69 3.1 5.5l.34 3.7L1 12l2.44 2.79-.34 3.7 3.61.82L8.6 22.5l3.4-1.47 3.4 1.46 1.89-3.19 3.61-.82-.34-3.69L23 12zm-12.91 4.72l-3.8-3.8 1.48-1.48 2.32 2.33 5.85-5.87 1.48 1.48-7.33 7.34z',
        'eco' => 'M6.05 8.05c-2.73 2.73-2.73 7.17 0 9.9C7.42 19.32 9.21 20 11 20s3.58-.68 4.95-2.05C19.43 14.47 20 4 20 4S9.53 4.57 6.05 8.05zm8.49 8.49c-.95.94-2.2 1.46-3.54 1.46-.89 0-1.73-.25-2.48-.68.92-2.88 4.02-6.03 6.93-7.05-.82 2.72-2.2 4.91-2.91 6.27z',
        'payments' => 'M19 14V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-9-1c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-6v11c0 1.1-.9 2-2 2H4v-2h17V7h2z',
        'schedule' => 'M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z',
        'cleaning_services' => 'M16 11h-1V3c0-.55-.45-1-1-1h-4c-.55 0-1 .45-1 1v8H8c-2.76 0-5 2.24-5 5v7h18v-7c0-2.76-2.24-5-5-5z',
        'local_laundry_service' => 'M9.17 16.83c1.56 1.56 4.1 1.56 5.66 0 1.56-1.56 1.56-4.1 0-5.66l-5.66 5.66zM18 2.01L6 2c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h12c1.11 0 2-.89 2-2V4c0-1.11-.89-1.99-2-1.99zM10 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM7 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm5 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z',
        'weekend' => 'M21 9V7c0-1.65-1.35-3-3-3H6C4.35 4 3 5.35 3 7v2c-1.65 0-3 1.35-3 3v5c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2v-5c0-1.65-1.35-3-3-3z',
        'bed' => 'M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z',
        'star' => 'M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z',
        'auto_awesome' => 'M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z',
        'curtains' => 'M20 19V3H4v16H2v2h20v-2h-2zM12 11l-2-2V5h4v4l-2 2zm-6 8V5h2v5l4 4 4-4V5h2v14H6z',
        'king_bed' => 'M20 10V7c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2v3c-1.1 0-2 .9-2 2v5h1.33L4 19h1l.67-2h12.67l.66 2h1l.67-2H22v-5c0-1.1-.9-2-2-2zm-9 0H6V7h5v3zm7 0h-5V7h5v3z',
        'history' => 'M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z',
        'workspace_premium' => 'M9.68 13.69L12 11.93l2.31 1.76-.88-2.85L15.75 9h-2.84L12 6.19 11.09 9H8.25l2.31 1.84-.88 2.85zM20 10c0-4.42-3.58-8-8-8s-8 3.58-8 8c0 2.03.76 3.87 2 5.28V23l6-2 6 2v-7.72c1.24-1.41 2-3.25 2-5.28zm-8-6c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6 2.69-6 6-6z',
    ];
    $path = $icons[$name] ?? ($icons['cleaning_services']);
    $c = htmlspecialchars($class);
    return '<svg class="'.$c.'" style="width:'.$size.';height:'.$size.';vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="'.$path.'"/></svg>';
}

// Slug alias handling ($slugAliases seo-data.php'den gelir)
if (isset($slugAliases[$svcSlug])) {
    if ($slugAliases[$svcSlug] === null) {
        header('Location: /#hizmetlerimiz');
        exit;
    }
    $svcSlug = $slugAliases[$svcSlug];
}

// Geçersiz hizmet kontrolü
if (!isset($services[$svcSlug])) {
    http_response_code(404);
    header('Location: /');
    exit;
}

$svc = $services[$svcSlug];

// Ayarları ve fiyat kalemlerini çek
$db = getDB();
$settings = [];
try {
    $stmtS = $db->query('SELECT setting_key, setting_value FROM settings');
    while ($row = $stmtS->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {}

// DB'den hizmet ve fiyat kalemlerini çek
$dbService = null;
$serviceItems = [];
try {
    $stmtSvc = $db->prepare("SELECT * FROM services WHERE LOWER(REPLACE(REPLACE(title, ' ', '-'), '&', '')) LIKE :slug OR title LIKE :name LIMIT 1");
    $stmtSvc->execute([':slug' => '%' . str_replace('-', '%', $svcSlug) . '%', ':name' => '%' . $svc['name'] . '%']);
    $dbService = $stmtSvc->fetch();

    if ($dbService) {
        $hasItems = false;
        try {
            $db->query('SELECT 1 FROM service_items LIMIT 1');
            $hasItems = true;
        } catch (Exception $e) {}

        if ($hasItems) {
            $stmtItems = $db->prepare('SELECT * FROM service_items WHERE service_id = :sid ORDER BY sort_order ASC, id ASC');
            $stmtItems->execute([':sid' => $dbService['id']]);
            $serviceItems = $stmtItems->fetchAll();
        }
    }
} catch (Exception $e) {}

$businessName = $settings['business_name'] ?? 'İşletme Adı';
$phone = $settings['phone'] ?? '';
$phoneRaw = $settings['phone_raw'] ?? '';
$phone2 = $settings['phone2'] ?? '';
$phone2Raw = $settings['phone2_raw'] ?? '';
$whatsapp = $settings['whatsapp_number'] ?? '';
$address = $settings['address'] ?? 'Türkiye';

// SEO değişkenleri
$pageTitle = $svc['name'] . ' Hizmeti | ' . $businessName;
$metaDesc = $businessName . ' profesyonel ' . strtolower($svc['name']) . ' hizmeti. ' . $svc['intro'] . ' Ücretsiz servis, uygun fiyat, garantili hizmet.';
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$canonicalUrl = $siteUrl . '/' . $svcSlug;

// Diğer hizmetler (internal linking)
$otherServices = [];
foreach ($services as $sSlug => $sData) {
    if ($sSlug !== $svcSlug) {
        $otherServices[] = ['slug' => $sSlug, 'name' => $sData['name'], 'icon' => $sData['icon']];
    }
}

// Son blog yazıları
$recentBlogs = [];
try {
    $stmtB = $db->query("SELECT title, slug, summary FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    $recentBlogs = $stmtB->fetchAll();
} catch (Exception $e) {}

// FAQ Schema JSON-LD
$faqSchema = [];
foreach ($svc['faq'] as $f) {
    $faqSchema[] = [
        '@type' => 'Question',
        'name' => $f[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]
    ];
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="tr">
<head>
<meta charset="utf-8"/>
<?php $gaId = $settings['analytics_id'] ?? ''; if ($gaId): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gaId) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag("js",new Date());gtag("config","<?= htmlspecialchars($gaId) ?>")</script>
<?php endif; ?>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= htmlspecialchars($pageTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($metaDesc) ?>"/>
<meta name="keywords" content="<?= htmlspecialchars(strtolower($svc['name']) . ', ankara ' . strtolower($svc['name']) . ', ' . strtolower($svc['name']) . ' fiyat, ' . strtolower($svc['name']) . ' hizmeti, profesyonel ' . strtolower($svc['name'])) ?>"/>
<meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>"/>
<meta property="og:description" content="<?= htmlspecialchars($metaDesc) ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>"/>
<meta property="og:image" content="<?= htmlspecialchars($siteUrl) ?>/images/hero-1.webp"/>
<meta property="og:locale" content="tr_TR"/>
<meta property="og:site_name" content="<?= htmlspecialchars($businessName) ?>"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>"/>
<meta name="twitter:description" content="<?= htmlspecialchars($metaDesc) ?>"/>
<meta name="twitter:image" content="<?= htmlspecialchars($siteUrl) ?>/images/hero-1.webp"/>
<meta name="geo.region" content="TR-06"/>
<meta name="geo.placename" content="Ankara"/>
<link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>"/>
<link rel="alternate" hreflang="tr" href="<?= htmlspecialchars($canonicalUrl) ?>"/>
<link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($canonicalUrl) ?>"/>
<link rel="icon" href="/favicon.svg" type="image/svg+xml"/>
<link rel="stylesheet" href="/css/style.css"/>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"/>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Inter:wght@300;400;500;600;700&display=swap"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<style>
.fonts-loaded .text-gradient-gold{background:linear-gradient(135deg,#4F7DD8,#93B4F0,#93B4F0);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
.btn-shimmer{background:linear-gradient(110deg,#F97316 0%,#FB923C 25%,#FB923C 50%,#FB923C 75%,#F97316 100%);background-size:200% 100%;animation:shimmer 4s linear infinite}
.nav-link{position:relative}
.nav-link::after{content:'';position:absolute;bottom:-4px;left:50%;width:0;height:2px;background:#FB923C;transition:all .3s ease;transform:translateX(-50%)}
.nav-link:hover::after{width:100%}
</style>

<!-- Schema.org -->
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'LocalBusiness',
            'name' => $businessName,
            'description' => $metaDesc,
            'url' => $siteUrl,
            'image' => $siteUrl . '/images/hero-1.webp',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Ankara',
                'addressRegion' => 'Ankara',
                'addressCountry' => 'TR'
            ],
            'priceRange' => '₺₺',
            'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => '4.9', 'bestRating' => '5', 'ratingCount' => '350'],
        ],
        [
            '@type' => 'Service',
            'serviceType' => $svc['name'],
            'provider' => ['@type' => 'LocalBusiness', 'name' => $businessName],
            'areaServed' => ['@type' => 'AdministrativeArea', 'name' => 'Ankara'],
            'description' => $svc['desc'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Ana Sayfa', 'item' => $siteUrl . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $svc['name']],
            ]
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => $faqSchema,
        ]
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>
</head>
<body class="bg-[#FFFFFF] font-body text-gray-800">

<style>
    /* Nav link active */
    .nav-link { position: relative; transition: color 0.3s ease; color: rgba(255,255,255,0.85); }
    .nav-link.active { color: #FB923C !important; font-weight: 600; }
    .nav-link::after { content: ''; position: absolute; bottom: -4px; left: 50%; width: 0; height: 2px; background: #FB923C; transition: all 0.3s ease; transform: translateX(-50%); }
    .nav-link:hover::after, .nav-link.active::after { width: 100%; }
    .nav-link { position: relative; transition: color 0.3s ease; color: rgba(255,255,255,0.85); }
    .nav-link { color: rgba(255,255,255,0.95) !important; text-shadow: 0 1px 4px rgba(0,0,0,0.5); }
    .nav-link { color: rgba(255,255,255,0.85) !important; font-weight: 500; }
    .nav-link { color: white !important; }
    .nav-link.active { color: #FB923C !important; font-weight: 600; }
    .nav-link::after { content: ''; position: absolute; bottom: -4px; left: 50%; width: 0; height: 2px; background: #FB923C; transition: all 0.3s ease; transform: translateX(-50%); }
    .nav-link:hover::after, .nav-link.active::after { width: 100%; }
    .nav-link:hover { color: #FB923C !important; }
    .nav-link.active { color: #FB923C !important; }
    .nav-hero .nav-link { color: rgba(255,255,255,0.95) !important; text-shadow: 0 1px 4px rgba(0,0,0,0.5); }
    .nav-hero .nav-link:hover { color: #FB923C !important; }
    .nav-hero .nav-link.active { color: #FB923C !important; }
    .nav-hero .text-gradient-gold { background: linear-gradient(135deg, #FFFFFF, #FB923C); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .nav-hero .logo-circle { background: linear-gradient(135deg, #FB923C, #F97316) !important; }
    .nav-hero #mobileMenuBtn { color: white !important; }
    .nav-scrolled .nav-link { color: rgba(255,255,255,0.85) !important; font-weight: 500; }
    .nav-scrolled .nav-link:hover { color: #FB923C !important; }
    .nav-scrolled .nav-link.active { color: #FB923C !important; }
    .nav-scrolled .text-gradient-gold { background: linear-gradient(135deg, #FFFFFF, #FB923C); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .nav-scrolled #mobileMenuBtn { color: white !important; }
    #mobileMenu .nav-link { color: white !important; }
    #mobileMenu .nav-link:hover { color: #FB923C !important; }
    #mobileMenu .nav-link.active { color: #FB923C !important; }
    #mobileMenu { max-height: 0; opacity: 0; overflow: hidden; transition: max-height 0.4s ease, opacity 0.3s ease, padding 0.3s ease; padding-top: 0; padding-bottom: 0; }
    #mobileMenu.open { max-height: calc(100vh - 120px); opacity: 1; padding-top: 0.5rem; padding-bottom: 1.5rem; overflow-y: auto; }
    #mobileMenuBtn { color: white !important; }
    #mobileMenuBtn .menu-icon { display: block; transition: opacity 0.2s ease, transform 0.3s ease; }
    #mobileMenuBtn .close-icon { display: none; transition: opacity 0.2s ease, transform 0.3s ease; }
    #mobileMenuBtn.active .menu-icon { display: none; }
    #mobileMenuBtn.active .close-icon { display: block; animation: spinIn 0.3s ease; }
    @keyframes spinIn { from { transform: rotate(-90deg) scale(0.5); opacity: 0; } to { transform: rotate(0) scale(1); opacity: 1; }
</style>
<div id="topBar" class="bg-[#2C4A8A] border-b border-white/10 py-2 text-xs z-40 relative">
    <div class="flex justify-between items-center px-4 sm:px-6 lg:px-10 max-w-full">
        <div class="flex items-center gap-5 text-white/70">
            <a href="tel:05323099671" data-kv-href="phone_raw" data-kv-href-prefix="tel:" class="flex items-center gap-1.5 hover:text-[#FB923C] transition-colors">
                <svg class="text-sm" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0;color:#25D366" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                <span data-kv="phone">0532 309 96 71</span>
            </a>
<span class="hidden lg:flex items-center gap-1.5">
                <svg class="text-sm text-[#FB923C]" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                <span data-kv="working_hours_short">Pzt-Cmt: 08:00 - 20:00</span>
            </span>

        </div>
        <div class="flex items-center justify-center gap-3 text-white/70 sm:w-[160px]" id="kvTopBarSocial">
            <a href="https://www.facebook.com/people/Lidya-Koltuk-Y%C4%B1kama/61577447068676/" target="_blank" rel="noopener noreferrer" class="hover:opacity-80 transition-opacity" title="Facebook"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav id="mainNav" class="sticky w-full z-50 transition-all duration-500"  role="navigation" aria-label="Ana navigasyon">
    <div class="flex justify-between items-center px-4 sm:px-6 lg:px-10 py-4 max-w-full">
        <a href="/" class="group flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary-container flex items-center justify-center shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform duration-300">
                <span class="font-headline italic font-bold text-on-primary text-xl">A</span>
            </div>
            <span class="text-2xl sm:text-3xl font-headline italic font-bold text-gradient-gold tracking-wide">Ankara Koltuk Temizleme</span>
        </a>
        <div class="flex items-center gap-4">
            <div class="hidden lg:flex items-center gap-8">
                <a class="nav-link font-label text-xs uppercase tracking-[0.15em] transition-colors duration-300" href="/#hizmetlerimiz">Hizmetlerimiz</a>
                <a class="nav-link font-label text-xs uppercase tracking-[0.15em] transition-colors duration-300" href="/#surecimiz">Sürecimiz</a>
                <a class="nav-link font-label text-xs uppercase tracking-[0.15em] transition-colors duration-300" href="/#hakkimizda">Hakkımızda</a>
                <a class="nav-link font-label text-xs uppercase tracking-[0.15em] transition-colors duration-300" href="/#galeri">Galeri</a>
                <a class="nav-link font-label text-xs uppercase tracking-[0.15em] transition-colors duration-300" href="/#yorumlar">Yorumlar</a>
                <a class="nav-link font-label text-xs uppercase tracking-[0.15em] transition-colors duration-300" href="/blog/">Blog</a>
                <a class="nav-link font-label text-xs uppercase tracking-[0.15em] transition-colors duration-300" href="/#iletisim">İletişim</a>
            </div>
            <a class="hidden sm:inline-flex btn-shimmer text-white px-6 py-2.5 rounded-full font-medium text-sm tracking-wide shadow-lg shadow-primary/20 hover:scale-105 transition-transform duration-300" href="/#iletisim">
                Ücretsiz Keşif
            </a>
            <button id="mobileMenuBtn" class="lg:hidden text-primary p-2">
                <svg class="text-3xl menu-icon" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
                <svg class="text-3xl close-icon" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden lg:hidden absolute left-0 right-0 top-full w-full px-6 space-y-4 bg-[#2C4A8A]/95 backdrop-blur-xl border-t border-white/10">
        <a class="nav-link block font-label text-sm uppercase tracking-[0.15em] text-white hover:text-[#FB923C] py-2" href="/#hizmetlerimiz">Hizmetlerimiz</a>
        <a class="nav-link block font-label text-sm uppercase tracking-[0.15em] text-white hover:text-[#FB923C] py-2" href="/#surecimiz">Sürecimiz</a>
        <a class="nav-link block font-label text-sm uppercase tracking-[0.15em] text-white hover:text-[#FB923C] py-2" href="/#hakkimizda">Hakkımızda</a>
        <a class="nav-link block font-label text-sm uppercase tracking-[0.15em] text-white hover:text-[#FB923C] py-2" href="/#galeri">Galeri</a>
        <a class="nav-link block font-label text-sm uppercase tracking-[0.15em] text-white hover:text-[#FB923C] py-2" href="/#yorumlar">Yorumlar</a>
        <a class="nav-link block font-label text-sm uppercase tracking-[0.15em] text-white hover:text-[#FB923C] py-2" href="/blog/">Blog</a>
        <a class="nav-link block font-label text-sm uppercase tracking-[0.15em] text-white hover:text-[#FB923C] py-2" href="/#iletisim">İletişim</a>
        <a class="block btn-shimmer text-white px-6 py-3 rounded-full font-medium text-sm tracking-wide text-center sm:hidden" href="/#iletisim">Ücretsiz Keşif</a>
    </div>
</nav>

<!-- Hero Slider -->

<!-- Hero -->
<header class="relative bg-gradient-to-br from-[#2C4A8A] via-[#4F7DD8] to-[#93B4F0] overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-96 h-96 bg-[#FB923C] rounded-full blur-[120px]"></div>
        <div class="absolute bottom-10 left-10 w-64 h-64 bg-[#93B4F0] rounded-full blur-[100px]"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <nav class="flex justify-center items-center gap-2 text-white/70 text-xs mb-8" aria-label="Breadcrumb">
            <a href="/" class="hover:text-[#FB923C] transition-colors">Ana Sayfa</a>
            <svg class="text-xs" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
            <span class="text-[#FB923C]"><?= htmlspecialchars($svc['name']) ?></span>
        </nav>
        <h1 class="font-headline text-4xl sm:text-5xl lg:text-6xl font-light text-white mb-6 tracking-wide">
            Profesyonel<br/>
            <span class="text-[#FB923C] italic"><?= htmlspecialchars($svc['name']) ?> Hizmeti</span>
        </h1>
        <p class="text-white/70 text-lg sm:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            <?= htmlspecialchars($svc['intro']) ?> Hizmet bölgelerimizde profesyonel ekipmanlarla yerinde hizmet sunuyoruz.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/#iletisim" class="btn-shimmer text-white px-10 py-4 rounded-full font-semibold text-base shadow-xl hover:scale-105 transition-transform">Ücretsiz Keşif Al</a>
            <?php if ($phoneRaw): ?>
            <a href="tel:<?= htmlspecialchars($phoneRaw) ?>" class="border border-white/30 text-white px-10 py-4 rounded-full font-semibold text-base hover:bg-white/10 transition-all flex items-center gap-2">
                <?= svgIcon('call', '', '1em') ?> Hemen Ara
            </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- İçerik -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">

    <!-- Hizmet Açıklaması -->
    <section class="mb-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-[#93B4F0] font-semibold text-xs uppercase tracking-[0.2em] block mb-4">Hizmet Detayı</span>
                <h2 class="font-headline text-3xl sm:text-4xl text-gray-900 mb-6">Profesyonel <span class="text-gradient-gold italic"><?= htmlspecialchars($svc['name']) ?></span></h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-6"><?= htmlspecialchars($svc['intro']) ?></p>
                <p class="text-gray-600 leading-relaxed mb-8"><?= htmlspecialchars($svc['desc']) ?></p>
                <div class="grid sm:grid-cols-2 gap-3">
                    <?php foreach ($svc['features'] as $feat): ?>
                    <div class="flex items-center gap-3">
                        <?= svgIcon('verified', 'text-[#93B4F0] text-lg') ?>
                        <span class="text-gray-700 font-medium text-sm"><?= htmlspecialchars($feat) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-br from-[#93B4F0]/20 to-[#4F7DD8]/10">
                    <img src="/images/hero-1.webp" alt="<?= htmlspecialchars($svc['name'] . ' hizmeti') ?>" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-5 rounded-2xl shadow-xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#FB923C] to-[#F97316] flex items-center justify-center">
                            <?= svgIcon('star', 'text-white text-2xl') ?>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">4.9</div>
                            <div class="text-xs text-gray-500">350+ Değerlendirme</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fiyat Kalemleri -->
    <?php if (!empty($serviceItems)): ?>
    <section class="mb-20">
        <div class="text-center mb-12">
            <h2 class="font-headline text-3xl sm:text-4xl text-gray-900"><?= htmlspecialchars($svc['name']) ?> <span class="text-gradient-gold italic">Fiyatlarımız</span></h2>
            <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Güncel fiyat listemiz aşağıda yer almaktadır. Detaylı bilgi için bizi arayabilirsiniz.</p>
        </div>
        <div class="max-w-2xl mx-auto bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-[#2C4A8A] to-[#4F7DD8] px-8 py-4 flex justify-between items-center">
                <span class="text-white font-semibold text-sm">Hizmet</span>
                <span class="text-[#FB923C] font-semibold text-sm">Fiyat</span>
            </div>
            <?php foreach ($serviceItems as $idx => $item):
                $price = $item['price'] ?? '';
                if ($price && strpos($price, '₺') === false) $price = '₺' . $price;
                $isLast = $idx === count($serviceItems) - 1;
            ?>
            <div class="flex justify-between items-center px-8 py-4 <?= $isLast ? '' : 'border-b border-gray-50' ?> hover:bg-gray-50/50 transition-colors">
                <div>
                    <span class="text-gray-800 font-medium text-sm"><?= htmlspecialchars($item['name']) ?></span>
                    <?php if (!empty($item['description'])): ?>
                    <span class="block text-gray-400 text-xs mt-0.5"><?= htmlspecialchars($item['description']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="text-right ml-4 flex-shrink-0">
                    <span class="text-[#4F7DD8] font-bold"><?= htmlspecialchars($price) ?></span>
                    <?php if (!empty($item['unit'])): ?>
                    <span class="block text-[#93B4F0] text-xs opacity-70"><?= htmlspecialchars($item['unit']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="px-8 py-4 bg-gray-50/50 text-center">
                <p class="text-gray-400 text-xs">Fiyatlar KDV dahildir. Detaylı bilgi için bizi arayınız.</p>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Neden Bizi Seçmelisiniz -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <h2 class="font-headline text-3xl sm:text-4xl text-gray-900">Neden <span class="text-gradient-gold italic">Bizi Seçmelisiniz?</span></h2>
            <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Yılların deneyimiyle fark yaratan hizmet kalitemiz</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $reasons = [
                ['workspace_premium', 'Profesyonel Ekipman', 'En son teknoloji yıkama makineleri ve çevre dostu temizlik ürünleri kullanıyoruz. Tüm ekipmanlarımız Avrupa standartlarında hijyen sertifikalıdır.'],
                ['local_shipping', 'Yerinde Hizmet', 'Hizmet bölgelerimizde adresinize gelerek yerinde profesyonel temizlik yapıyoruz. Randevulu sistem ile size uygun saatte geliyoruz.'],
                ['verified', 'Memnuniyet Garantisi', 'Yıkama sonrası memnun kalmadığınız durumda ücretsiz tekrar yıkama yapıyoruz. Güvenilir ve tercih edilen bir temizlik hizmetiyiz.'],
                ['payments', 'Uygun Fiyat Garantisi', 'En rekabetçi fiyatları sunuyoruz. Ücretsiz keşif ile peşin fiyat teklifi alın, sürpriz ücretle karşılaşmayın.'],
                ['schedule', 'Hızlı Hizmet Süreci', 'Temizlik işleminiz en kısa sürede tamamlanır. Randevu öncesinde sizinle iletişime geçerek uygun zamanı belirliyoruz.'],
                ['eco', 'Çevre Dostu Yıkama', 'Biyolojik olarak parçalanabilen, alerjenleri yok eden özel temizlik solüsyonları kullanıyoruz. Bebek ve evcil hayvan dostu formüllerle güvenle yıkama.'],
            ];
            foreach ($reasons as $r):
            ?>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-[#93B4F0]/10 flex items-center justify-center mb-4">
                    <?= svgIcon($r[0], 'text-[#93B4F0]', '1.25rem') ?>
                </div>
                <h3 class="font-headline text-lg text-gray-900 mb-2"><?= htmlspecialchars($r[1]) ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?= htmlspecialchars($r[2]) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Süreç -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <h2 class="font-headline text-3xl sm:text-4xl text-gray-900"><?= htmlspecialchars($svc['name']) ?> <span class="text-gradient-gold italic">Nasıl Yapılır?</span></h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $steps = [
                ['call', 'Bizi Arayın', 'Ücretsiz keşif talebi oluşturun veya bizi arayın. Size en uygun randevuyu belirleyelim.'],
                ['cleaning_services', 'Keşif & İnceleme', 'Uzman ekibimiz adresinize gelerek mobilyalarınızı inceler ve kumaş tipine uygun yöntem belirler.'],
                ['dry_cleaning', 'Yerinde Temizlik', 'Profesyonel ekipmanlarla yerinde derinlemesine temizlik yapılır. Leke, kir ve bakteriler giderilir.'],
                ['verified', 'Kontrol & Garanti', 'Temizlik sonrası kontrol yapılır. Memnun kalmadığınız durumda ücretsiz tekrar temizlik garantisi.'],
            ];
            foreach ($steps as $i => $step):
            ?>
            <div class="text-center p-6 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#93B4F0] to-[#4F7DD8] flex items-center justify-center mx-auto mb-4">
                    <?= svgIcon($step[0], 'text-white', '1.5rem') ?>
                </div>
                <div class="text-[#FB923C] font-bold text-sm mb-2"><?= $i + 1 ?>. Adım</div>
                <h3 class="font-headline text-lg text-gray-900 mb-2"><?= htmlspecialchars($step[1]) ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?= htmlspecialchars($step[2]) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SSS (FAQ) -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <h2 class="font-headline text-3xl sm:text-4xl text-gray-900"><?= htmlspecialchars($svc['name']) ?> <span class="text-gradient-gold italic">Sık Sorulan Sorular</span></h2>
        </div>
        <div class="max-w-3xl mx-auto space-y-4">
            <?php foreach ($svc['faq'] as $f): ?>
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <summary class="flex items-center justify-between p-6 cursor-pointer hover:bg-gray-50 transition-colors">
                    <h3 class="font-semibold text-gray-900 text-base pr-4"><?= htmlspecialchars($f[0]) ?></h3>
                    <svg class="text-[#93B4F0] group-open:rotate-180 transition-transform duration-300 flex-shrink-0" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg>
                </summary>
                <div class="px-6 pb-6 text-gray-600 leading-relaxed"><?= htmlspecialchars($f[1]) ?></div>
            </details>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Diğer Hizmetler (Internal Linking) -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <h2 class="font-headline text-3xl text-gray-900">Diğer <span class="text-gradient-gold italic">Hizmetlerimiz</span></h2>
            <p class="text-gray-500 mt-3">Profesyonel temizlik hizmetlerimizi keşfedin</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($otherServices as $os): ?>
            <a href="/<?= htmlspecialchars($os['slug']) ?>" class="bg-white p-5 rounded-xl border border-gray-100 text-center hover:border-[#93B4F0]/30 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-[#93B4F0]/10 flex items-center justify-center mx-auto mb-3">
                    <?= svgIcon($os['icon'], 'text-[#93B4F0]', '1.25rem') ?>
                </div>
                <span class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($os['name']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Blog Yazıları -->
    <?php if (!empty($recentBlogs)): ?>
    <section class="mb-16">
        <div class="text-center mb-12">
            <h2 class="font-headline text-3xl text-gray-900">Faydalı <span class="text-gradient-gold italic">Blog Yazıları</span></h2>
        </div>
        <div class="grid sm:grid-cols-3 gap-6">
            <?php foreach ($recentBlogs as $rb): ?>
            <a href="/blog/<?= htmlspecialchars($rb['slug']) ?>" class="bg-white p-6 rounded-2xl border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 block group">
                <h3 class="font-headline text-lg text-gray-900 mb-3 group-hover:text-[#93B4F0] transition-colors line-clamp-2"><?= htmlspecialchars($rb['title']) ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-4"><?= htmlspecialchars($rb['summary'] ?? '') ?></p>
                <span class="text-[#93B4F0] text-sm font-semibold flex items-center gap-1">Devamını Oku <svg class="text-sm" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg></span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<!-- CTA -->
<section class="bg-gradient-to-r from-[#2C4A8A] via-[#4F7DD8] to-[#93B4F0] py-20 text-center px-4">
    <h2 class="font-headline text-3xl sm:text-4xl text-white mb-6"><?= htmlspecialchars($svc['name']) ?> İçin <span class="text-[#FB923C] italic">Hemen Arayın</span></h2>
    <p class="text-white/70 text-lg mb-10 max-w-2xl mx-auto">Ücretsiz keşif ve fiyat teklifi için bizi arayın. Hizmet bölgelerimizde profesyonel yerinde temizlik hizmeti sunuyoruz.</p>
    <div class="flex flex-wrap justify-center gap-4">
        <a href="/#iletisim" class="bg-[#FB923C] text-white px-10 py-4 rounded-full font-bold hover:bg-[#F97316] hover:scale-105 transition-all shadow-xl">Ücretsiz Keşif Al</a>
        <?php if ($phoneRaw): ?>
        <a href="tel:<?= htmlspecialchars($phoneRaw) ?>" class="border border-white/30 text-white px-10 py-4 rounded-full font-bold hover:bg-white/10 transition-all flex items-center gap-2">
            <?= svgIcon('call', '', '1em') ?> <?= htmlspecialchars($phone) ?>
        </a>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="bg-[#2C4A8A] pt-16 sm:pt-24 pb-12 px-4 sm:px-6 lg:px-8 border-t border-white/10" role="contentinfo">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12">
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#FB923C] to-[#F97316] flex items-center justify-center">
                    <span class="font-headline italic font-bold text-white text-base">A</span>
                </div>
                <span class="text-2xl font-headline italic font-bold text-white" data-kv="business_name">Ankara Koltuk Temizleme</span>
            </div>
            <p class="text-sm text-white/70 mb-8 leading-relaxed">Ankara Koltuk Temizleme olarak, koltuklarınızı adresinizde, profesyonel ekip ve ekipmanlarla en hijyenik şekilde temizliyoruz.</p>
            <div class="flex gap-4" id="kvSocialLinks"></div>
        </div>
        <div>
            <h3 class="text-xs font-label uppercase tracking-widest text-[#FB923C] mb-6">Hızlı Menü</h3>
            <ul class="space-y-4">
                <li><a class="text-white/70 hover:text-[#FB923C] transition-colors text-sm" href="/#hizmetlerimiz">Hizmetlerimiz</a></li>
                <li><a class="text-white/70 hover:text-[#FB923C] transition-colors text-sm" href="/#surecimiz">Sürecimiz</a></li>
                <li><a class="text-white/70 hover:text-[#FB923C] transition-colors text-sm" href="/#hakkimizda">Hakkımızda</a></li>
                <li><a class="text-white/70 hover:text-[#FB923C] transition-colors text-sm" href="/galeri">Galeri</a></li>
                <li><a class="text-white/70 hover:text-[#FB923C] transition-colors text-sm" href="/blog/">Blog</a></li>
                <li><a class="text-white/70 hover:text-[#FB923C] transition-colors text-sm" href="/#iletisim">İletişim</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-xs font-label uppercase tracking-widest text-[#FB923C] mb-6">İletişim</h3>
            <ul class="space-y-4">
                <li><span class="text-white/70 text-sm" data-kv="phone">0532 309 96 71</span></li>
                <li><span class="text-white/70 text-sm" data-kv="phone2"></span></li>
                <li><span class="text-white/70 text-sm" data-kv="email">lidyakoltuk1@gmail.com</span></li>
                <li><span class="text-white/70 text-sm">Oğuzlar Mahallesi 1574. Sokak No:2 Etimesgut/Ankara</span></li>
            </ul>
        </div>
        <div>
            <h3 class="text-xs font-label uppercase tracking-widest text-[#FB923C] mb-6">Bülten</h3>
            <p class="text-sm text-white/70 mb-4">Bakım ipuçları ve kampanyalar için abone olun.</p>
            <div class="relative">
                <input class="w-full bg-white/10 border border-white/20 rounded-xl py-3 px-5 text-sm text-white placeholder-white/30 focus:ring-1 focus:ring-[#FB923C] transition-all" placeholder="E-posta adresiniz" type="email"/>
                <button class="absolute right-2 top-1.5 bg-gradient-to-r from-[#F97316] to-[#FB923C] text-white p-1.5 rounded-lg"><svg class="text-sm" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg></button>
            </div>
            <div class="mt-8">
                <span class="text-[10px] text-white/25 uppercase tracking-widest">&copy; 2026 Ankara Koltuk Temizleme. Tüm Hakları Saklıdır.</span>
            </div>
        </div>
    </div>
    <!-- Hizmet Bölgeleri — SEO Internal Links -->
    <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-white/10">
        <h3 class="text-xs font-label uppercase tracking-widest text-[#FB923C] mb-4">Hizmet Bölgelerimiz</h3>
        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-white/60">
            <?php foreach ($services as $sSlug => $sData): foreach ($locations as $lSlug => $lData): ?>
                <a class="hover:text-[#FB923C] transition-colors" href="/<?= htmlspecialchars($lSlug) ?>-<?= htmlspecialchars($sSlug) ?>"><?= htmlspecialchars($lData['name']) ?> <?= htmlspecialchars($sData['name']) ?></a>
            <?php endforeach; endforeach; ?>
        </div>
    </div>
    <div class="max-w-7xl mx-auto mt-8 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] text-white/25 uppercase tracking-widest">
        <span>Designed with Precision</span>
        <div class="flex gap-6">
            <a class="hover:text-[#FB923C] transition-colors" href="/gizlilik-politikasi">Gizlilik Politikası</a>
            <a class="hover:text-[#FB923C] transition-colors" href="/kvkk">KVKK Aydınlatma</a>
            <a class="hover:text-[#FB923C] transition-colors" href="/kullanim-sartlari">Kullanım Şartları</a>
        </div>
    </div>
</footer>

<!-- WhatsApp Template Popup -->
<div id="waPopup" class="fixed inset-0 z-[200] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('waPopup').classList.add('hidden')"></div>
    <div class="absolute bottom-24 right-6 md:right-8 w-72 bg-white rounded-2xl shadow-2xl overflow-hidden" style="animation: slideIn 0.3s ease">
        <div class="bg-gradient-to-r from-[#25D366] to-[#128C7E] px-4 py-3 flex items-center justify-between">
            <span class="text-white font-bold text-sm">WhatsApp ile Yazın</span>
            <button onclick="document.getElementById('waPopup').classList.add('hidden')" class="text-white/80 hover:text-white">
                <svg class="text-lg" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
        <div id="waPopupList" class="p-3 space-y-2 max-h-60 overflow-y-auto"></div>
    </div>
</div>

<!-- Instagram Button -->
<a href="https://www.instagram.com/p/DWYO8VLjC8Y/?igsh=MWRydDR4Z2syeWNyOA%3D%3D" target="_blank" rel="noopener noreferrer" id="instagramButton" title="Instagram" class="fixed right-6 md:right-8 w-12 h-12 rounded-full text-white hover:scale-110 transition-all duration-500 ease-out z-[100] flex items-center justify-center cursor-pointer group" style="bottom:8.5rem;background:radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);box-shadow:0 0 24px rgba(214,36,159,0.45);">
    <svg class="text-2xl" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
    <div class="absolute right-14 text-white px-4 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold text-sm" style="background:linear-gradient(135deg,#d6249f,#285AEB)">Instagram</div>
</a>
<!-- Call Button -->
<a href="tel:<?= htmlspecialchars($phoneRaw) ?>" data-kv-href="phone_raw" data-kv-href-prefix="tel:" style="background:linear-gradient(135deg,#4CAF50,#2E7D32);box-shadow:0 0 24px rgba(76,175,80,0.45);" class="fixed bottom-20 right-6 md:bottom-22 md:right-8 text-white w-12 h-12 rounded-full hover:scale-110 transition-all duration-500 ease-out z-[100] flex items-center justify-center cursor-pointer group" id="callButton">
    <svg class="text-on-primary text-2xl" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
    <div class="absolute right-14 text-white px-4 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold text-sm" style="background:#2E7D32">Hemen Ara</div>
</a>

<!-- WhatsApp Button -->
<div class="fixed bottom-6 right-6 md:bottom-8 md:right-8 bg-gradient-to-br from-[#25D366] to-[#128C7E] text-white w-12 h-12 rounded-full shadow-[0_0_24px_rgba(37,211,102,0.4)] hover:scale-110 hover:shadow-[0_0_32px_rgba(37,211,102,0.5)] transition-all duration-500 ease-out z-[100] flex items-center justify-center cursor-pointer group wa-bounce" id="waButton" data-kv-wa="whatsapp_number" onclick="toggleWaPopup()">
    <svg class="text-on-primary text-2xl" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.654-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    <div class="absolute right-14 bg-[#128C7E] text-white px-4 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold text-sm">WhatsApp</div>
</div>

<!-- Scroll to Top -->
<button id="scrollTopBtn" class="fixed bottom-6 left-6 md:bottom-8 md:left-8 bg-[#4F7DD8] text-white w-12 h-12 rounded-full shadow-lg shadow-[#4F7DD8]/20 hover:scale-105 hover:shadow-[#4F7DD8]/40 transition-all duration-500 ease-out z-[100] flex items-center justify-center" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Yukarı çık">
    <svg class="text-2xl" style="width:1em;height:1em;vertical-align:middle;flex-shrink:0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8z"/></svg>
</button>

<!-- Ziyaretci Takip -->
<img src="/api/track.php?pixel&amp;page=/<?= htmlspecialchars($svcSlug) ?>" alt="" width="1" height="1" style="position:absolute;left:-9999px" loading="eager"/>

<script src="/js/main.js" defer></script>
<script>
function toggleWaPopup(){
    var p=document.getElementById('waPopup');
    p.classList.toggle('hidden');
}
</script>
</body>
</html>
