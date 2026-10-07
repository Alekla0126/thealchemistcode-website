<?php
/**
 * Publica la v3 de thealchemistcode.org (correr DESPUÉS de mover alchemist-lab-next a alchemist-lab):
 *  - títulos y descripciones de AIOSEO (estudio SEO y ASO),
 *  - páginas de apps de privadas a públicas,
 *  - entrada de Flutter: slug nuevo (WordPress redirige el viejo) y enlace roto a thealchemistcode.dev.
 *   wp eval-file publicar-v3.php <ruta seo_paginas.json> <ruta apps_contenido.json>
 */
global $wpdb;
$t = $wpdb->prefix . 'aioseo_posts';
$og = content_url('uploads/tac-site/og-thealchemistcode.jpg');
// Imagen para redes por página: og/<app>-<idioma>.jpg y og/estudio-<idioma>.jpg (genera qa/og.py); si falta, la general.
function tac_og_url($nombre, $defecto) {
    return is_readable(wp_upload_dir()['basedir'] . "/tac-site/og/$nombre.jpg") ? content_url("uploads/tac-site/og/$nombre.jpg") : $defecto;
}
$seo = json_decode(file_get_contents($args[0]), true) ?: array();
$apps = json_decode(file_get_contents($args[1]), true) ?: array();
function tac_aioseo_set($id, $title, $desc, $og) {
    global $wpdb; $t = $wpdb->prefix . 'aioseo_posts';
    $row = array('title' => $title, 'description' => $desc, 'og_title' => $title, 'og_description' => $desc,
        'og_image_type' => 'custom_image', 'og_image_custom_url' => $og, 'twitter_use_og' => 1, 'updated' => current_time('mysql'));
    if ($wpdb->get_var($wpdb->prepare("SELECT id FROM $t WHERE post_id=%d", $id))) { $wpdb->update($t, $row, array('post_id' => $id)); }
    else { $wpdb->insert($t, $row + array('post_id' => $id, 'created' => current_time('mysql'))); }
}
$pages = get_posts(array('post_type' => 'page', 'post_status' => array('publish', 'private'), 'numberposts' => -1, 'fields' => 'ids', 'meta_key' => 'tac_view'));
foreach ($pages as $id) {
    $view = get_post_meta($id, 'tac_view', true);
    $lang = get_post_meta($id, 'tac_lang', true) === 'en' ? 'en' : 'es';
    if ($view === 'app') {
        $k = get_post_meta($id, 'tac_app', true);
        if (get_post_status($id) === 'private') { wp_update_post(array('ID' => $id, 'post_status' => 'publish')); }
        if (!empty($apps[$k][$lang]['title'])) { tac_aioseo_set($id, $apps[$k][$lang]['title'], $apps[$k][$lang]['description'] ?? '', tac_og_url("$k-$lang", $og)); }
        WP_CLI::log("app $k/$lang #$id publicada");
        continue;
    }
    if (!empty($seo["$view|$lang"]['title'])) {
        tac_aioseo_set($id, $seo["$view|$lang"]['title'], $seo["$view|$lang"]['description'], tac_og_url("$view-$lang", tac_og_url("estudio-$lang", $og)));
        WP_CLI::log("$view|$lang #$id → " . $seo["$view|$lang"]['title']);
    }
}
// Entrada de Flutter: slug con palabras clave y enlace al contacto vigente.
$p = get_post(1);
if ($p && $p->post_name === 'hello-world') {
    $content = str_replace(array('https://thealchemistcode.dev/contact/', 'http://thealchemistcode.dev/contact/'), tac_url('contact', 'en'), $p->post_content);
    wp_update_post(array('ID' => 1, 'post_name' => 'why-flutter-is-the-future-of-app-development', 'post_content' => $content));
    WP_CLI::log('Entrada 1: ' . get_permalink(1));
}
if (function_exists('aioseo') && isset(aioseo()->core->cache)) { aioseo()->core->cache->clear(); }
WP_CLI::success('v3 publicada en la base de datos.');
