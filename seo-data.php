<?php
// Koltuk Yıkama — SEO Veri Dosyası
// seo-location.php ve seo-service.php tarafından include edilir
// Yeni sektörler için bu dosya Gemini ile otomatik üretilir

// İlçe/bölge veritabanı
$locations = [
    'eryaman'     => [
        'name' => 'Eryaman',
        'desc' => 'Eryaman ve çevresinde',
        'long_desc' => 'Ankara\'nın en hızlı gelişen ve modern konut projeleriyle öne çıkan bölgesi Eryaman\'da, site ve apartman yaşamına uygun, hızlı ve etkili koltuk yıkama çözümleri sunuyoruz. Eryaman 1-8. etap genelinde yerinde profesyonel hizmet veriyoruz.',
        'neighborhoods' => 'Göksu, Şeker, Tunahan, Devlet Mahallesi, Eryaman 1-8. Etap, Güzelkent',
        'landmarks' => 'Göksu Parkı, Metromall AVM ve Eryaman Stadyumu çevresinden tüm Eryaman\'a aynı gün randevu ile koltuk yıkama hizmeti veriyoruz.',
    ],
    'sincan'      => [
        'name' => 'Sincan',
        'desc' => 'Sincan ve çevresinde',
        'long_desc' => 'Ankara\'nın batı yakasındaki en büyük ilçelerinden Sincan, hem merkezi hem de yeni gelişen yerleşim alanlarıyla dikkat çekmektedir. İşletmemizin de bulunduğu Sincan ve tüm mahallelerinde profesyonel yerinde koltuk yıkama hizmeti veriyoruz.',
        'neighborhoods' => 'Oğuzlar, 1574. Sokak, Etimesgut, Eryaman, Elvankent',
        'landmarks' => 'Sincan merkez, Sincan OSB ve Fatih Metro İstasyonu çevresindeki tüm bölgelere yerinde koltuk yıkama hizmeti sunuyoruz.',
    ],
    'baglica'     => [
        'name' => 'Bağlıca',
        'desc' => 'Bağlıca bölgesinde',
        'long_desc' => 'Etimesgut\'un en prestijli ve modern yaşam alanlarından biri olan Bağlıca, villa ve lüks konutlarıyla dikkat çekmektedir. Özel kumaş türleri ve hassas döşemeler için uzmanlaşmış ekibimizle Bağlıca\'da yerinde koltuk yıkama hizmeti veriyoruz.',
        'neighborhoods' => 'Bağlıca Mahallesi, Bağlıca Bulvarı çevresi, Yaşamkent bağlantı yolları, İncek bağlantı yolları',
        'landmarks' => 'Bağlıca Köprülü Kavşağı ve Başkent Üniversitesi çevresi gibi önemli lokasyonlara yakın konumda, kaliteli yerinde koltuk yıkama hizmeti sunuyoruz.',
    ],
    'batikent'    => [
        'name' => 'Batıkent',
        'desc' => 'Batıkent ve çevresinde',
        'long_desc' => 'Yenimahalle ilçesine bağlı Batıkent, Ankara\'nın en köklü ve yerleşik toplu konut bölgelerindendir. Kardelen, Çakırlar gibi önemli mahalleleriyle Batıkent\'in tüm site ve apartmanlarına hızlı ve güvenilir yerinde koltuk yıkama hizmeti sağlıyoruz.',
        'neighborhoods' => 'Kardelen, Çakırlar, Turgut Özal, Uğur Mumcu, Kentkoop, Ergazi, Yeni Batı',
        'landmarks' => 'Batıkent Metro ve Atlantis AVM çevresinden tüm Batıkent\'e servis imkanı sunmaktayız.',
    ],
    'etimesgut'   => [
        'name' => 'Etimesgut',
        'desc' => 'Etimesgut ve çevresinde',
        'long_desc' => 'Etimesgut, Ankara\'nın hızla büyüyen ve gelişen ilçelerinden biridir. Hem merkezi konumu hem de yeni yerleşim projeleriyle dikkat çeken Etimesgut\'ta, tüm mahalle ve sitelere profesyonel yerinde koltuk yıkama servisimiz bulunmaktadır.',
        'neighborhoods' => 'Süvari, Topçu, İstasyon, Alsancak, Şeyh Şamil, Atayurt',
        'landmarks' => 'Etimesgut Devlet Hastanesi ve Etimesgut Hava Kuvvetleri Komutanlığı çevresindeki tüm konut ve iş yerlerine koltuk yıkama hizmeti ulaştırıyoruz.',
    ],
    'yasamkent'   => [
        'name' => 'Yaşamkent',
        'desc' => 'Yaşamkent ve çevresinde',
        'long_desc' => 'Yaşamkent, Ankara\'nın en modern ve planlı yaşam bölgelerinden biridir. Yeni yapı konutları ve kaliteli yaşam standartlarıyla öne çıkan Yaşamkent sakinlerine, aynı kalitede yerinde koltuk yıkama hizmeti sunuyoruz.',
        'neighborhoods' => 'Yaşamkent Merkez, Park Yaşamkent, Yaşamkent Bulvarı çevresi, Dodurga, İncek bağlantısı',
        'landmarks' => 'Yaşamkent\'in modern konut projeleri ve site yaşam alanlarına yerinde koltuk yıkama hizmeti sunuyoruz.',
    ],
    'beytepe'     => [
        'name' => 'Beytepe',
        'desc' => 'Beytepe bölgesinde',
        'long_desc' => 'Beytepe, Hacettepe Üniversitesi kampüsü ve çevresindeki konut bölgeleriyle bilinen, akademik ve aile dostu bir yaşam alanıdır. Üniversite personeli ve çevredeki konut sakinlerine yerinde koltuk yıkama hizmeti sunuyoruz.',
        'neighborhoods' => 'Beytepe Mahallesi, Hacettepe Üniversitesi çevresi, Beytepe Konutları, Bilkent-Beytepe bağlantısı',
        'landmarks' => 'Beytepe bölgesine randevulu yerinde koltuk yıkama hizmeti sunuyoruz, aynı gün kuruma garantisiyle.',
    ],
    'yenikent'    => [
        'name' => 'Yenikent',
        'desc' => 'Yenikent ve çevresinde',
        'long_desc' => 'Sincan ilçesinin önemli bölgelerinden Yenikent, geniş yerleşim alanları ve sanayi bölgelerine yakınlığı ile öne çıkar. Yenikent\'te yaşayan müşterilerimize yüksek kalitede, yerinde koltuk ve sandalye yıkama hizmeti sunmaktayız.',
        'neighborhoods' => 'Mehmet Akif Ersoy, Mustafa Kemal, Cumhuriyet, Menderes, Ulubatlı Hasan, Fevzi Çakmak',
        'landmarks' => 'Yenikent Sanayi Sitesi ve çevresindeki tüm site ve konutlara kolaylıkla ulaşarak koltuk yıkama hizmeti veriyoruz.',
    ],
    'yapracik'    => [
        'name' => 'Yapracık',
        'desc' => 'Yapracık bölgesinde',
        'long_desc' => 'Yapracık, Etimesgut\'a bağlı gelişmekte olan bir mahalledir. Yeni konut projeleri ve mevcut yerleşim alanlarıyla büyüyen bu bölgede, güvenilir ve ekonomik yerinde koltuk yıkama hizmeti sunuyoruz.',
        'neighborhoods' => 'Yapracık Mahallesi, Yapracık TOKİ, Yapracık Konutları, Etimesgut-Yapracık bağlantısı',
        'landmarks' => 'Yapracık bölgesine düzenli yerinde koltuk, çekyat ve yatak yıkama hizmeti sunuyoruz.',
    ],
    'cayyolu'     => [
        'name' => 'Çayyolu',
        'desc' => 'Çayyolu ve çevresinde',
        'long_desc' => 'Çayyolu, Ankara\'nın en prestijli yaşam bölgelerinden biridir. Lüks site ve villa sakinlerine; deri, kadife ve şönil gibi hassas kumaşlı koltuk takımları için özel uzmanlığımızla yerinde profesyonel temizlik sunuyoruz.',
        'neighborhoods' => 'Çayyolu 1-2-3. Etap, Çayyolu Bulvarı, çevre lüks siteler',
        'landmarks' => 'Çayyolu\'nun lüks konut bölgelerine hassas yerinde koltuk yıkama hizmeti veriyoruz.',
    ],
    'umitkoy'     => [
        'name' => 'Ümitköy',
        'desc' => 'Ümitköy ve çevresinde',
        'long_desc' => 'Ümitköy, Çayyolu hattının prestijli yaşam alanlarından biridir. Site sakinleri ve villa kullanıcılarına yerinde profesyonel koltuk, kanepe ve yatak yıkama hizmeti sunuyoruz.',
        'neighborhoods' => 'Ümitköy Merkez, Ümitköy site bölgeleri, çevre konut alanları',
        'landmarks' => 'Ümitköy\'ün modern yaşam alanlarına aynı gün kuruma garantili yerinde temizlik sağlıyoruz.',
    ],
    'konutkent'   => [
        'name' => 'Konutkent',
        'desc' => 'Konutkent bölgesinde',
        'long_desc' => 'Konutkent, Çayyolu-Ümitköy hattının lüks site bölgelerinden biridir. Site sakinlerine premium yerinde koltuk yıkama hizmeti sunuyoruz.',
        'neighborhoods' => 'Konutkent 1-2, çevre lüks siteler ve villa bölgeleri',
        'landmarks' => 'Konutkent\'in lüks site sakinlerine hassas kumaş koltuk takımları için profesyonel yerinde temizlik veriyoruz.',
    ],
];

// Hizmetler veritabanı
$services = [
    'koltuk-yikama'            => [
        'name' => 'Koltuk Yıkama',
        'icon' => 'cleaning_services',
        'intro' => 'Ev ve ofislerinizdeki koltuklarınız için derinlemesine temizlik ve hijyen çözümleri sunuyoruz.',
        'desc' => 'Koltuklarınızın kumaş türüne uygun, özel leke çıkarıcılar ve anti-bakteriyel şampuanlar kullanarak, yüksek vakumlu makinelerle derinlemesine yıkama işlemi gerçekleştiriyoruz. Kir, toz ve alerjenleri kökünden temizleyerek koltuklarınıza ilk günkü ferahlığını geri kazandırıyoruz.',
        'features' => [
            'Derinlemesine kir ve leke çıkarma',
            'Alerjenleri ve akarları yok etme',
            'Kumaşa uygun özel şampuan kullanımı',
            'Yüksek vakumlu güçlü emiş sistemi',
            'Hızlı kuruma ve ferahlık',
            'Çevre dostu ve insan sağlığına zararsız ürünler',
        ],
        'faq' => [
            ['S: Koltuklarım ne kadar sürede kurur?', 'C: Kumaşın cinsine ve ortamın havalandırmasına bağlı olarak genellikle 4-8 saat içinde kurur.'],
            ['S: Tüm lekeler çıkar mı?', 'C: Lekelerin türüne ve ne kadar eski olduğuna bağlı olarak değişkenlik gösterir. Ancak özel leke çıkarıcılarımızla maksimum başarı sağlıyoruz.'],
            ['S: Yıkama işlemi koltuğuma zarar verir mi?', 'C: Hayır, kumaşın türüne göre en uygun ve hassas yıkama tekniklerini kullanarak koltuklarınıza zarar vermeyiz.'],
            ['S: Fiyatlandırma nasıl yapılıyor?', 'C: Koltuk takımınızın adeti, büyüklüğü ve kumaş türüne göre ücretsiz keşif sonrası en uygun fiyat teklifini sunuyoruz.'],
        ],
    ],
    'sandalye-berjer-yikama'   => [
        'name' => 'Sandalye & Berjer Yıkama',
        'icon' => 'event_seat',
        'intro' => 'Yemek odası sandalyelerinizden berjerlerinize kadar, tüm tekli döşemeli mobilyalarınız için profesyonel temizlik.',
        'desc' => 'Tekli veya çoklu sandalyeleriniz ile berjerlerinizin kumaşlarını, özel buharlı ve vakumlu sistemlerle hijyenik bir şekilde temizliyoruz. Oturma alanlarında biriken kir, toz ve yemek artıklarını etkili bir şekilde gidererek mobilyalarınıza yenilenmiş bir görünüm kazandırıyoruz.',
        'features' => [
            'Hassas kumaşlara özel temizlik',
            'İnatçı leke ve kir çıkarma',
            'Oturma alanlarında tam hijyen',
            'Ergonomik ve detaylı temizlik',
            'Hızlı ve pratik yerinde servis',
            'Ahşap ve metal kısımları koruyucu uygulama',
        ],
        'faq' => [
            ['S: Her türlü sandalye ve berjer yıkanabilir mi?', 'C: Kumaş döşemeli tüm sandalyeler ve berjerler yıkanabilir. Deri gibi özel malzemeler için farklı uygulamalarımız mevcuttur.'],
            ['S: Sandalyelerin ahşap veya metal kısımları zarar görür mü?', 'C: Hayır, yıkama işlemi sadece kumaş kısımlara odaklanır ve diğer yüzeylerin korunmasına özen gösterilir.'],
            ['S: Tek bir sandalye için de hizmet alabilir miyim?', 'C: Evet, ihtiyacınız doğrultusunda tekli veya toplu olarak hizmet alabilirsiniz.'],
            ['S: Berjer yıkama ile koltuk yıkama arasında fark var mı?', 'C: Temel prensip aynı olsa da, berjerlerin daha detaylı ve hassas çalışma gerektiren özel tasarım detayları için farklı uygulamalarımız olabilir.'],
        ],
    ],
    'cekyat-kanepe-yikama'     => [
        'name' => 'Çekyat & Kanepe Yıkama',
        'icon' => 'single_bed',
        'intro' => 'Yaşam alanlarınızın merkezi olan çekyat ve kanepelerinizde tam hijyen ve ferahlık sağlıyoruz.',
        'desc' => 'Evlerimizin en çok kullanılan mobilyalarından olan çekyat ve kanepeler, zamanla kir, leke ve kötü kokular barındırabilir. Kumaşın yapısına zarar vermeden, derinlemesine temizlik sağlayan özel tekniklerimizle çekyat ve kanepelerinizi ilk günkü temizliğine kavuşturuyoruz.',
        'features' => [
            'Geniş yüzeylere özel yıkama',
            'Yataklı mekanizmalara dikkat',
            'Evcil hayvan tüyü ve kokusu temizliği',
            'Derinlemesine hijyen ve sterilizasyon',
            'Kumaşa uygun özel şampuan seçimi',
            'Uzun ömürlü ve kalıcı temizlik',
        ],
        'faq' => [
            ['S: Çekyatın yatak mekanizmasına zarar gelir mi?', 'C: Yıkama işlemi sadece kumaş döşemeye uygulanır ve mekanizmaya su teması minimize edilerek zarar görmesi engellenir.'],
            ['S: Büyük ebatlardaki kanepeler için fiyat farkı olur mu?', 'C: Evet, kanepe veya çekyatın ebatlarına ve modeline göre fiyatlandırma değişkenlik gösterebilir.'],
            ['S: Minderler ve sırt yastıkları da yıkanıyor mu?', 'C: Evet, çıkarılabilen tüm minder ve yastıklar ana yıkama işlemine dahil edilir.'],
            ['S: İşlem ne kadar sürer?', 'C: Kanepe veya çekyatın büyüklüğüne ve kirlilik derecesine göre ortalama 1-2 saat sürmektedir.'],
        ],
    ],
    'yatak-yikama'             => [
        'name' => 'Yatak Yıkama',
        'icon' => 'bed',
        'intro' => 'Sağlıklı ve hijyenik bir uyku deneyimi için yataklarınızı derinlemesine temizliyoruz.',
        'desc' => 'Yataklarda zamanla biriken ter, ölü deri hücreleri, akar ve bakteriler uyku kalitenizi ve sağlığınızı olumsuz etkileyebilir. Özel buharlı ve vakumlu sistemlerimizle yataklarınızı bu tür alerjenlerden arındırarak sağlıklı bir uyku ortamı sunuyoruz.',
        'features' => [
            'Akarlara karşı etkili temizlik',
            'Derinlemesine hijyen ve dezenfeksiyon',
            'Kötü koku ve ter izlerini giderme',
            'Alerjik reaksiyonları azaltma',
            'Uyku kalitesini artırma',
            'Buharlı sterilizasyon uygulaması',
        ],
        'faq' => [
            ['S: Yatak yıkama işlemi yatağa zarar verir mi?', 'C: Hayır, yatağınızın kumaşına ve dolgu malzemesine uygun, zarar vermeyen profesyonel teknikler kullanılır.'],
            ['S: Lateks veya visco yataklar yıkanabilir mi?', 'C: Evet, özel uygulamalarla bu tür yataklar da hijyenik olarak temizlenebilir.'],
            ['S: Yatak ne kadar sürede kurur?', 'C: Yatağın kalınlığına ve oda sıcaklığına bağlı olarak genellikle 8-12 saat içinde tamamen kurur.'],
            ['S: Çocuk yatakları veya tek kişilik yataklar için fiyatlandırma nasıl?', 'C: Yatağın ebatlarına göre fiyatlandırma farklılık gösterir. Tek ve çift kişilik yataklar için ayrı tarifelerimiz mevcuttur.'],
        ],
    ],
    'leke-koku-giderme'        => [
        'name' => 'Leke & Koku Giderme',
        'icon' => 'auto_awesome',
        'intro' => 'İnatçı lekelere ve kötü kokulara karşı özel çözümlerle mobilyalarınızı ilk günkü haline döndürüyoruz.',
        'desc' => 'Kahve, şarap, yağ, mürekkep gibi zorlu lekelerin yanı sıra, evcil hayvan idrarı, sigara dumanı gibi rahatsız edici kötü kokuları da özel solüsyonlar ve uzman tekniklerle ortadan kaldırıyoruz. Kumaşın yapısına zarar vermeden leke ve koku sorunlarınıza kalıcı çözümler sunuyoruz.',
        'features' => [
            'Özel leke çıkarıcı solüsyonlar',
            'Koku nötralizasyon teknolojisi',
            'Kumaşa zarar vermeyen uygulama',
            'Kalıcı ve etkili çözüm',
            'Eski ve inatçı lekelerde yüksek başarı',
            'Ortama taze ve temiz bir koku bırakma',
        ],
        'faq' => [
            ['S: Her türlü leke çıkarılabilir mi?', 'C: Lekenin türüne, kumaşın cinsine ve lekenin ne kadar süredir var olduğuna bağlı olarak değişir, ancak en iyi sonucu almak için çaba gösteriyoruz.'],
            ['S: Koku tamamen gider mi?', 'C: Evet, özel koku nötralizasyon ürünlerimizle kötü kokuları maskelemek yerine, moleküler düzeyde yok ederek kalıcı çözüm sağlıyoruz.'],
            ['S: Leke çıkarma işlemi kumaşa zarar verir mi?', 'C: Hayır, kumaşın türüne uygun, pH dengeli ve kumaş dostu ürünler kullanılır.'],
            ['S: Bu hizmeti koltuk yıkama ile birlikte mi almalıyım?', 'C: En iyi sonuçlar için leke ve koku giderme işlemi, profesyonel koltuk yıkama hizmeti ile birlikte önerilir.'],
        ],
    ],
    'perde-yikama' => [
        'name' => 'Perde Yıkama',
        'icon' => 'curtains',
        'intro' => 'Tül, fon ve stor perdelerinizi sökmeden veya yerinde, kumaşına özel şampuanlarla profesyonelce yıkıyoruz.',
        'desc' => 'Perdeler, ev içerisindeki tozu ve alerjenleri en çok tutan tekstil ürünleri arasındadır. Perdelerinizin kumaş türüne (tül, fon, blackout, stor, jaluzi) uygun olarak, çekme ve solma yapmadan derinlemesine temizlik sağlıyoruz. Söküp takma hizmeti dahil, eve teslim sistemiyle çalışıyoruz.',
        'features' => [
            'Tül, fon, stor ve blackout perde temizliği',
            'Söküp takma hizmeti dahil',
            'Çekme ve solma garantisi',
            'Anti-alerjik özel şampuan',
            'Ütüleme ve katlama dahil',
            'Hassas kumaşlar için özel program',
        ],
        'faq' => [
            ['S: Perdeler söküp götürülüyor mu yoksa yerinde mi yıkanıyor?', 'C: Genellikle daha kaliteli bir temizlik için söküp atölyemizde yıkıyor ve aynı gün ütülenmiş şekilde takıyoruz.'],
            ['S: Perdelerim çeker mi?', 'C: Hayır, perdelerinizin kumaş özelliğine göre uygun ısı ve programda yıkadığımız için çekme yapmaz.'],
            ['S: Stor ve jaluzi perdeler de yıkanıyor mu?', 'C: Evet, stor, jaluzi, plise gibi tüm perde türlerini özel teknikle temizliyoruz.'],
            ['S: Perde yıkama ne kadar sürer?', 'C: Söküm, yıkama, ütüleme ve takım dahil aynı gün veya ertesi gün teslim edilir.'],
        ],
    ],
    'yorgan-yikama' => [
        'name' => 'Yorgan Yıkama',
        'icon' => 'bedroom_parent',
        'intro' => 'Yün, elyaf, pamuk ve kaz tüyü yorganlarınızı hijyenik şekilde derinlemesine temizliyoruz.',
        'desc' => 'Yorganlarda zaman içinde ter, ölü deri, akar ve toz birikir. Yorganınızın iç dolgu malzemesine (yün, elyaf, kaz tüyü, pamuk) uygun yıkama programı ile derinlemesine temizleyip, yüksek devirli sıkma ve özel kurutma sistemleriyle topaklanma yapmadan ilk günkü yumuşaklığına döndürüyoruz.',
        'features' => [
            'Yün, elyaf, pamuk, kaz tüyü yorgan temizliği',
            'Akar ve alerjen arındırma',
            'Topaklanma yapmayan özel kurutma',
            'Anti-bakteriyel hijyen uygulaması',
            'Kapıdan alma - kapıya teslim',
            'Bebek yorganları için hipoalerjenik program',
        ],
        'faq' => [
            ['S: Her tür yorgan yıkanabilir mi?', 'C: Evet, yün, elyaf, pamuk, kaz tüyü ve mikrofiber tüm yorgan türlerini özel programlarla temizliyoruz.'],
            ['S: Yorgan topaklanır mı?', 'C: Hayır, özel kurutma sistemimiz sayesinde yorganınız topaklanmadan eski yumuşaklığında geri döner.'],
            ['S: Yorganlar ne kadar sürede teslim edilir?', 'C: Yıkama ve kurutma süreciyle birlikte genellikle 24-48 saat içinde teslim edilir.'],
            ['S: Bebek yorganları için özel uygulama var mı?', 'C: Evet, bebekler için hipoalerjenik ve cilt dostu özel şampuanlar kullanıyoruz.'],
        ],
    ],
    'anti-bakteriyel-dezenfeksiyon' => [
        'name' => 'Anti-Bakteriyel Dezenfeksiyon',
        'icon' => 'workspace_premium',
        'intro' => 'Koltuk ve yataklarınızda tam hijyen sağlamak için anti-bakteriyel dezenfeksiyon hizmeti sunuyoruz.',
        'desc' => 'Yıkama sonrası uygulanan özel dezenfektanlarla bakteri, virüs ve mantar oluşumunun önüne geçiyoruz. Yaşam alanlarınızda özellikle çocuklu ve alerjisi olan aileler için sağlıklı, steril ve güvenli bir ortam oluşturarak maksimum hijyen sağlıyoruz.',
        'features' => [
            '%99.9 oranında bakteri öldürme',
            'Virüs ve mantar oluşumunu engelleme',
            'Alerjenleri önemli ölçüde azaltma',
            'Evcil hayvan dostu ve güvenli ürünler',
            'Sağlık otoriteleri onaylı dezenfektanlar',
            'Uzun süreli koruma ve sterilizasyon',
        ],
        'faq' => [
            ['S: Kullanılan kimyasallar insan sağlığına zararlı mı?', 'C: Hayır, kullandığımız dezenfektanlar insan ve evcil hayvan sağlığına uygun, çevre dostu ve uluslararası standartlara sahip ürünlerdir.'],
            ['S: Bebekli veya alerjisi olan aileler için uygun mu?', 'C: Evet, aksine bu tür durumlar için özellikle tavsiye ettiğimiz bir hizmettir. Alerjenleri azaltır ve daha sağlıklı bir ortam sunar.'],
            ['S: Bu hizmeti ne sıklıkla almalıyım?', 'C: Yataklar için yılda en az iki kez, koltuklar için ise kullanım yoğunluğuna bağlı olarak yılda 1-2 kez önerilir.'],
            ['S: COVID-19 gibi virüslere karşı etkili midir?', 'C: Evet, kullandığımız dezenfektanlar geniş spektrumlu olup virüslere karşı da etkilidir, yaşam alanlarınızda hijyen güvenliğini artırır.'],
        ],
    ],
];

// Slug alias veritabanı
$slugAliases = null;
?>