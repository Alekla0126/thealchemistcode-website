<?php
/**
 * Publica (o actualiza) las guías del blog generadas por qa/guias.py.
 *   python3 qa/guias.py > /tmp/guias.json
 *   wp eval-file publicar-guias.php /tmp/guias.json --user=<usuario-admin>
 * Cada guía: entrada publicada, categoría Guías/Guides, metadatos tac_lang y tac_servicio,
 * título y descripción de All in One SEO e imagen para redes og/guia-<slug>.jpg.
 */
global $wpdb;
$guias = json_decode(file_get_contents($args[0]), true) ?: array();
$cats = array(
    'es' => term_exists('guias', 'category') ?: wp_insert_term('Guías', 'category', array('slug' => 'guias')),
    'en' => term_exists('guides', 'category') ?: wp_insert_term('Guides', 'category', array('slug' => 'guides')),
);
$t = $wpdb->prefix . 'aioseo_posts';
foreach ($guias as $g) {
    $existe = get_page_by_path($g['slug'], OBJECT, 'post');
    $data = array(
        'post_type' => 'post', 'post_status' => 'publish', 'post_title' => $g['title'], 'post_name' => $g['slug'],
        'post_content' => $g['content'], 'post_excerpt' => $g['excerpt'], 'comment_status' => 'closed',
        'post_category' => array((int) (is_array($cats[$g['lang']]) ? $cats[$g['lang']]['term_id'] : $cats[$g['lang']])),
    );
    if ($existe) { $data['ID'] = $existe->ID; $id = wp_update_post(wp_slash($data), true); } else { $id = wp_insert_post(wp_slash($data), true); }
    if (is_wp_error($id)) { WP_CLI::warning($g['slug'] . ': ' . $id->get_error_message()); continue; }
    update_post_meta($id, 'tac_lang', $g['lang']);
    update_post_meta($id, 'tac_servicio', $g['servicio']);
    if (!empty($g['par'])) { update_post_meta($id, 'tac_par', $g['par']); }
    $og = is_readable(wp_upload_dir()['basedir'] . "/tac-site/og/guia-{$g['slug']}.jpg") ? content_url("uploads/tac-site/og/guia-{$g['slug']}.jpg") : '';
    $row = array('title' => $g['seo_title'], 'description' => $g['seo_desc'], 'og_title' => $g['title'], 'og_description' => $g['seo_desc'],
        'og_image_type' => $og ? 'custom_image' : 'default', 'og_image_custom_url' => $og, 'twitter_use_og' => 1, 'updated' => current_time('mysql'));
    if ($wpdb->get_var($wpdb->prepare("SELECT id FROM $t WHERE post_id=%d", $id))) { $wpdb->update($t, $row, array('post_id' => $id)); }
    else { $wpdb->insert($t, $row + array('post_id' => $id, 'created' => current_time('mysql'))); }
    WP_CLI::log(sprintf('%s #%d %s', $g['lang'], $id, get_permalink($id)));
}
if (function_exists('aioseo') && isset(aioseo()->core->cache)) { aioseo()->core->cache->clear(); }
WP_CLI::success(count($guias) . ' guías publicadas.');
