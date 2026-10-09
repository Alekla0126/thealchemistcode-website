<?php
/**
 * Crea (o actualiza) el índice de casos (/casos/, /en/work/) y una página por caso de inc/data/casos.json,
 * con título, descripción e imagen para redes en All in One SEO.
 *   wp eval-file crear-casos.php wp-content/themes/alchemist-lab/inc/data/casos.json --user=<usuario-admin>
 */
global $wpdb;
$casos = json_decode(file_get_contents($args[0]), true) ?: array();
unset($casos['_nota']);
$t = $wpdb->prefix . 'aioseo_posts';
$en_home = get_posts(array('post_type' => 'page', 'numberposts' => 1, 'fields' => 'ids', 'meta_query' => array(array('key' => 'tac_view', 'value' => 'home'), array('key' => 'tac_lang', 'value' => 'en'))));
$guardar = function ($vista, $lang, $titulo, $slug, $padre, $extra = array()) {
    $meta = array(array('key' => 'tac_view', 'value' => $vista), array('key' => 'tac_lang', 'value' => $lang));
    foreach ($extra as $mk => $mv) { $meta[] = array('key' => $mk, 'value' => $mv); }
    $found = get_posts(array('post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'meta_query' => $meta));
    $data = array('post_type' => 'page', 'post_status' => 'publish', 'post_title' => $titulo, 'post_name' => $slug,
        'post_parent' => $padre, 'post_content' => '[tac_page]', 'comment_status' => 'closed');
    if ($found) { $data['ID'] = (int) $found[0]; $id = wp_update_post($data, true); } else { $id = wp_insert_post($data, true); }
    if (is_wp_error($id)) { WP_CLI::warning("$vista/$lang: " . $id->get_error_message()); return 0; }
    update_post_meta($id, 'tac_view', $vista);
    update_post_meta($id, 'tac_lang', $lang);
    update_post_meta($id, '_wp_page_template', 'page-landing');
    foreach ($extra as $mk => $mv) { update_post_meta($id, $mk, $mv); }
    WP_CLI::log(sprintf('%s/%s #%d %s', $vista, $lang, $id, get_permalink($id)));
    return $id;
};
$indice = array(
    'es' => $guardar('casos', 'es', 'Casos', 'casos', 0),
    'en' => $guardar('casos', 'en', 'Work', 'work', $en_home ? (int) $en_home[0] : 0),
);
foreach ($casos as $k => $c) {
    foreach (array('es' => 0, 'en' => 1) as $lang => $L) {
        $id = $guardar('caso', $lang, $c['nombre'], $c['slug'], $indice[$lang], array('tac_caso' => $k));
        if (!$id) { continue; }
        $og = is_readable(wp_upload_dir()['basedir'] . "/tac-site/og/{$c['app']}-$lang.jpg") ? content_url("uploads/tac-site/og/{$c['app']}-$lang.jpg") : '';
        $row = array('title' => $c['seo_title'][$L], 'description' => $c['seo_desc'][$L], 'og_title' => $c['seo_title'][$L], 'og_description' => $c['seo_desc'][$L],
            'og_image_type' => $og ? 'custom_image' : 'default', 'og_image_custom_url' => $og, 'twitter_use_og' => 1, 'updated' => current_time('mysql'));
        if ($wpdb->get_var($wpdb->prepare("SELECT id FROM $t WHERE post_id=%d", $id))) { $wpdb->update($t, $row, array('post_id' => $id)); }
        else { $wpdb->insert($t, $row + array('post_id' => $id, 'created' => current_time('mysql'))); }
    }
}
if (function_exists('aioseo') && isset(aioseo()->core->cache)) { aioseo()->core->cache->clear(); }
WP_CLI::success('Casos listos.');
