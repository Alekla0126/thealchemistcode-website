<?php
/** Barra superior, cabecera y pie (bloques dinámicos usados en parts/). */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    register_block_type('tac/topbar', array('render_callback' => 'tac_render_topbar'));
    register_block_type('tac/header', array('render_callback' => 'tac_render_header'));
    register_block_type('tac/footer', array('render_callback' => 'tac_render_footer'));
    register_block_type('tac/post-end', array('render_callback' => 'tac_render_post_end'));
    register_block_type('tac/blog', array('render_callback' => 'tac_render_blog'));
});

/**
 * Portada del blog: las guías agrupadas por idioma (primero español, luego inglés), con tiempo de lectura
 * y el servicio con el que se relaciona cada una. Sin paginación mientras haya pocas entradas.
 */
function tac_render_blog() {
    $posts = get_posts(array('post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 60, 'orderby' => 'date', 'order' => 'DESC'));
    $grupos = array('es' => array(), 'en' => array());
    foreach ($posts as $p) {
        $grupos[get_post_meta($p->ID, 'tac_lang', true) === 'en' ? 'en' : 'es'][] = $p;
    }
    $srv = tac_servicios();
    $tarjeta = function ($p, $lang) use ($srv) {
        $L = $lang === 'en' ? 1 : 0;
        $mins = max(3, (int) round(str_word_count(wp_strip_all_tags($p->post_content)) / 220));
        $s = (string) get_post_meta($p->ID, 'tac_servicio', true);
        return '<a class="tac-guia" href="' . esc_url(get_permalink($p)) . '" lang="' . esc_attr($lang) . '">'
            . '<small>' . esc_html(sprintf($L ? 'Guide · %d min' : 'Guía · %d min', $mins)) . '</small>'
            . '<b>' . esc_html(get_the_title($p)) . '</b>'
            . '<span>' . esc_html(wp_trim_words(get_the_excerpt($p), 24)) . '</span>'
            . (isset($srv[$s]) ? '<em>' . esc_html($srv[$s][$L]) . '</em>' : '')
            . '</a>';
    };
    ob_start(); ?>
<div class="tac-blog-head">
  <div class="tac-label"><b>—</b> Blog</div>
  <h1><?php tac_e('Guías para contratar software, apps e IA', 'Guides for hiring software, app and AI teams'); ?></h1>
  <p class="tac-sub"><?php tac_e('Lo que preguntan las empresas antes de contratar, respondido con lo que aprendimos al publicar y operar nuestras propias apps.', 'What companies ask before hiring a software team, answered with what we learned shipping and running our own apps.'); ?></p>
  <nav class="tac-blog-lang" aria-label="<?php echo esc_attr(tac_t('Idioma de las guías', 'Guide language')); ?>">
    <a href="#es" hreflang="es">Español <span><?php echo (int) count($grupos['es']); ?></span></a>
    <a href="#en" hreflang="en">English <span><?php echo (int) count($grupos['en']); ?></span></a>
    <a class="costo" href="<?php echo esc_url(tac_url('costo')); ?>"><?php tac_e('¿Cuánto cuesta una app?', 'How much does an app cost?'); ?> →</a>
  </nav>
</div>
<?php foreach ($grupos as $lang => $lista) : if (!$lista) { continue; } ?>
  <section class="tac-blog-grupo" id="<?php echo esc_attr($lang); ?>" lang="<?php echo esc_attr($lang); ?>">
    <h2><?php echo $lang === 'en' ? 'In English' : 'En español'; ?></h2>
    <div class="tac-guias"><?php foreach ($lista as $p) { echo $tarjeta($p, $lang); } // phpcs:ignore -- escapado en $tarjeta ?></div>
  </section>
<?php endforeach;
    return ob_get_clean();
}

/** Servicios del estudio: vista → nombre en cada idioma (pie, cierre de artículos y schema). */
function tac_servicios() {
    return array(
        'local'   => array('Desarrollo de software en Puebla', 'Software development company in Mexico'),
        'movil'   => array('Desarrollo de apps móviles', 'Mobile app development'),
        'flutter' => array('Desarrollo en Flutter', 'Flutter development'),
        'ia'      => array('Inteligencia artificial para empresas', 'AI development'),
        'infra'   => array('Infraestructura y redes', 'Infrastructure and networks'),
    );
}

/**
 * Cierre de cada artículo del blog: el servicio relacionado (metadato tac_servicio), los demás servicios
 * y la invitación a iniciar un proyecto. Quien llega por una guía tiene a un clic la página que vende.
 */
function tac_render_post_end() {
    if (!is_singular('post')) {
        return '';
    }
    $L = tac_lang() === 'en' ? 1 : 0;
    $srv = (string) get_post_meta(get_the_ID(), 'tac_servicio', true);
    $todo = tac_servicios();
    $datos = json_decode((string) file_get_contents(get_template_directory() . '/inc/data/servicios.json'), true) ?: array();
    ob_start(); ?>
<div class="tac-post-end">
  <?php if ($srv && isset($todo[$srv])) :
      $intro = $datos[$srv]['intro'][$L] ?? tac_t('Estudio en Puebla dirigido por su fundador: apps iOS y Android, sistemas a la medida e IA para empresas de México y Estados Unidos.', 'A founder-led studio in Puebla, Mexico: iOS and Android apps, custom systems and AI for companies in the US and Mexico.'); ?>
    <a class="tac-post-srv" href="<?php echo esc_url(tac_url($srv)); ?>">
      <span class="tac-label"><?php tac_e('Servicio relacionado', 'Related service'); ?></span>
      <b><?php echo esc_html($todo[$srv][$L]); ?> <span aria-hidden="true">→</span></b>
      <span class="d"><?php echo esc_html(wp_trim_words($intro, 34)); ?></span>
    </a>
  <?php endif; ?>
  <div class="tac-post-cta">
    <h2><?php tac_e('¿Qué tiene que salir bien en tu proyecto?', 'What does success look like for your product?'); ?></h2>
    <p><?php tac_e('La primera llamada y la propuesta no tienen costo. Te responde en persona quien va a dirigir tu proyecto.', 'The first call and the proposal are free. The person who will lead your project replies in person.'); ?></p>
    <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?> <span class="arr">→</span></a>
  </div>
  <nav class="tac-post-more" aria-label="<?php echo esc_attr(tac_t('Servicios', 'Services')); ?>">
    <?php foreach ($todo as $v => $t) : if ($v === $srv) { continue; } ?>
      <a href="<?php echo esc_url(tac_url($v)); ?>"><?php echo esc_html($t[$L]); ?></a>
    <?php endforeach; ?>
    <a class="back" href="<?php echo esc_url(tac_url('blog')); ?>">← Blog</a>
  </nav>
</div>
<?php
    return ob_get_clean();
}

function tac_render_topbar() {
    // Relojes en vivo (los actualiza site.js): el comprador de EE. UU. ve de un vistazo cuánto se empalma el horario.
    $zonas = array(
        array('Puebla', 'America/Mexico_City'),
        array(tac_t('Chicago', 'Chicago'), 'America/Chicago'),
        array(tac_t('Nueva York', 'New York'), 'America/New_York'),
        array(tac_t('Los Ángeles', 'Los Angeles'), 'America/Los_Angeles'),
    );
    ob_start(); ?>
<div class="tac-topbar">
  <div class="tac-wrap">
    <span class="tac-clocks" aria-label="<?php echo esc_attr(tac_t('Hora local', 'Local time')); ?>">
      <?php foreach ($zonas as $i => $z) :
          $t = new DateTime('now', new DateTimeZone($z[1])); ?>
        <span class="<?php echo $i ? 'tac-hide-md' : ''; ?>"><i><?php echo esc_html($z[0]); ?></i> <time data-tz="<?php echo esc_attr($z[1]); ?>"><?php echo esc_html($t->format('H:i')); ?></time></span>
      <?php endforeach; ?>
    </span>
    <span class="sp tac-hide-md"><?php tac_e('Contratos en MXN o USD · Factura CFDI', 'Contracts in USD or MXN · NDA before details'); ?></span>
    <a href="mailto:<?php echo esc_attr(TAC_MAIL); ?>"><?php echo esc_html(TAC_MAIL); ?></a>
  </div>
</div>
<?php
    return ob_get_clean();
}

function tac_menu_items() {
    $home = tac_url('home');
    return array(
        array(tac_t('Soluciones', 'Solutions'), tac_url('solutions'), 'solutions'),
        array(tac_t('Casos', 'Work'), $home . '#' . tac_t('casos', 'work'), ''),
        array('Apps', tac_url('apps'), 'apps'),
        array(tac_t('Cómo trabajamos', 'How we work'), $home . '#' . tac_t('proceso', 'process'), ''),
        array(tac_t('Nosotros', 'About'), tac_url('about'), 'about'),
        array('Blog', tac_url('blog'), 'blog'),
    );
}

function tac_render_header() {
    $view = tac_current_view();
    if (!$view && (is_home() || is_singular('post') || is_archive())) {
        $view = 'blog';
    }
    if ($view === 'app') {
        $view = 'apps';
    }
    $lang = tac_lang();
    ob_start(); ?>
<div class="tac-progress" aria-hidden="true"></div>
<div class="tac-h">
  <div class="tac-nav">
    <div class="tac-wrap">
      <a class="tac-brand" href="<?php echo esc_url(tac_url('home')); ?>"><?php echo tac_logo(); ?>The Alchemist Code</a>
      <nav class="tac-menu" aria-label="<?php echo esc_attr(tac_t('Principal', 'Main')); ?>">
        <?php foreach (tac_menu_items() as $it) : ?>
          <a href="<?php echo esc_url($it[1]); ?>"<?php echo ($it[2] && $it[2] === $view) ? ' class="on" aria-current="page"' : ''; ?>><?php echo esc_html($it[0]); ?></a>
        <?php endforeach; ?>
      </nav>
      <button class="tac-theme" type="button" data-theme-toggle aria-pressed="false" aria-label="<?php echo esc_attr(tac_t('Cambiar entre modo claro y oscuro', 'Toggle light and dark mode')); ?>"><svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4.2"/><path d="M12 2.5v2.2M12 19.3v2.2M4.6 4.6l1.6 1.6M17.8 17.8l1.6 1.6M2.5 12h2.2M19.3 12h2.2M4.6 19.4l1.6-1.6M17.8 6.2l1.6-1.6"/></svg><svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5a8.5 8.5 0 1 0 10.7 10.7Z"/></svg></button>
      <div class="tac-lang">
        <a href="<?php echo esc_url(tac_counterpart_url('es')); ?>" hreflang="es" lang="es"<?php echo $lang === 'es' ? ' class="on"' : ''; ?>>ES</a>
        <a href="<?php echo esc_url(tac_counterpart_url('en')); ?>" hreflang="en" lang="en"<?php echo $lang === 'en' ? ' class="on"' : ''; ?>>EN</a>
      </div>
      <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?></a>
      <button class="tac-burger" type="button" aria-expanded="false" aria-controls="tac-drawer" aria-label="<?php echo esc_attr(tac_t('Menú', 'Menu')); ?>" onclick="var d=document.getElementById('tac-drawer');var o=d.classList.toggle('open');this.setAttribute('aria-expanded',o)"><?php echo tac_svg('menu'); ?></button>
    </div>
  </div>
  <nav class="tac-drawer" id="tac-drawer" aria-label="<?php echo esc_attr(tac_t('Menú móvil', 'Mobile menu')); ?>">
    <?php foreach (tac_menu_items() as $it) : ?>
      <a href="<?php echo esc_url($it[1]); ?>"><?php echo esc_html($it[0]); ?></a>
    <?php endforeach; ?>
    <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?></a>
  </nav>
</div>
<?php
    return ob_get_clean();
}

function tac_render_footer() {
    ob_start(); ?>
<div class="tac-f">
  <div class="tac-wrap">
    <div class="tac-fgrid">
      <div>
        <a class="tac-brand" href="<?php echo esc_url(tac_url('home')); ?>"><?php echo tac_logo(); ?>The Alchemist Code</a>
        <p><?php tac_e('Estudio de desarrollo de apps e IA para empresas. Puebla, México.', 'App and AI development studio for businesses. Puebla, Mexico.'); ?></p>
      </div>
      <div><h2><?php tac_e('Servicios', 'Services'); ?></h2><ul>
        <li><a href="<?php echo esc_url(tac_url('local')); ?>"><?php tac_e('Desarrollo de software en Puebla', 'Software development in Mexico'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('movil')); ?>"><?php tac_e('Apps móviles', 'Mobile apps'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('flutter')); ?>">Flutter</a></li>
        <li><a href="<?php echo esc_url(tac_url('ia')); ?>"><?php tac_e('Inteligencia artificial', 'AI development'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('infra')); ?>"><?php tac_e('Infraestructura y redes', 'Infrastructure and networks'); ?></a></li>
      </ul></div>
      <div><h2><?php tac_e('Estudio', 'Studio'); ?></h2><ul>
        <li><a href="<?php echo esc_url(tac_url('solutions')); ?>"><?php tac_e('Soluciones', 'Solutions'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('home') . '#' . tac_t('casos', 'work')); ?>"><?php tac_e('Casos', 'Work'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('apps')); ?>">Apps</a></li>
        <li><a href="<?php echo esc_url(tac_url('about')); ?>"><?php tac_e('Nosotros', 'About'); ?></a></li>
      </ul></div>
      <div><h2><?php tac_e('Recursos', 'Resources'); ?></h2><ul>
        <li><a href="<?php echo esc_url(tac_url('costo')); ?>"><?php tac_e('¿Cuánto cuesta una app?', 'How much does an app cost?'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('blog')); ?>"><?php tac_e('Blog y guías', 'Blog and guides'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('faq')); ?>"><?php tac_e('Preguntas frecuentes', 'FAQ'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Contacto', 'Contact'); ?></a></li>
      </ul></div>
      <div><h2>Legal</h2><ul>
        <li><a href="<?php echo esc_url(tac_url('privacy')); ?>"><?php tac_e('Aviso de privacidad', 'Privacy notice'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('terms')); ?>"><?php tac_e('Términos', 'Terms'); ?></a></li>
        <li><a href="<?php echo esc_url(tac_url('privacy') . '#cookies'); ?>">Cookies</a></li>
      </ul></div>
    </div>
    <div class="tac-fword" aria-hidden="true">The Alchemist Code</div>
    <div class="tac-fbottom">
      <span>© <?php echo esc_html(gmdate('Y')); ?> The Alchemist Code · <?php tac_e('nombre comercial de ' . TAC_OWNER . ', persona física con actividad empresarial. Puebla, México.', 'trade name of ' . TAC_OWNER . ', Mexican sole proprietor. Puebla, Mexico.'); ?></span>
      <span><?php echo esc_html(TAC_MAIL); ?> · WhatsApp <?php echo esc_html(TAC_WA_HUMAN); ?></span>
    </div>
  </div>
</div>

<?php
    return ob_get_clean();
}
