<?php
/** Idioma, enlaces entre páginas, recursos y el shortcode [tac_page]. */

if (!defined('ABSPATH')) {
    exit;
}

/** Idioma de la página actual: es (por defecto) o en. */
function tac_lang() {
    static $lang = null;
    if ($lang !== null) {
        return $lang;
    }
    $lang = 'es';
    $id = get_queried_object_id();
    if ($id && (is_singular() || is_page())) {
        $meta = get_post_meta($id, 'tac_lang', true);
        if ($meta === 'en') {
            $lang = 'en';
        }
    }
    return $lang;
}

/** Devuelve el texto en el idioma actual. */
function tac_t($es, $en) {
    return tac_lang() === 'en' ? $en : $es;
}

/** Imprime el texto en el idioma actual, sin escapar (las vistas solo llevan texto propio). */
function tac_e($es, $en) {
    echo tac_t($es, $en); // phpcs:ignore WordPress.Security.EscapeOutput
}

/** Mapa vista → idioma → ID de página, a partir de los metadatos. */
function tac_page_map() {
    static $map = null;
    if ($map !== null) {
        return $map;
    }
    $map = array();
    $q = new WP_Query(array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 100,
        'meta_key'       => 'tac_view',
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ));
    foreach ($q->posts as $id) {
        $view = get_post_meta($id, 'tac_view', true);
        $lang = get_post_meta($id, 'tac_lang', true) === 'en' ? 'en' : 'es';
        $map[$view][$lang] = (int) $id;
    }
    return $map;
}

/** URL de una vista en un idioma (el actual si no se indica). */
function tac_url($view, $lang = null) {
    $lang = $lang ?: tac_lang();
    if ($view === 'blog') {
        $blog = (int) get_option('page_for_posts');
        return $blog ? get_permalink($blog) : home_url('/');
    }
    if ($view === 'home' && $lang === 'es') {
        return home_url('/');
    }
    $map = tac_page_map();
    if (!empty($map[$view][$lang])) {
        return get_permalink($map[$view][$lang]);
    }
    return $lang === 'en' && !empty($map['home']['en']) ? get_permalink($map['home']['en']) : home_url('/');
}

/** Vista de la página actual (o '' en blog y entradas). */
function tac_current_view() {
    $id = get_queried_object_id();
    return $id ? (string) get_post_meta($id, 'tac_view', true) : '';
}

/** URL de la misma página en el otro idioma. */
function tac_counterpart_url($lang) {
    $view = tac_current_view();
    if ($view === 'app') {
        $u = tac_app_url((string) get_post_meta(get_queried_object_id(), 'tac_app', true), $lang);
        return $u ?: tac_url('apps', $lang);
    }
    if ($view) {
        return tac_url($view, $lang);
    }
    if (is_singular('post') && tac_lang() === $lang) {
        return get_permalink(get_queried_object_id());
    }
    if (is_singular('post') && ($par = tac_post_par(get_queried_object_id())) && get_post_meta($par->ID, 'tac_lang', true) === $lang) {
        return get_permalink($par);
    }
    if (is_home() || is_singular('post') || is_archive()) {
        return tac_url('blog', $lang);
    }
    return tac_url('home', $lang);
}

/** Traducción de una entrada del blog (metadato tac_par = slug de la versión en el otro idioma), si está publicada. */
function tac_post_par($id) {
    $slug = (string) get_post_meta($id, 'tac_par', true);
    $p = $slug ? get_page_by_path($slug, OBJECT, 'post') : null;
    return $p && $p->post_status === 'publish' ? $p : null;
}

/** URL de una imagen del sitio (subida a uploads/tac-site). */
function tac_img($file) {
    return content_url('uploads/tac-site/' . $file);
}

/** URL del icono de una app. Usa la versión de 256 px (@2x) si existe: se ve nítida hasta 84 px en pantallas 3x. */
function tac_icon($key) {
    $dir = trailingslashit(wp_upload_dir()['basedir']) . 'tac-site/icons/';
    foreach (array($key . '@2x.webp', $key . '.webp') as $f) {
        if (is_readable($dir . $f)) {
            return content_url('uploads/tac-site/icons/' . $f);
        }
    }
    return content_url('uploads/alchemist-apps/icons/' . $key . '.png');
}

/** Atributo srcset 1x/2x si junto a uploads/tac-site/<ruta>.webp existe <ruta>@2x.webp. */
function tac_srcset($rel) {
    $hi = preg_replace('/\.webp$/', '@2x.webp', $rel);
    if ($hi === $rel || !is_readable(trailingslashit(wp_upload_dir()['basedir']) . 'tac-site/' . $hi)) {
        return '';
    }
    return ' srcset="' . esc_attr(content_url('uploads/tac-site/' . $rel) . ' 1x, ' . content_url('uploads/tac-site/' . $hi) . ' 2x') . '"';
}

/** Enlace de WhatsApp con un mensaje ya redactado. */
function tac_wa($text = '') {
    return 'https://wa.me/' . TAC_WA . ($text !== '' ? '?text=' . rawurlencode($text) : '');
}

/** Logo del estudio (el matraz azul). */
function tac_logo($size = 30) {
    $dir = get_template_directory_uri() . '/assets/img/';
    return '<img class="tac-logo" src="' . esc_url($dir . 'logo-64.png') . '" srcset="' . esc_url($dir . 'logo-64.png') . ' 1x, ' . esc_url($dir . 'logo-128.png') . ' 2x" width="' . (int) $size . '" height="' . (int) $size . '" alt="">';
}

function tac_svg($name) {
    switch ($name) {
        case 'mark':
            return '<svg viewBox="0 0 40 40" fill="none" stroke-width="1.6" aria-hidden="true"><circle cx="20" cy="20" r="17.5" stroke="currentColor"/><path d="M20 6.5 32 29.5H8Z" stroke="#0A5FB4"/><circle cx="20" cy="22" r="5.2" stroke="#B4532A"/></svg>';
        case 'mark-dark':
            return '<svg viewBox="0 0 40 40" fill="none" stroke-width="1.6" aria-hidden="true"><circle cx="20" cy="20" r="17.5" stroke="#E9EAEC"/><path d="M20 6.5 32 29.5H8Z" stroke="#7DBEF0"/><circle cx="20" cy="22" r="5.2" stroke="#D9875B"/></svg>';
        case 'wa':
            return '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.2a9.7 9.7 0 0 0-8.4 14.6L2.3 21.7l5-1.3A9.7 9.7 0 1 0 12 2.2Zm0 17.7a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 19.9Zm4.4-6c-.2-.1-1.4-.7-1.7-.8-.2-.1-.4-.1-.5.1l-.8.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.5-.4h-.5a.9.9 0 0 0-.7.3 2.8 2.8 0 0 0-.9 2.1 4.9 4.9 0 0 0 1 2.6 11.1 11.1 0 0 0 4.3 3.8c1.6.7 2.2.7 3 .6a2.6 2.6 0 0 0 1.7-1.2 2.1 2.1 0 0 0 .2-1.2c-.1-.1-.3-.2-.5-.3Z"/></svg>';
        case 'check':
            return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="#E7F0FB"/><path d="m7 12.5 3.2 3.2L17 9" stroke="#0A5FB4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        case 'mail':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>';
        case 'video':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="6" width="13" height="12" rx="2"/><path d="m16 10 5-3v10l-5-3"/></svg>';
        case 'clock':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';
        case 'shield':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 4.5 6v5.5c0 4.5 3.2 8.2 7.5 9.5 4.3-1.3 7.5-5 7.5-9.5V6Z"/><path d="m8.8 12 2.2 2.2 4.4-4.4"/></svg>';
        case 'spark':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6.3 6.3l2.5 2.5M15.2 15.2l2.5 2.5M6.3 17.7l2.5-2.5M15.2 8.8l2.5-2.5"/></svg>';
        case 'chat':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v11H9l-5 4Z"/><path d="M8 9.5h8M8 12.5h5"/></svg>';
        case 'doc':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 3h7l5 5v13H7Z"/><path d="M14 3v5h5M10 13h6M10 17h4"/></svg>';
        case 'lock':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="10.5" width="14" height="10" rx="2.5"/><path d="M8.5 10.5V7.5a3.5 3.5 0 0 1 7 0v3"/></svg>';
        case 'phone':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="7" y="2.5" width="10" height="19" rx="2.5"/><path d="M10.5 18.5h3"/></svg>';
        case 'arrow':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
        case 'cloud':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 18.5h10.5a4 4 0 0 0 .6-7.95A6 6 0 0 0 6.6 9.2 4.7 4.7 0 0 0 7 18.5Z"/></svg>';
        case 'net':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="2.6"/><circle cx="4.5" cy="5.5" r="1.9"/><circle cx="19.5" cy="5.5" r="1.9"/><circle cx="4.5" cy="18.5" r="1.9"/><circle cx="19.5" cy="18.5" r="1.9"/><path d="m6 7 4 3.4M18 7l-4 3.4M6 17l4-3.4M18 17l-4-3.4"/></svg>';
        case 'pulse':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12h4l2.5-6 5 12 2.5-6h4"/></svg>';
        case 'stack':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 9 4.5-9 4.5-9-4.5Z"/><path d="m3 12 9 4.5 9-4.5M3 16.5 12 21l9-4.5"/></svg>';
        case 'chart':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20V4M4 20h16"/><path d="m7.5 14.5 3.5-4 3 2.5 5-6"/></svg>';
        case 'menu':
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>';
    }
    return '';
}

/** Íconos de todas las apps publicadas, leídos de apps.json. */
function tac_app_icons() {
    $file = trailingslashit(wp_upload_dir()['basedir']) . 'alchemist-apps/apps.json';
    $data = is_readable($file) ? json_decode(file_get_contents($file), true) : null;
    if (empty($data['apps'])) {
        return '';
    }
    $out = '';
    foreach ($data['apps'] as $a) {
        if (!empty($a['icono'])) {
            $out .= '<img src="' . esc_url(tac_icon($a['clave'])) . '" alt="' . esc_attr($a['nombre']) . '" title="' . esc_attr($a['nombre']) . '" width="44" height="44" loading="lazy">';
        }
    }
    return $out;
}

/** [tac_page] pinta la vista de la página actual (o la indicada con view="..."). */
add_shortcode('tac_page', function ($atts) {
    $atts = shortcode_atts(array('view' => ''), $atts, 'tac_page');
    $view = $atts['view'] ?: tac_current_view();
    $view = preg_replace('/[^a-z]/', '', $view);
    $file = get_template_directory() . '/inc/views/' . $view . '.php';
    if (!$view || !is_readable($file)) {
        return '';
    }
    ob_start();
    include $file;
    return ob_get_clean();
});

// Las vistas son HTML completo: que wpautop no meta <p> ni <br> entre etiquetas.
add_filter('the_content', function ($content) {
    if (is_singular('page') && tac_current_view() && has_shortcode($content, 'tac_page')) {
        remove_filter('the_content', 'wpautop');
        remove_filter('the_content', 'wptexturize');
    }
    return $content;
}, 1);

/** Teléfono con la pantalla real de una app (uploads/tac-site/screens/<key>.webp). */
function tac_phone($key, $alt = '', $eager = false) {
    // phones/ guarda pantallas completas (barra de estado incluida, generadas con qa/phones.py); screens/ eran recortes.
    $base = trailingslashit(wp_upload_dir()['basedir']) . 'tac-site/';
    $dir = is_readable($base . 'phones/' . $key . '.webp') ? 'phones/' : 'screens/';
    $size = @getimagesize($base . $dir . $key . '.webp');
    $w = $size ? (int) $size[0] : 560;
    $h = $size ? (int) $size[1] : 1214;
    $src = content_url('uploads/tac-site/' . $dir . $key . '.webp');
    // Inventra solo existe en Android: cámara perforada en lugar de la isla del iPhone.
    $clase = in_array($key, array('inventra'), true) ? 'tac-phone and' : 'tac-phone';
    return '<div class="' . $clase . '"><span class="pb a"></span><span class="pb b"></span><span class="pb c"></span><div class="scr"><img src="' . esc_url($src) . '"' . tac_srcset($dir . $key . '.webp') . ' alt="' . esc_attr($alt) . '" width="' . $w . '" height="' . $h . '"' . ($eager ? ' fetchpriority="high"' : ' loading="lazy" decoding="async"') . '></div></div>';
}

/** Teléfono plegable (Pixel 9 Pro Fold) que se abre con el scroll: pantalla exterior y pantalla interior reales. */
function tac_fold($key, $alt, $eager = false) {
    $u = function ($f) {
        return content_url('uploads/tac-site/fold/' . $f);
    };
    // arriba de la página (página de la app) se cargan de inmediato: si no, el teléfono cerrado se ve negro un instante
    // Sin loading="lazy": el plegable se ve desde lejos al hacer scroll y la pantalla no puede aparecer negra.
    $carga = $eager ? ' fetchpriority="high"' : ' decoding="async" fetchpriority="low"';
    // proporciones reales de las capturas (ya sin barra de estado) para que el marco se ajuste a ellas
    $dir = trailingslashit(wp_upload_dir()['basedir']) . 'tac-site/fold/';
    $sc = @getimagesize($dir . "$key-cover.webp") ?: array(480, 1027);
    $si = @getimagesize($dir . "$key-inner.webp") ?: array(1040, 1066);
    $cover = '<img src="' . esc_url($u("$key-cover.webp")) . '"' . tac_srcset("fold/$key-cover.webp") . ' alt="" width="' . (int) $sc[0] . '" height="' . (int) $sc[1] . '"' . $carga . '>';
    $inner = '<img src="' . esc_url($u("$key-inner.webp")) . '"' . tac_srcset("fold/$key-inner.webp") . ' alt="" width="' . (int) $si[0] . '" height="' . (int) $si[1] . '"' . ($eager ? '' : ' decoding="async" fetchpriority="low"') . '>';
    $ar = sprintf('--ari:%.4f;--arc:%d/%d', $si[0] / max(1, $si[1]), (int) $sc[0], (int) $sc[1]);
    return '<div class="tac-fold" role="img" style="' . esc_attr($ar) . '" aria-label="' . esc_attr($alt) . '">'
        . '<div class="fd-half fd-r"><div class="fd-scr">' . $inner . '</div><i class="fd-shade"></i></div>'
        . '<div class="fd-half fd-l">'
        . '<div class="fd-face fd-front"><div class="fd-scr">' . $inner . '</div><i class="fd-shade"></i></div>'
        . '<div class="fd-face fd-back"><div class="fd-cover">' . $cover . '</div></div>'
        . '</div>'
        . '<span class="fd-crease"></span>'
        . '</div>';
}

/** Titular con cada palabra animada. Acepta un tramo resaltado entre [[ y ]]. */
function tac_words($text) {
    $out = '';
    $i = 0;
    $parts = preg_split('/(\[\[.*?\]\])/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    foreach ($parts as $part) {
        $hl = strpos($part, '[[') === 0;
        $part = $hl ? substr($part, 2, -2) : $part;
        foreach (preg_split('/\s+/u', trim($part)) as $w) {
            if ($w === '') {
                continue;
            }
            $cls = $hl ? 'w tac-grad-text' : 'w';
            $out .= '<span class="' . $cls . '" style="--i:' . $i . '">' . esc_html($w) . '</span> ';
            $i++;
        }
    }
    return trim($out);
}

/** Cinta de apps (dos filas en sentidos opuestos), leída de apps.json. */
function tac_app_marquee() {
    $file = trailingslashit(wp_upload_dir()['basedir']) . 'alchemist-apps/apps.json';
    $data = is_readable($file) ? json_decode(file_get_contents($file), true) : null;
    if (empty($data['apps'])) {
        return '';
    }
    $lang = tac_lang();
    $pills = array();
    foreach ($data['apps'] as $a) {
        if (empty($a['icono'])) {
            continue;
        }
        $cat = $data['categorias'][$a['categoria']][$lang] ?? '';
        $inner = '<img src="' . esc_url(tac_icon($a['clave'])) . '" alt="" width="34" height="34" loading="lazy"><span>' . esc_html($a['nombre']) . '<small>' . esc_html($cat) . '</small></span>';
        $url = function_exists('tac_app_url') ? tac_app_url($a['clave']) : '';
        $pills[] = $url ? '<a class="tac-app-pill" href="' . esc_url($url) . '">' . $inner . '</a>' : '<span class="tac-app-pill">' . $inner . '</span>';
    }
    $half = (int) ceil(count($pills) / 2);
    $rows = array(array_slice($pills, 0, $half), array_slice($pills, $half));
    $out = '';
    foreach ($rows as $n => $row) {
        $items = implode('', $row);
        $out .= '<div class="tac-marquee' . ($n ? ' rev' : '') . '"><div class="tac-track">' . $items . '<span aria-hidden="true" inert style="display:contents">' . str_replace('<a ', '<a tabindex="-1" ', $items) . '</span></div></div>';
    }
    return $out;
}

/** Manifiesto: cada palabra se enciende con el scroll. Tramos resaltados entre [[ y ]]. */
function tac_manifest($text) {
    $out = '';
    $parts = preg_split('/(\[\[.*?\]\])/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    foreach ($parts as $part) {
        $hl = strpos($part, '[[') === 0;
        $part = $hl ? substr($part, 2, -2) : $part;
        foreach (preg_split('/\s+/u', trim($part)) as $w) {
            if ($w !== '') {
                $out .= '<span class="mw' . ($hl ? ' hl' : '') . '">' . esc_html($w) . '</span> ';
            }
        }
    }
    return trim($out);
}

/* ---------- páginas por app ---------- */

/** Textos SEO de cada app (inc/data/apps_contenido.json, del estudio de ASO). */
function tac_apps_content() {
    static $c = null;
    if ($c === null) {
        $f = __DIR__ . '/data/apps_contenido.json';
        $c = is_readable($f) ? (json_decode(file_get_contents($f), true) ?: array()) : array();
    }
    return $c;
}

/** Datos vivos de las apps (apps.json: nombre, categoría, enlaces comprobados). */
function tac_apps_live() {
    static $a = null;
    if ($a === null) {
        $a = array('apps' => array(), 'categorias' => array());
        $f = trailingslashit(wp_upload_dir()['basedir']) . 'alchemist-apps/apps.json';
        $j = is_readable($f) ? json_decode(file_get_contents($f), true) : null;
        if (is_array($j)) {
            $a['categorias'] = $j['categorias'] ?? array();
            foreach ($j['apps'] ?? array() as $x) {
                $a['apps'][$x['clave']] = $x;
            }
        }
    }
    return $a;
}

/** Mapa app → idioma → ID de página. */
function tac_app_pages() {
    static $m = null;
    if ($m !== null) {
        return $m;
    }
    $m = array();
    $q = new WP_Query(array('post_type' => 'page', 'post_status' => defined('TAC_PREVIEW') ? array('publish', 'private') : 'publish', 'posts_per_page' => 200, 'fields' => 'ids', 'no_found_rows' => true,
        'meta_query' => array(array('key' => 'tac_view', 'value' => 'app'))));
    foreach ($q->posts as $id) {
        $k = get_post_meta($id, 'tac_app', true);
        $l = get_post_meta($id, 'tac_lang', true) === 'en' ? 'en' : 'es';
        if ($k) {
            $m[$k][$l] = (int) $id;
        }
    }
    return $m;
}

function tac_app_url($k, $lang = null) {
    $lang = $lang ?: tac_lang();
    $m = tac_app_pages();
    return !empty($m[$k][$lang]) ? get_permalink($m[$k][$lang]) : '';
}

/** Enlace a la tienda con atribución de campaña (Google Play acepta "referrer"). */
function tac_store_link($k, $store) {
    $live = tac_apps_live()['apps'][$k]['enlaces'] ?? array();
    if (empty($live[$store])) {
        return '';
    }
    $u = $live[$store];
    if ($store === 'android') {
        $u .= '&referrer=' . rawurlencode('utm_source=thealchemistcode.org&utm_medium=web&utm_campaign=app_page&utm_content=' . $k);
    }
    return $u;
}

/** Capturas de tienda de una app (uploads/tac-site/apps/<clave>/N.webp). */
function tac_app_shots($k) {
    $dir = trailingslashit(wp_upload_dir()['basedir']) . 'tac-site/apps/' . $k;
    $out = array();
    for ($i = 1; $i <= 6; $i++) {
        $f = "$dir/$i.webp";
        if (is_readable($f)) {
            $s = @getimagesize($f);
            $out[] = array('src' => content_url("uploads/tac-site/apps/$k/$i.webp"), 'srcset' => tac_srcset("apps/$k/$i.webp"), 'w' => $s ? $s[0] : 420, 'h' => $s ? $s[1] : 910);
        }
    }
    return $out;
}

function tac_schema_category($cat) {
    $map = array('sports' => 'SportsApplication', 'productivity' => 'BusinessApplication', 'utilities' => 'UtilitiesApplication', 'lifestyle' => 'HealthApplication');
    return $map[$cat] ?? 'MobileApplication';
}

/** Pruebas reales (testimonios, clientes, precios) de inc/data/pruebas.json. Vacío = la sección no se muestra. */
function tac_pruebas() {
    static $p = null;
    if ($p === null) {
        $f = __DIR__ . '/data/pruebas.json';
        $p = is_readable($f) ? (json_decode(file_get_contents($f), true) ?: array()) : array();
        $p += array('testimonios' => array(), 'clientes' => array(), 'precios' => array());
    }
    return $p;
}

/** "Desde $X MXN · US$Y" con los precios de pruebas.json, o '' si no hay montos. */
function tac_precio_desde($modelo, $sufijo_es = '', $sufijo_en = '') {
    $pr = tac_pruebas()['precios'][$modelo] ?? array();
    $partes = array();
    if (!empty($pr['mxn'])) {
        $partes[] = '$' . $pr['mxn'] . ' MXN';
    }
    if (!empty($pr['usd'])) {
        $partes[] = 'US$' . $pr['usd'];
    }
    if (!$partes) {
        return '';
    }
    $orden = tac_lang() === 'en' ? array_reverse($partes) : $partes;
    return tac_t('Desde ', 'From ') . implode(' · ', $orden) . tac_t($sufijo_es, $sufijo_en);
}

/** Tarjetas de guías del blog (por slug, solo las publicadas): enlazan cada servicio con su contenido. */
function tac_guias_html($slugs) {
    $out = '';
    foreach ((array) $slugs as $slug) {
        $p = get_page_by_path($slug, OBJECT, 'post');
        if (!$p || $p->post_status !== 'publish') {
            continue;
        }
        $mins = max(3, (int) round(str_word_count(wp_strip_all_tags($p->post_content)) / 220));
        $out .= '<a class="tac-guia" href="' . esc_url(get_permalink($p)) . '" data-reveal>'
            . '<small>' . esc_html(sprintf(tac_t('Guía · %d min', 'Guide · %d min'), $mins)) . '</small>'
            . '<b>' . esc_html(get_the_title($p)) . '</b>'
            . '<span>' . esc_html(wp_trim_words(get_the_excerpt($p), 26)) . '</span></a>';
    }
    return $out ? '<div class="tac-guias">' . $out . '</div>' : '';
}
