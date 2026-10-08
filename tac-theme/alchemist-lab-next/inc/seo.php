<?php
/** Idioma del documento, hreflang y redirecciones desde las URL de la web anterior. */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('language_attributes', function ($output) {
    $lang = tac_lang() === 'en' ? 'en-US' : 'es-MX';
    return preg_replace('/lang="[^"]*"/', 'lang="' . $lang . '"', $output) ?: 'lang="' . $lang . '"';
});

add_action('wp_head', function () {
    $view = tac_current_view();
    if (!$view) {
        return;
    }
    if ($view === 'app') {
        $k = (string) get_post_meta(get_queried_object_id(), 'tac_app', true);
        $es = tac_app_url($k, 'es');
        $en = tac_app_url($k, 'en');
        if ($es && $en) {
            echo '<link rel="alternate" hreflang="es-MX" href="' . esc_url($es) . "\">\n";
            echo '<link rel="alternate" hreflang="en" href="' . esc_url($en) . "\">\n";
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($es) . "\">\n";
        }
        $ios = tac_apps_live()['apps'][$k]['enlaces']['ios'] ?? '';
        if ($ios && preg_match('/id(\d+)/', $ios, $mm)) {
            // Smart App Banner: en Safari para iPhone ofrece abrir o instalar la app.
            echo '<meta name="apple-itunes-app" content="app-id=' . esc_attr($mm[1]) . '">' . "\n";
        }
        return;
    }
    $map = tac_page_map();
    if (empty($map[$view]['es']) || empty($map[$view]['en'])) {
        return;
    }
    $es = tac_url($view, 'es');
    $en = tac_url($view, 'en');
    echo '<link rel="alternate" hreflang="es-MX" href="' . esc_url($es) . "\">\n";
    echo '<link rel="alternate" hreflang="en" href="' . esc_url($en) . "\">\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($es) . "\">\n";
}, 2);

/** La web anterior estaba en inglés: sus URL llevan a la versión en inglés de cada página. */
add_action('template_redirect', function () {
    $path = trim((string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $map = array(
        'about'     => array('about', 'en'),
        'services'  => array('solutions', 'en'),
        'team'      => array('about', 'en'),
        'faq'       => array('faq', 'en'),
        'contact'   => array('contact', 'en'),
        'portfolio' => array('apps', 'en'),
    );
    if (isset($map[$path])) {
        wp_safe_redirect(tac_url($map[$path][0], $map[$path][1]), 301);
        exit;
    }
}, 1);

/** Ícono del sitio con el monograma, si no hay uno configurado en WordPress. */
add_action('wp_head', function () {
    if (has_site_icon()) {
        return;
    }
    $icon = get_template_directory_uri() . '/assets/img/icon-180.png';
    echo '<link rel="icon" href="' . esc_url($icon) . '" sizes="180x180">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url($icon) . '">' . "\n";
}, 3);

/* ---------- ajustes a All in One SEO (estudio SEO del 3-oct-2026) ---------- */

/** Idioma correcto en Open Graph y tipo "website" en las portadas. */
add_filter('aioseo_facebook_tags', function ($tags) {
    $tags['og:locale'] = tac_lang() === 'en' ? 'en_US' : 'es_MX';
    if (tac_current_view() === 'home') {
        $tags['og:type'] = 'website';
    }
    return $tags;
});

/** Datos estructurados: idioma, migas sin duplicados y la organización como ProfessionalService. */
add_filter('aioseo_schema_output', function ($graphs) {
    if (!is_array($graphs)) {
        return $graphs;
    }
    $lang = tac_lang() === 'en' ? 'en' : 'es-MX';
    $org = null;
    $f = get_template_directory() . '/inc/data/schema_org.json';
    if (is_readable($f)) {
        $j = json_decode(file_get_contents($f), true);
        foreach ($j['@graph'] ?? array() as $n) {
            if (($n['@type'] ?? '') === 'ProfessionalService') {
                $org = $n;
            }
        }
    }
    foreach ($graphs as $i => $n) {
        $type = is_array($n) ? ($n['@type'] ?? '') : '';
        if (in_array($type, array('WebPage', 'WebSite', 'CollectionPage', 'AboutPage', 'ContactPage', 'FAQPage', 'ItemPage'), true)) {
            $graphs[$i]['inLanguage'] = $lang;
        }
        if ($type === 'Organization' && $org) {
            $graphs[$i] = $org;
            // catálogo de servicios con la URL de cada página en el idioma actual
            $servicios = array(
                'local'   => tac_t('Desarrollo de software a la medida', 'Custom software development'),
                'movil'   => tac_t('Desarrollo de aplicaciones móviles', 'Mobile app development'),
                'flutter' => tac_t('Desarrollo en Flutter', 'Flutter development'),
                'ia'      => tac_t('Inteligencia artificial para empresas', 'AI development'),
                'infra'   => tac_t('Infraestructura de TI, nube y redes', 'Cloud and network infrastructure'),
            );
            $map = tac_page_map();
            $ofertas = array();
            foreach ($servicios as $v => $nombre) {
                if (!empty($map[$v][tac_lang()])) {
                    $ofertas[] = array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => $nombre, 'url' => tac_url($v)));
                }
            }
            if ($ofertas) {
                $graphs[$i]['hasOfferCatalog'] = array('@type' => 'OfferCatalog', 'name' => tac_t('Servicios', 'Services'), 'itemListElement' => $ofertas);
            }
        }
        if ($type === 'BreadcrumbList' && !empty($n['itemListElement'])) {
            $items = array();
            $first = tac_lang() === 'en' ? array('name' => 'Home', 'item' => tac_url('home', 'en')) : array('name' => 'Inicio', 'item' => home_url('/'));
            foreach ($n['itemListElement'] as $it) {
                $url = $it['item'] ?? ($it['item']['@id'] ?? '');
                if (is_array($url)) {
                    $url = $url['@id'] ?? '';
                }
                if (!$items) {
                    $items[] = array_merge($it, $first);
                    continue;
                }
                $prev = end($items);
                $prevUrl = is_array($prev['item'] ?? null) ? ($prev['item']['@id'] ?? '') : ($prev['item'] ?? '');
                if (($it['name'] ?? '') === ($prev['name'] ?? '') || ($url && trailingslashit($url) === trailingslashit((string) $prevUrl))) {
                    continue;
                }
                $items[] = $it;
            }
            foreach ($items as $p => $it) {
                $items[$p]['position'] = $p + 1;
            }
            $graphs[$i]['itemListElement'] = array_values($items);
        }
    }
    return $graphs;
});

/** Archivos de categorías y etiquetas: fuera del índice (contenido duplicado del blog). */
add_filter('wp_robots', function ($robots) {
    if (is_category() || is_tag() || is_author() || is_date()) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
    }
    return $robots;
});

/** All in One SEO imprime su propia etiqueta robots e ignora wp_robots: el noindex de archivos se aplica aquí también. */
add_filter('aioseo_robots_meta', function ($attrs) {
    if (is_category() || is_tag() || is_author() || is_date()) {
        unset($attrs['index']);
        $attrs['noindex'] = 'noindex';
        $attrs['follow'] = 'follow';
    }
    return $attrs;
});

/* ---------- IndexNow: Bing (y con él DuckDuckGo, Yahoo, Copilot y la búsqueda de ChatGPT), Yandex y otros ---------- */

/** Clave de IndexNow: se genera una vez y vive en la base de datos (no en el repositorio). */
function tac_indexnow_key() {
    $k = (string) get_option('tac_indexnow_key');
    if (!preg_match('/^[a-f0-9]{32}$/', $k)) {
        $k = bin2hex(random_bytes(16));
        update_option('tac_indexnow_key', $k, false);
    }
    return $k;
}

/** Avisa a IndexNow de URLs nuevas o cambiadas (máximo 10,000 por envío; sin bloquear la petición). */
function tac_indexnow_submit(array $urls, $blocking = false) {
    $host = wp_parse_url(home_url(), PHP_URL_HOST);
    $urls = array_values(array_unique(array_filter($urls, function ($u) use ($host) {
        return wp_parse_url($u, PHP_URL_HOST) === $host;
    })));
    if (!$urls || wp_get_environment_type() !== 'production') {
        return null;
    }
    $key = tac_indexnow_key();
    return wp_remote_post('https://api.indexnow.org/indexnow', array(
        'blocking' => $blocking,
        'timeout'  => $blocking ? 20 : 3,
        'headers'  => array('Content-Type' => 'application/json; charset=utf-8'),
        'body'     => wp_json_encode(array('host' => $host, 'key' => $key, 'keyLocation' => home_url('/' . $key . '.txt'), 'urlList' => array_slice($urls, 0, 10000))),
    ));
}

// Al publicar o actualizar una página o entrada pública, se avisa de su URL (y de su versión en el otro idioma).
add_action('transition_post_status', function ($nuevo, $viejo, $post) {
    if ($nuevo !== 'publish' || !in_array($post->post_type, array('post', 'page'), true) || wp_is_post_revision($post) || wp_is_post_autosave($post)) {
        return;
    }
    $urls = array(get_permalink($post));
    $view = (string) get_post_meta($post->ID, 'tac_view', true);
    if ($view && $view !== 'app') {
        $otro = get_post_meta($post->ID, 'tac_lang', true) === 'en' ? 'es' : 'en';
        $urls[] = tac_url($view, $otro);
    }
    tac_indexnow_submit($urls);
}, 10, 3);

/* ---------- archivos de texto en la raíz: clave de IndexNow y llms.txt ---------- */

add_action('init', function () {
    $path = trim((string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if ($path === '' || strpos($path, '/') !== false || substr($path, -4) !== '.txt') {
        return;
    }
    if ($path === tac_indexnow_key() . '.txt') {
        header('Content-Type: text/plain; charset=utf-8');
        echo tac_indexnow_key(); // phpcs:ignore
        exit;
    }
    if ($path === 'llms.txt') {
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        echo tac_llms_txt(); // phpcs:ignore
        exit;
    }
}, 0);

/**
 * llms.txt (llmstxt.org): resumen del estudio para asistentes de IA (ChatGPT, Claude, Perplexity…),
 * con enlaces a las páginas que responden "¿quién hace apps / software / IA en Puebla o México?".
 * Solo datos que ya están publicados en el sitio, con su fuente.
 */
function tac_llms_txt() {
    $u = function ($v, $l) { return tac_url($v, $l); };
    $s = array();
    $s[] = '# The Alchemist Code';
    $s[] = '';
    $s[] = '> Founder-led software, mobile app and AI development studio in Puebla, Mexico, working with companies in Mexico and the United States. We design, build, launch and run software, and we publish and operate 17 apps of our own (iOS, Android and desktop).';
    $s[] = '';
    $s[] = 'Estudio de desarrollo de software, apps móviles e inteligencia artificial en Puebla, México, dirigido por su fundador. Atiende empresas de México y Estados Unidos. Contratos en MXN o USD, factura CFDI, NDA y el código queda a nombre del cliente.';
    $s[] = '';
    $s[] = '## Facts (with sources)';
    $s[] = '- 100,000+ Google Play installs across our own apps (Play Console, Sep 2026).';
    $s[] = '- Soccer24: 75,685 Google Play installs and a public 4.5★ rating (Play Console, Sep 2026; Google Play, Oct 2026).';
    $s[] = '- Komodo VPN: 30,566 Google Play installs, running on our own OpenVPN server network (Play Console, Sep 2026).';
    $s[] = '- Research: "Enhanced Cybersecurity: AI-Driven Phishing Fraud Detection Approach", Springer Nature, 2025, https://doi.org/10.1007/978-3-031-85363-0_21';
    $s[] = '- Location: Puebla, Mexico (UTC−6 all year). Languages: Spanish and English. Contact: ' . TAC_MAIL;
    $s[] = '';
    $s[] = '## Services (English)';
    $s[] = '- [Software development company in Mexico](' . $u('local', 'en') . '): custom software, mobile apps, AI and infrastructure for US and Mexican companies.';
    $s[] = '- [Mobile app development](' . $u('movil', 'en') . '): iOS and Android apps in Flutter, Swift and Kotlin, from design to store release and maintenance.';
    $s[] = '- [Flutter development](' . $u('flutter', 'en') . '): most of our 17 apps are built in Flutter.';
    $s[] = '- [AI development](' . $u('ia', 'en') . '): document reading (OCR), image recognition, assistants and fraud detection.';
    $s[] = '- [Cloud and network infrastructure](' . $u('infra', 'en') . '): Linux servers, Google Cloud, Firebase, VPNs, security and monitoring.';
    $s[] = '';
    $s[] = '## Servicios (español)';
    $s[] = '- [Desarrollo de software en Puebla](' . $u('local', 'es') . '): software a la medida, apps móviles, IA e infraestructura.';
    $s[] = '- [Desarrollo de aplicaciones móviles](' . $u('movil', 'es') . '): apps iOS y Android en Flutter, Swift y Kotlin.';
    $s[] = '- [Desarrollo en Flutter](' . $u('flutter', 'es') . ')';
    $s[] = '- [Inteligencia artificial para empresas](' . $u('ia', 'es') . ')';
    $s[] = '- [Infraestructura y redes](' . $u('infra', 'es') . ')';
    $s[] = '';
    $s[] = '## More';
    $s[] = '- [Our 17 apps](' . $u('apps', 'en') . ')';
    $s[] = '- [How to engage, FAQ](' . $u('faq', 'en') . ')';
    $s[] = '- [Contact / start a project](' . $u('contact', 'en') . ')';
    $s[] = '- [Blog and guides](' . tac_url('blog') . ')';
    $s[] = '- [Source code of this website (GPL-2.0)](https://github.com/Alekla0126/thealchemistcode-website)';
    return implode("\n", $s) . "\n";
}
