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
