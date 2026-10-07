<?php
/**
 * Crea (o actualiza) las páginas de servicio en español (raíz) e inglés (bajo /en/):
 *   infra, movil, ia y flutter. El contenido sale de inc/data/servicios.json del tema.
 *   wp eval-file crear-servicios.php --user=<usuario-admin>
 */
$en_home = get_posts(array('post_type' => 'page', 'numberposts' => 1, 'fields' => 'ids', 'meta_query' => array(array('key' => 'tac_view', 'value' => 'home'), array('key' => 'tac_lang', 'value' => 'en'))));
$en_padre = $en_home ? (int) $en_home[0] : 0;
$paginas = array(
    'infra'   => array('es' => array('Infraestructura y redes', 'infraestructura-y-redes'), 'en' => array('Cloud and network infrastructure', 'cloud-and-network-infrastructure')),
    'movil'   => array('es' => array('Desarrollo de aplicaciones móviles', 'desarrollo-de-aplicaciones-moviles'), 'en' => array('Mobile app development company in Mexico', 'mobile-app-development-company-mexico')),
    'ia'      => array('es' => array('Inteligencia artificial para empresas', 'inteligencia-artificial-para-empresas'), 'en' => array('AI development company in Mexico', 'ai-development-company-mexico')),
    'flutter' => array('es' => array('Desarrollo en Flutter en México', 'desarrollo-flutter-mexico'), 'en' => array('Flutter development company in Mexico', 'flutter-development-company-mexico')),
);
foreach ($paginas as $vista => $idiomas) {
    foreach ($idiomas as $lang => $p) {
        $found = get_posts(array('post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids',
            'meta_query' => array(array('key' => 'tac_view', 'value' => $vista), array('key' => 'tac_lang', 'value' => $lang))));
        $data = array('post_type' => 'page', 'post_status' => 'publish', 'post_title' => $p[0], 'post_name' => $p[1],
            'post_parent' => $lang === 'en' ? $en_padre : 0, 'post_content' => '[tac_page]', 'comment_status' => 'closed');
        if ($found) { $data['ID'] = (int) $found[0]; $id = wp_update_post($data, true); } else { $id = wp_insert_post($data, true); }
        if (is_wp_error($id)) { WP_CLI::warning("$vista/$lang: " . $id->get_error_message()); continue; }
        update_post_meta($id, 'tac_view', $vista);
        update_post_meta($id, 'tac_lang', $lang);
        update_post_meta($id, '_wp_page_template', 'page-landing');
        WP_CLI::log(sprintf('%s/%s #%d %s', $vista, $lang, $id, get_permalink($id)));
    }
}
WP_CLI::success('Páginas de servicio listas.');
