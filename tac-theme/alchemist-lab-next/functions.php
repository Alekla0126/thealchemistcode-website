<?php
/**
 * Alchemist Lab — tema de thealchemistcode.org.
 *
 * Las páginas de marca (inicio, soluciones, apps, nosotros, FAQ, contacto, aviso y términos) se pintan
 * desde inc/views/*.php con el shortcode [tac_page]. Cada página guarda dos metadatos:
 *   tac_view  home|solutions|apps|about|faq|contact|privacy|terms
 *   tac_lang  es|en
 * El blog usa las plantillas de bloques normales.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('TAC_VERSION', '4.4.0');
define('TAC_WA', '522221085511');
define('TAC_WA_HUMAN', '+52 222 108 5511');
define('TAC_MAIL', 'contact@thealchemistcode.org');
define('TAC_OWNER', 'Yabin Alejandro Lagunes Cuevas');

require __DIR__ . '/inc/core.php';
require __DIR__ . '/inc/chrome.php';
require __DIR__ . '/inc/contact.php';
require __DIR__ . '/inc/seo.php';

add_action('wp_enqueue_scripts', function () {
    $dir = get_stylesheet_directory();
    wp_enqueue_style('alchemist-lab', get_stylesheet_uri(), array(), (string) filemtime($dir . '/style.css'));
    wp_enqueue_script('lenis', get_stylesheet_directory_uri() . '/assets/js/lenis.min.js', array(), '1.1.20', array('strategy' => 'defer', 'in_footer' => true));
    wp_enqueue_script('alchemist-lab', get_stylesheet_directory_uri() . '/assets/js/site.js', array('lenis'), (string) filemtime($dir . '/assets/js/site.js'), array('strategy' => 'defer', 'in_footer' => true));
});

// Marca <html> con "tac-js" antes de pintar: sin JavaScript, nada queda oculto esperando una animación.
add_action('wp_head', function () {
    // Tema antes de pintar: lo elegido con el botón o, si no, oscuro (es el aspecto por defecto del sitio).
    // En la portada, la intro de marca se muestra una vez por sesión (y nunca con "reducir movimiento").
    $intro = function_exists('tac_current_view') && tac_current_view() === 'home' ? 1 : 0;
    echo "<script>(function(){var d=document.documentElement,t;d.classList.add('tac-js');try{t=localStorage.getItem('tac-theme')}catch(e){}if(t!=='light'&&t!=='dark'){t='dark'}d.setAttribute('data-theme',t);"
        . "try{if(" . $intro . "&&!sessionStorage.getItem('tac-intro')&&!matchMedia('(prefers-reduced-motion: reduce)').matches){d.classList.add('tac-intro-on');sessionStorage.setItem('tac-intro','1')}}catch(e){}})()</script>\n";
    echo '<meta name="theme-color" content="#060D1A">' . "\n";
}, 0);

// Intro de marca (solo se ve si <html> tiene la clase tac-intro-on) y cursor propio.
add_action('wp_body_open', function () {
    if (function_exists('tac_current_view') && tac_current_view() === 'home') {
        echo '<div class="tac-intro" aria-hidden="true"><div class="tac-intro-in"><img src="' . esc_url(get_template_directory_uri() . '/assets/img/logo-256.webp') . '" width="76" height="76" alt=""><span class="tac-intro-w">The Alchemist Code</span><i class="tac-intro-bar"><b></b></i></div></div>' . "\n";
    }
    echo '<div class="tac-cursor" aria-hidden="true"><b><span></span></b><i></i></div>' . "\n";
});

// Precarga de la tipografía principal.
add_action('wp_head', function () {
    echo '<link rel="preload" href="' . esc_url(get_stylesheet_directory_uri() . '/assets/fonts/Jakarta-wght.woff2') . '" as="font" type="font/woff2" crossorigin>' . "\n";
}, 1);

add_action('after_setup_theme', function () {
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
});

// Fuera emojis y oEmbed del <head>: no se usan y cuestan peticiones.
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
