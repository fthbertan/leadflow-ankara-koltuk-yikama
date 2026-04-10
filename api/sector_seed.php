<?php
// Koltuk Yıkama — Sektöre Özel Seed Data
// Bu dosya install.php tarafından include edilir
// $db, $messages, $errors değişkenleri zaten tanımlıdır

// ══════════════════════════════════════
// VARSAYILAN HİZMETLER
// ══════════════════════════════════════
try {
    $forceServices = isset($_GET['force_services']);
    $count = $db->query('SELECT COUNT(*) FROM services')->fetchColumn();
    if ($forceServices && (int)$count > 0) {
        $db->exec('DELETE FROM services');
        $messages[] = 'Eski hizmetler silindi (force reset).';
        $count = 0;
    }
    if ($forceServices) {
        try { $db->exec('DELETE FROM service_items'); } catch (Exception $e) {}
    }
    if ((int)$count === 0) {
        $defaultServices = [
            ['Koltuk Yıkama', 'Yerinde profesyonel koltuk yıkama ile kumaşlarınızı canlandırıyor, lekeleri ve kötü kokuları gideriyoruz.', 'weekend', '₺600\'den'],
            ['Sandalye & Berjer Yıkama', 'Yemek odası sandalyeleri, berjer ve tekli koltuklar için profesyonel kumaş temizliği.', 'chair', '₺75\'den'],
            ['Çekyat & Kanepe Yıkama', 'Açılır kapanır çekyat ve büyük kanepeler için derinlemesine yerinde yıkama.', 'king_bed', '₺550\'den'],
            ['Yatak Yıkama', 'Yatak temizliği, dezenfeksiyon ve akar arındırma ile sağlıklı uyku ortamı.', 'bed', '₺500\'den'],
            ['Halı Yıkama (Ek Hizmet)', 'Koltuk temizliğinizle birlikte halılarınızı da yıkıyoruz.', 'dry_cleaning', '₺80/m²\'den'],
            ['Perde Yıkama', 'Stor, zebra, tül ve fon perdeler için söküm-yıkama-takım dahil profesyonel hizmet.', 'curtains', '₺100\'den'],
            ['Leke Çıkarma & Koku Giderme', 'Kahve, kan, mürekkep, evcil hayvan kokusu ve idrar lekelerine özel formül.', 'auto_awesome', 'Ücretsiz Analiz'],
            ['Anti-Bakteriyel Dezenfeksiyon', 'Koltuk, yatak ve kumaş yüzeylerde UV + ozon destekli hijyenik dezenfeksiyon.', 'sanitizer', '₺250\'den'],
            ['Ücretsiz Yerinde Servis', 'Ankara genelinde ücretsiz keşif ve yerinde profesyonel temizlik. Aynı gün kuruma.', 'home_repair_service', 'Ücretsiz'],
        ];
        $stmt = $db->prepare('INSERT INTO services (title, description, icon, price, sort_order) VALUES (:t, :d, :i, :p, :s)');
        foreach ($defaultServices as $i => $svc) {
            $stmt->execute([':t' => $svc[0], ':d' => $svc[1], ':i' => $svc[2], ':p' => $svc[3], ':s' => $i]);
        }

        // Fiyat kalemlerini ekle (service_items tablosu varsa)
        try {
            $db->query('SELECT 1 FROM service_items LIMIT 1');
            $itemStmt = $db->prepare('INSERT INTO service_items (service_id, name, description, price, unit, sort_order) VALUES (:sid, :n, :d, :p, :u, :s)');

            $koltukId = $db->query("SELECT id FROM services WHERE title='Koltuk Yıkama' LIMIT 1")->fetchColumn();
            if ($koltukId) {
                $koltukItems = [
                    ['3\'lü Koltuk', 'Üçlü oturma grubu, büyük kanepe', '600', 'adet'],
                    ['2\'li Koltuk', 'İkili oturma grubu', '450', 'adet'],
                    ['Tekli Koltuk / Berjer', 'Tek kişilik koltuk, berjer, salıncak', '300', 'adet'],
                    ['Köşe Takımı (L)', 'L şeklinde köşe takımı', '900', 'adet'],
                    ['U Köşe Takımı', 'U şeklinde geniş köşe takımı', '1200', 'adet'],
                    ['Puf / Tabure', 'Küçük puf, ayak taburesi', '150', 'adet'],
                    ['Deri Koltuk (Tekli)', 'Deri / suni deri tekli koltuk', '400', 'adet'],
                    ['Deri Koltuk (3\'lü)', 'Deri / suni deri üçlü koltuk', '750', 'adet'],
                    ['Kadife / Şönil Koltuk', 'Hassas kumaş özel işlem', '+%20', 'fark'],
                ];
                foreach ($koltukItems as $ii => $item) {
                    $itemStmt->execute([':sid' => $koltukId, ':n' => $item[0], ':d' => $item[1], ':p' => $item[2], ':u' => $item[3], ':s' => $ii]);
                }
            }

            $sandalyeId = $db->query("SELECT id FROM services WHERE title='Sandalye & Berjer Yıkama' LIMIT 1")->fetchColumn();
            if ($sandalyeId) {
                $sandalyeItems = [
                    ['Yemek Sandalyesi (Kumaş)', '', '75', 'adet'],
                    ['Ofis Sandalyesi', '', '150', 'adet'],
                    ['Bar Taburesi', '', '100', 'adet'],
                ];
                foreach ($sandalyeItems as $ii => $item) {
                    $itemStmt->execute([':sid' => $sandalyeId, ':n' => $item[0], ':d' => $item[1], ':p' => $item[2], ':u' => $item[3], ':s' => $ii]);
                }
            }

            $cekyatId = $db->query("SELECT id FROM services WHERE title='Çekyat & Kanepe Yıkama' LIMIT 1")->fetchColumn();
            if ($cekyatId) {
                $cekyatItems = [
                    ['Çekyat (Tek Kişilik)', '', '550', 'adet'],
                    ['Çekyat (Çift Kişilik)', '', '750', 'adet'],
                    ['Büyük Kanepe (3+)', '', '850', 'adet'],
                ];
                foreach ($cekyatItems as $ii => $item) {
                    $itemStmt->execute([':sid' => $cekyatId, ':n' => $item[0], ':d' => $item[1], ':p' => $item[2], ':u' => $item[3], ':s' => $ii]);
                }
            }

            $yatakId = $db->query("SELECT id FROM services WHERE title='Yatak Yıkama' LIMIT 1")->fetchColumn();
            if ($yatakId) {
                $yatakItems = [
                    ['Tek Kişilik Yatak', '', '500', 'adet'],
                    ['Çift Kişilik Yatak', '', '700', 'adet'],
                    ['King Size Yatak', '', '850', 'adet'],
                ];
                foreach ($yatakItems as $ii => $item) {
                    $itemStmt->execute([':sid' => $yatakId, ':n' => $item[0], ':d' => $item[1], ':p' => $item[2], ':u' => $item[3], ':s' => $ii]);
                }
            }

            $haliId = $db->query("SELECT id FROM services WHERE title='Halı Yıkama (Ek Hizmet)' LIMIT 1")->fetchColumn();
            if ($haliId) {
                $haliItems = [
                    ['Makine Halısı', '', '80', 'm²'],
                    ['Yün / El Dokuma Halı', '', '140', 'm²'],
                    ['Shaggy / Mega Halı', '', '90', 'm²'],
                ];
                foreach ($haliItems as $ii => $item) {
                    $itemStmt->execute([':sid' => $haliId, ':n' => $item[0], ':d' => $item[1], ':p' => $item[2], ':u' => $item[3], ':s' => $ii]);
                }
            }

            $perdeId = $db->query("SELECT id FROM services WHERE title='Perde Yıkama' LIMIT 1")->fetchColumn();
            if ($perdeId) {
                $itemStmt->execute([':sid' => $perdeId, ':n' => 'Stor / Zebra Perde', ':d' => '', ':p' => '100', ':u' => 'adet', ':s' => 0]);
                $itemStmt->execute([':sid' => $perdeId, ':n' => 'Tül / Fon Perde', ':d' => 'Söküm-yıkama-takım dahil', ':p' => '150', ':u' => 'adet', ':s' => 1]);
            }

            $messages[] = 'Hizmet fiyat kalemleri yüklendi.';
        } catch (Exception $e) {
            // service_items tablosu henüz yoksa sorun değil
        }

        $messages[] = 'Varsayılan hizmetler yüklendi (' . count($defaultServices) . ' hizmet).';
    } else {
        $messages[] = 'Hizmetler zaten mevcut (' . $count . ' kayıt), atlanıyor.';
    }
} catch (Exception $e) {
    $errors[] = 'Hizmet yükleme hatası: ' . $e->getMessage();
}

// ══════════════════════════════════════
// VARSAYILAN GALERİ GÖRSELLERİ
// ══════════════════════════════════════
try {
    $forceGallery = isset($_GET['force_gallery']);
    $count = $db->query("SELECT COUNT(*) FROM gallery WHERE category = 'gallery'")->fetchColumn();
    if ($forceGallery && (int)$count > 0) {
        $db->exec("DELETE FROM gallery WHERE category = 'gallery'");
        $messages[] = 'Eski galeri görselleri silindi (force reset).';
        $count = 0;
    }
    if ((int)$count === 0) {
        $defaultGallery = [
            ['images/gallery-1.webp', 'gallery', 'Profesyonel yerinde koltuk yıkama hizmeti', 0],
            ['images/gallery-2.webp', 'gallery', 'Kanepe temizliği öncesi ve sonrası', 1],
            ['images/gallery-3.webp', 'gallery', 'Köşe takımı yıkama işlemi', 2],
            ['images/gallery-4.webp', 'gallery', 'Deri koltuk bakımı ve temizliği', 3],
            ['images/gallery-5.webp', 'gallery', 'Yatak dezenfeksiyon hizmeti', 4],
            ['images/gallery-6.webp', 'gallery', 'Profesyonel temizlik ekibimiz', 5],
        ];
        $stmt = $db->prepare('INSERT INTO gallery (filename, category, alt_text, sort_order) VALUES (:f, :c, :a, :s)');
        foreach ($defaultGallery as $g) {
            $stmt->execute([':f' => $g[0], ':c' => $g[1], ':a' => $g[2], ':s' => $g[3]]);
        }
        $messages[] = 'Varsayılan galeri görselleri yüklendi (' . count($defaultGallery) . ' görsel).';
    } else {
        $messages[] = 'Galeri görselleri zaten mevcut (' . $count . ' kayıt), atlanıyor.';
    }
} catch (Exception $e) {
    $errors[] = 'Galeri yükleme hatası: ' . $e->getMessage();
}

// ══════════════════════════════════════
// VARSAYILAN YORUMLAR
// ══════════════════════════════════════
try {
    $count = $db->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
    if ((int)$count === 0) {
        $defaultTestimonials = [
            ['Ayşe Yılmaz', '2 yıldır müşterimiz', 5, 'Ankara Koltuk Temizleme ekibi evime gelip koltuklarımı yerinde yıkadı. 3 saat sonra kuruyup kullanmaya başladık. Lekeler tamamen çıktı, kanepelerim yepyeni gibi oldu. Kesinlikle tavsiye ederim!'],
            ['Mehmet Demir', '1 yıldır müşterimiz', 5, 'Köpeğimizin tüyleri ve kokusu koltuklarımıza işlemişti. Anti-bakteriyel temizlik sonrası evdeki koku tamamen gitti, alerji şikayetlerimiz azaldı. Profesyonel ve titiz çalıştılar.'],
            ['Elif Can', 'Yeni müşterimiz', 5, 'Kadife koltuk takımım için temizlik yaptırmaya çekiniyordum, kumaşa zarar gelir diye. Ankara Koltuk Temizleme ekibi özel ürünlerle hassasça temizledi, hiçbir leke iz bırakmadı. Çocuklarım için güvenli olması da çok önemliydi.'],
        ];
        $stmt = $db->prepare('INSERT INTO testimonials (name, role, rating, text, sort_order) VALUES (:n, :r, :rt, :t, :s)');
        foreach ($defaultTestimonials as $i => $t) {
            $stmt->execute([':n' => $t[0], ':r' => $t[1], ':rt' => $t[2], ':t' => $t[3], ':s' => $i]);
        }
        $messages[] = 'Varsayılan yorumlar yüklendi (' . count($defaultTestimonials) . ' yorum).';
    } else {
        $messages[] = 'Yorumlar zaten mevcut (' . $count . ' kayıt), atlanıyor.';
    }
} catch (Exception $e) {
    $errors[] = 'Yorum yükleme hatası: ' . $e->getMessage();
}
