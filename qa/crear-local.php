<?php
/**
 * Crea (o actualiza) las páginas locales: /desarrollo-de-software-puebla/ (es) y /en/software-development-company-mexico/ (en).
 *   wp eval-file crear-local.php --user=<usuario-admin>
 */
$en_home = get_posts(array('post_type' => 'page', 'numberposts' => 1, 'fields' => 'ids', 'meta_query' => array(array('key' => 'tac_view', 'value' => 'home'), array('key' => 'tac_lang', 'value' => 'en'))));
$paginas = array(
    'es' => array('Desarrollo de software en Puebla', 'desarrollo-de-software-puebla', 0),
    'en' => array('Software development company in Mexico', 'software-development-company-mexico', $en_home ? (int) $en_home[0] : 0),
);
foreach ($paginas as $lang => $p) {
    $found = get_posts(array('post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids',
        'meta_query' => array(array('key' => 'tac_view', 'value' => 'local'), array('key' => 'tac_lang', 'value' => $lang))));
    $data = array('post_type' => 'page', 'post_status' => 'publish', 'post_title' => $p[0], 'post_name' => $p[1],
        'post_parent' => $p[2], 'post_content' => '[tac_page]', 'comment_status' => 'closed');
    if ($found) { $data['ID'] = (int) $found[0]; $id = wp_update_post($data, true); } else { $id = wp_insert_post($data, true); }
    if (is_wp_error($id)) { WP_CLI::warning("$lang: " . $id->get_error_message()); continue; }
    update_post_meta($id, 'tac_view', 'local');
    update_post_meta($id, 'tac_lang', $lang);
    update_post_meta($id, '_wp_page_template', 'page-landing');
    WP_CLI::log(sprintf('%s #%d %s', $lang, $id, get_permalink($id)));
}
WP_CLI::success('Páginas locales listas.');
