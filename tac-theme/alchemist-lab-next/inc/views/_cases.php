<?php if (!defined('ABSPATH')) { exit; }
/*
 * Casos seleccionados con estructura fija: rol, plataforma, año, reto, qué hicimos, resultados (con fuente) y stack.
 * Cifras comprobadas el 7-oct-2026 (Play Console, fichas públicas de Google Play y App Store, suicidologia.mx).
 */
$casos = array(
    array(
        'k' => 'soccer24', 'icon' => 'soccer24', 'phones' => array('soccer24', 'clutch'), 'media' => 'dark',
        'titulo' => array('Soccer24: 75,685 instalaciones y 4.5★ en Google Play', 'Soccer24: 75,685 installs and a 4.5★ Google Play rating'),
        'meta' => array(
            array(array('Rol', 'Role'), array('Producto propio: diseño, desarrollo y operación', 'Own product: design, build and operations')),
            array(array('Plataforma', 'Platform'), array('iOS · Android · widgets', 'iOS · Android · widgets')),
            array(array('Año', 'Year'), array('2024 — hoy', '2024 — today')),
        ),
        'reto' => array('Dar resultados en vivo de más de 1,100 competiciones en el segundo en que ocurre el gol.', 'Deliver live results from 1,100+ competitions the second a goal happens.'),
        'hicimos' => array('Apps en Flutter con widgets nativos en Swift y Kotlin, notificaciones en tiempo real, caché sin conexión y fichas de tienda optimizadas por país. La misma base impulsa Clutch, de básquetbol.', 'Flutter apps with native Swift and Kotlin widgets, real-time notifications, an offline cache and store listings optimized per country. The same foundation powers Clutch, for basketball.'),
        'metricas' => array(
            array('75,685', array('instalaciones en Google Play', 'Google Play installs'), array('Play Console, sep 2026', 'Play Console, Sep 2026'), 75685),
            array('4.5★', array('calificación pública en Google Play', 'public Google Play rating'), array('Google Play, oct 2026', 'Google Play, Oct 2026'), 0),
            array('1,100+', array('competiciones en vivo', 'live competitions'), array('soccer24.website', 'soccer24.website'), 0),
        ),
        'stack' => array('Flutter', 'Swift', 'Kotlin', 'Firebase', 'Firestore'),
        'link' => array(tac_app_url('soccer24'), array('Ver la app', 'See the app')),
    ),
    array(
        'k' => 'komodo', 'icon' => 'komodo', 'phones' => array('komodo'), 'media' => 'dark',
        'titulo' => array('Komodo VPN: 30,566 instalaciones sobre una red de servidores propia', 'Komodo VPN: 30,566 installs on a server network we run'),
        'meta' => array(
            array(array('Rol', 'Role'), array('Producto propio: app, servidores y operación', 'Own product: app, servers and operations')),
            array(array('Plataforma', 'Platform'), array('iOS · Android', 'iOS · Android')),
            array(array('Año', 'Year'), array('2025 — hoy', '2025 — today')),
        ),
        'reto' => array('Cifrar la conexión en redes Wi-Fi públicas con un solo toque, sobre servidores que controlamos de punta a punta.', 'Encrypt the connection on public Wi-Fi with a single tap, on servers we control end to end.'),
        'hicimos' => array('App en Flutter sobre OpenVPN, red de servidores propia con cambio automático cuando uno falla, y suscripciones en App Store y Google Play.', 'A Flutter app on OpenVPN, our own server network with automatic failover, and subscriptions on the App Store and Google Play.'),
        'metricas' => array(
            array('30,566', array('instalaciones en Google Play', 'Google Play installs'), array('Play Console, sep 2026', 'Play Console, Sep 2026'), 30566),
            array('3.0', array('versión mayor en App Store', 'major version on the App Store'), array('App Store, oct 2026', 'App Store, Oct 2026'), 0),
        ),
        'stack' => array('Flutter', 'OpenVPN', tac_t('Servidores Linux', 'Linux servers'), tac_t('Compras dentro de la app', 'In-app purchases')),
        'link' => array(tac_app_url('komodo'), array('Ver la app', 'See the app')),
    ),
    array(
        'k' => 'inventra', 'icon' => 'inventra', 'phones' => array('inventra'), 'media' => 'light',
        'titulo' => array('Inventra: inventario multisucursal que se sincroniza en la nube', 'Inventra: multi-branch inventory that syncs in the cloud'),
        'meta' => array(
            array(array('Rol', 'Role'), array('Producto propio B2B: base para sistemas a la medida', 'Own B2B product: a base for custom systems')),
            array(array('Plataforma', 'Platform'), array('Android', 'Android')),
            array(array('Estado', 'Status'), array('En Google Play', 'Live on Google Play')),
        ),
        'reto' => array('Pymes que llevan el inventario en Excel y no saben qué tienen en cada sucursal.', 'Small businesses tracking stock in spreadsheets, blind to what each branch holds.'),
        'hicimos' => array('App multiplataforma con sincronización en la nube: productos, sucursales, ventas, compras, lector de códigos y alertas de existencias. Se adapta a los procesos de cada empresa en lugar de empezar de cero.', 'A cross-platform app with cloud sync: products, branches, sales, purchases, barcode scanning and low-stock alerts. It adapts to each company’s processes instead of starting from scratch.'),
        'metricas' => array(),
        'stack' => array('Flutter', 'Cloud Firestore', tac_t('Lector de códigos', 'Barcode scanning')),
        'link' => array(tac_app_url('inventra'), array('Ver la app', 'See the app')),
    ),
    array(
        'k' => 'irisa', 'icon' => '', 'phones' => array(), 'media' => 'browser',
        'titulo' => array('IRISA: tamizaje de riesgo suicida en línea para la Asociación Mexicana de Suicidología', 'IRISA: online suicide-risk screening for the Mexican Association of Suicidology'),
        'meta' => array(
            array(array('Cliente', 'Client'), array('Asociación Mexicana de Suicidología, A.C.', 'Mexican Association of Suicidology')),
            array(array('Rol', 'Role'), array('Mantenimiento y nuevas funciones', 'Maintenance and new features')),
            array(array('Año', 'Year'), array('2021', '2021')),
        ),
        'reto' => array('Un instrumento clínico que profesionales de salud mental aplican en línea, desde cualquier dispositivo: 50 reactivos, de 10 a 15 minutos y cinco niveles de riesgo.', 'A clinical instrument that mental-health professionals run online from any device: 50 items, 10 to 15 minutes and five risk levels.'),
        'hicimos' => array('Mantenimiento, desarrollo de nuevas funciones y seguimiento de la aplicación web que usan los profesionales registrados con cédula, con datos clínicos sensibles de por medio.', 'Maintenance, new features and follow-up for the web app used by licensed professionals, with sensitive clinical data involved.'),
        'metricas' => array(),
        'stack' => array(tac_t('Aplicación web', 'Web app'), tac_t('Reportes PDF y Excel', 'PDF and Excel reports'), tac_t('Datos clínicos', 'Clinical data')),
        'link' => array('https://suicidologia.mx/irisa/', array('Ver IRISA', 'See IRISA')),
        'nota' => array('Captura publicada por la AMS; los datos del ejemplo están difuminados.', 'Screenshot published by the AMS; sample data blurred.'),
    ),
);
?>
<div class="tac-wrap tac-cases">
<?php foreach ($casos as $i => $c) : $L = tac_lang() === 'en' ? 1 : 0; ?>
  <article class="tac-prod tac-case<?php echo $c['media'] === 'light' || $c['media'] === 'browser' ? ' flip' : ''; ?>" data-reveal>
    <div class="media<?php echo $c['media'] === 'light' ? ' light' : ($c['media'] === 'browser' ? ' light browser' : ''); ?>">
      <?php if ($c['media'] === 'browser') : ?>
        <figure class="tac-browser" aria-label="IRISA">
          <div class="bar"><i></i><i></i><i></i><span>suicidologia.mx/irisa</span></div>
          <img src="<?php echo esc_url(tac_img('cases/irisa.webp')); ?>"<?php echo tac_srcset('cases/irisa.webp'); // phpcs:ignore ?> width="900" height="506" alt="<?php echo esc_attr(tac_t('Sumario de resultados de IRISA', 'IRISA results summary')); ?>" loading="lazy" decoding="async">
        </figure>
        <p class="tac-case-note"><?php echo esc_html($c['nota'][$L]); ?></p>
      <?php else : foreach ($c['phones'] as $ph) { echo tac_phone($ph, ucfirst($ph)); } endif; // phpcs:ignore ?>
    </div>
    <div class="body">
      <div class="head">
        <?php if ($c['icon']) : ?><img src="<?php echo esc_url(tac_icon($c['icon'])); ?>" alt="" width="54" height="54"><?php endif; ?>
        <span class="tac-case-n"><?php echo esc_html(sprintf('%02d / %02d', $i + 1, count($casos))); ?></span>
      </div>
      <h3 class="tac-case-t"><?php echo esc_html($c['titulo'][$L]); ?></h3>
      <dl class="tac-case-meta">
        <?php foreach ($c['meta'] as $m) : ?><div><dt><?php echo esc_html($m[0][$L]); ?></dt><dd><?php echo esc_html($m[1][$L]); ?></dd></div><?php endforeach; ?>
      </dl>
      <dl class="tac-rows">
        <div><dt><?php tac_e('Reto', 'Challenge'); ?></dt><dd><?php echo esc_html($c['reto'][$L]); ?></dd></div>
        <div><dt><?php tac_e('Qué hicimos', 'What we did'); ?></dt><dd><?php echo esc_html($c['hicimos'][$L]); ?></dd></div>
      </dl>
      <?php if ($c['metricas']) : ?>
        <div class="tac-metrics">
          <?php foreach ($c['metricas'] as $m) : ?>
            <div><b<?php echo $m[3] ? ' data-count="' . (int) $m[3] . '"' : ''; ?>><?php echo esc_html($m[0]); ?></b><span><?php echo esc_html($m[1][$L]); ?></span><small><?php echo esc_html($m[2][$L]); ?></small></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="tac-case-foot">
        <ul class="tac-stack"><?php foreach ($c['stack'] as $s) : ?><li><?php echo esc_html($s); ?></li><?php endforeach; ?></ul>
        <?php if ($caso_url = tac_caso_url($c['k'])) : ?><a class="tac-case-link tac-case-full" href="<?php echo esc_url($caso_url); ?>"><?php tac_e('Caso completo', 'Full case study'); ?> <span aria-hidden="true">→</span></a><?php endif; ?>
        <a class="tac-case-link" href="<?php echo esc_url($c['link'][0]); ?>"<?php echo strpos($c['link'][0], home_url()) === 0 ? '' : ' target="_blank" rel="noopener"'; ?>><?php echo esc_html($c['link'][1][$L]); ?> <span aria-hidden="true">→</span></a>
      </div>
    </div>
  </article>
<?php endforeach; ?>
</div>

