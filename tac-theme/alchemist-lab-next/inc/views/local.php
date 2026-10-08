<?php if (!defined('ABSPATH')) { exit; }
/*
 * Página local: "Desarrollo de software en Puebla" (es) / "Software development company in Mexico" (en).
 * Contenido verificable: casos reales, proceso, formalidad. Sin clientes, plazos ni precios inventados.
 */
$L = tac_lang() === 'en' ? 1 : 0;
$servicios = array(
    array('Software a la medida', 'Custom software',
        'Sistemas web y móviles para la operación de tu empresa: inventario, ventas, pedidos en campo, reportes y paneles para dirección. Si ya tienes un ERP o un sistema anterior, empezamos con una prueba de concepto acotada para validar la integración.',
        'Web and mobile systems that run your operation: inventory, sales, field orders, reporting and leadership dashboards. If you already have an ERP or a legacy system, we start with a scoped proof of concept to validate the integration.',
        array('inventra', 'Inventra')),
    array('Desarrollo de apps móviles', 'Mobile app development',
        'Apps para iOS y Android en Flutter, o nativas en Swift y Kotlin cuando la app lo pide: widgets, notificaciones en tiempo real, modo sin conexión, compras dentro de la app y publicación en App Store y Google Play.',
        'iOS and Android apps in Flutter, or native Swift and Kotlin when the app calls for it: widgets, real-time notifications, offline mode, in-app purchases and release on the App Store and Google Play.',
        array('soccer24', 'Soccer24')),
    array('Inteligencia artificial para empresas', 'AI for business',
        'IA donde ahorra trabajo de verdad: OCR de documentos en el dispositivo, reconocimiento de fotos, asistentes de redacción y automatización de procesos, con los costos por uso bajo control y sin enviar datos que no hace falta.',
        'AI where it actually saves work: on-device document OCR, photo recognition, writing assistants and process automation, with usage costs under control and no data sent that does not need to be.',
        array('pdfmaster', 'PDF Master')),
    array('Infraestructura y redes', 'Infrastructure and networks',
        'Servidores Linux, Google Cloud y Firebase, VPN para sucursales y personal remoto, APIs, monitoreo y respaldos. Operamos nuestra propia infraestructura en producción todos los días.',
        'Linux servers, Google Cloud and Firebase, VPNs for branches and remote staff, APIs, monitoring and backups. We run our own infrastructure in production every day.',
        array('komodo', 'Komodo VPN')),
);
$usos = $L ? array(
    array('US startups and product teams', 'A senior engineer who leads your app from first call to launch, on your hours and in English.'),
    array('Mexican and Latin American companies', 'Custom systems and apps with Mexican invoices (CFDI), contracts in MXN or USD and meetings in Spanish.'),
    array('Companies with an existing app', 'Maintenance, iOS and Android updates, store reviews and new features on an app that is already live.'),
    array('Teams adding AI', 'OCR, image recognition or assistants added to an existing product, with a proof of concept first.'),
) : array(
    array('Comercio y distribución', 'Inventario entre sucursales, ventas, compras y alertas de existencias en el celular, sincronizado en la nube.'),
    array('Manufactura y proveedores', 'Pedidos, inventario de refacciones, listas de verificación en tablet y reportes para dirección, conectados a tu sistema actual.'),
    array('Salud y servicios profesionales', 'Citas, cuestionarios clínicos y reportes en PDF, con el cuidado que piden los datos sensibles.'),
    array('Educación', 'Apps y plataformas para alumnos, padres y docentes, con evaluaciones en línea y notificaciones.'),
    array('Turismo, restaurantes y comercio local', 'Reservas, programas de lealtad y apps propias con notificaciones segmentadas.'),
);
$faq = array(
    array('¿Hacen desarrollo de software a la medida en Puebla?', 'Do you build custom software for companies in Mexico?',
        'Sí. Somos un estudio en Puebla dirigido por su fundador. Desarrollamos software a la medida, apps móviles, IA e infraestructura para empresas de Puebla, del resto de México y de Estados Unidos.',
        'Yes. We are a founder-led studio in Puebla, Mexico. We build custom software, mobile apps, AI and infrastructure for companies in Mexico and the United States.'),
    array('¿Puedo reunirme en persona en Puebla?', 'Do you work on US hours?',
        'Sí, la primera reunión puede ser en persona en Puebla o por videollamada. El resto del proyecto avanza con entregas que instalas en tu propio teléfono.',
        'Yes. Puebla is on UTC−6 all year: the same time as Chicago in winter and one hour behind it in summer, so meetings fit your morning or afternoon.'),
    array('¿Facturan en México?', 'Can I pay in US dollars?',
        'Sí. Emitimos factura CFDI, también cuando el pago es en dólares, por transferencia o tarjeta.',
        'Yes. Contracts, invoices and payments can be in USD by wire or card. Mexican pesos with CFDI invoices are also available.'),
    array('¿El código y la propiedad intelectual son míos?', 'Do I own the code and the IP?',
        'Sí. Al terminar recibes el código, los repositorios, la documentación y los accesos. Firmamos un NDA antes de conocer los detalles, si lo necesitas.',
        'Yes. At handover you receive the code, repositories, documentation and access, with full assignment of IP. We sign your NDA before we see the details.'),
    array('¿Cuánto cuesta desarrollar software o una app?', 'How much does it cost?',
        'Depende del alcance, las plataformas, las integraciones y si lleva IA. La primera llamada y la propuesta no tienen costo: recibes alcance, plazos y precio por escrito antes de empezar.',
        'It depends on scope, platforms, integrations and whether AI is involved. The first call and the proposal are free: you get scope, timeline and price in writing before we start.'),
    array('¿Dan mantenimiento después del lanzamiento?', 'What happens after launch?',
        'Sí. Mantenimiento mensual con tiempos por escrito (falla crítica: respuesta en 4 horas hábiles) y 60 días de garantía de defectos sin costo.',
        'Monthly maintenance with written response times (critical issues: response within 4 business hours) and a 60-day defect warranty at no cost.'),
);
$casos = array(
    array('soccer24', 'Soccer24', '75,685', array('instalaciones en Google Play · 4.5★', 'Google Play installs · 4.5★')),
    array('komodo', 'Komodo VPN', '30,566', array('instalaciones en Google Play', 'Google Play installs')),
    array('inventra', 'Inventra', 'B2B', array('inventario multisucursal en la nube', 'multi-branch inventory in the cloud')),
);
?>
<section class="tac-phero tac-local-hero"><div class="tac-wrap">
  <div class="tac-label" data-reveal><b>—</b> <?php tac_e('Puebla, México', 'Puebla, Mexico'); ?></div>
  <h1 data-reveal style="--d:80ms"><?php tac_e('Desarrollo de software en Puebla: apps, sistemas a la medida e IA', 'Software development company in Mexico: apps, custom systems and AI'); ?></h1>
  <p class="tac-sub" data-reveal style="--d:160ms"><?php tac_e('The Alchemist Code es un estudio de desarrollo de software en Puebla dirigido por su fundador. Diseñamos, programamos, lanzamos y operamos software para empresas de Puebla, del resto de México y de Estados Unidos, con el mismo rigor que aplicamos a las 17 apps que publicamos nosotros mismos.', 'The Alchemist Code is a founder-led software studio in Puebla, Mexico. We design, build, launch and run software for companies in the US and Mexico, held to the same standard as the 17 apps we publish ourselves.'); ?></p>
  <div class="tac-ctas" data-reveal style="--d:240ms">
    <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?> <span class="arr">→</span></a>
    <a class="tac-btn tac-btn-line" href="<?php echo esc_url(tac_url('home') . '#' . tac_t('casos', 'work')); ?>"><?php tac_e('Ver casos', 'See our work'); ?></a>
  </div>
  <?php include __DIR__ . '/_proof.php'; ?>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>01</b> — <?php tac_e('Qué desarrollamos', 'What we build'); ?></div><h2><?php tac_e('Software, apps, IA e infraestructura', 'Software, apps, AI and infrastructure'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Cada servicio se apoya en un producto que ya construimos y operamos en producción.', 'Every service builds on a product we already built and run in production.'); ?></p>
  </div>
  <div class="tac-local-grid">
    <?php foreach ($servicios as $i => $s) : $url = tac_app_url($s[4][0]); ?>
      <article class="tac-local-card" data-reveal style="--d:<?php echo (int) ($i * 80); ?>ms">
        <span class="k"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
        <h3><?php echo esc_html($L ? $s[1] : $s[0]); ?></h3>
        <p><?php echo esc_html($L ? $s[3] : $s[2]); ?></p>
        <a class="ex" href="<?php echo esc_url($url ?: tac_url('apps')); ?>"><img src="<?php echo esc_url(tac_icon($s[4][0])); ?>" alt="" width="28" height="28" loading="lazy"><span><?php tac_e('Hecho por nosotros:', 'Built by us:'); ?> <b><?php echo esc_html($s[4][1]); ?></b></span></a>
      </article>
    <?php endforeach; ?>
  </div>
  <p class="tac-local-more" data-reveal><?php tac_e('Detalle por servicio:', 'Service details:'); ?> <a href="<?php echo esc_url(tac_url('movil')); ?>"><?php tac_e('apps móviles', 'mobile apps'); ?></a> · <a href="<?php echo esc_url(tac_url('flutter')); ?>">Flutter</a> · <a href="<?php echo esc_url(tac_url('ia')); ?>"><?php tac_e('inteligencia artificial', 'AI'); ?></a> · <a href="<?php echo esc_url(tac_url('infra')); ?>"><?php tac_e('infraestructura y redes', 'infrastructure and networks'); ?></a></p>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>02</b> — <?php tac_e('Para quién', 'Who we work with'); ?></div><h2><?php tac_e('Lo que solemos construir para empresas de Puebla', 'Who hires a nearshore studio in Mexico'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Casos de uso frecuentes por sector. Partimos de tu operación, no de la tecnología.', 'The teams that work with us most often, and what they need.'); ?></p>
  </div>
  <dl class="tac-uses">
    <?php foreach ($usos as $i => $u) : ?>
      <div data-reveal style="--d:<?php echo (int) ($i * 60); ?>ms"><dt><?php echo esc_html($u[0]); ?></dt><dd><?php echo esc_html($u[1]); ?></dd></div>
    <?php endforeach; ?>
  </dl>
</div></section>

<section class="tac-sec tac-near-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>03</b> — <?php tac_e('Por qué un estudio en Puebla', 'Why a nearshore studio in Mexico'); ?></div><h2><?php tac_e('Cerca, formal y con el código de tu lado', 'Close, formal, and the IP on your side'); ?></h2></div>
  </div>
  <?php include __DIR__ . '/_nearshore.php'; ?>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>04</b> — <?php tac_e('Resultados', 'Results'); ?></div><h2><?php tac_e('Software que ya está en producción', 'Software already in production'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Cifras de Play Console y de las fichas públicas de las tiendas, octubre de 2026.', 'Figures from Play Console and public store listings, October 2026.'); ?></p>
  </div>
  <div class="tac-local-cases">
    <?php foreach ($casos as $i => $c) : ?>
      <a class="tac-local-case" href="<?php echo esc_url(tac_app_url($c[0]) ?: tac_url('apps')); ?>" data-reveal style="--d:<?php echo (int) ($i * 80); ?>ms">
        <img src="<?php echo esc_url(tac_icon($c[0])); ?>" alt="" width="48" height="48" loading="lazy">
        <b><?php echo esc_html($c[2]); ?></b><span><?php echo esc_html($c[1]); ?> · <?php echo esc_html($c[3][$L]); ?></span>
      </a>
    <?php endforeach; ?>
    <a class="tac-local-case" href="https://suicidologia.mx/irisa/" target="_blank" rel="noopener" data-reveal style="--d:240ms">
      <span class="ic"><?php echo tac_svg('shield'); // phpcs:ignore ?></span>
      <b>IRISA</b><span><?php tac_e('Tamizaje de riesgo suicida en línea · Asociación Mexicana de Suicidología', 'Online suicide-risk screening · Mexican Association of Suicidology'); ?></span>
    </a>
  </div>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>05</b> — <?php tac_e('Cómo trabajamos', 'How we work'); ?></div><h2><?php tac_e('De la primera llamada al lanzamiento, y después', 'From the first call to launch, and beyond'); ?></h2></div>
  </div>
  <?php include __DIR__ . '/_process.php'; ?>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>06</b> — <?php tac_e('Costos', 'Pricing'); ?></div><h2><?php tac_e('¿Cuánto cuesta el desarrollo de software?', 'How much does development cost?'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('El precio depende del alcance, las plataformas (iOS, Android, web, escritorio), las integraciones con tus sistemas y si lleva IA. Lo fijamos por escrito antes de empezar, en uno de estos tres modelos.', 'Price depends on scope, platforms (iOS, Android, web, desktop), integrations with your systems and whether AI is involved. We set it in writing before we start, in one of three models.'); ?></p>
  </div>
  <?php include __DIR__ . '/_engage.php'; ?>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>07</b> — <?php tac_e('Preguntas frecuentes', 'FAQ'); ?></div><h2><?php tac_e('Antes de empezar', 'Before we start'); ?></h2></div>
  </div>
  <div class="tac-faq">
    <?php foreach ($faq as $q) : ?>
      <details data-reveal><summary><?php echo esc_html($L ? $q[1] : $q[0]); ?></summary><p><?php echo esc_html($L ? $q[3] : $q[2]); ?></p></details>
    <?php endforeach; ?>
  </div>
  <p class="tac-local-more" data-reveal><?php tac_e('Más respuestas en', 'More answers in our'); ?> <a href="<?php echo esc_url(tac_url('faq')); ?>"><?php tac_e('preguntas frecuentes', 'FAQ'); ?></a> · <a href="<?php echo esc_url(tac_url('solutions')); ?>"><?php tac_e('soluciones', 'solutions'); ?></a> · <a href="<?php echo esc_url(tac_url('apps')); ?>"><?php tac_e('las 17 apps', 'all 17 apps'); ?></a></p>
</div></section>

<?php $guias = tac_guias_html($L ? array('nearshore-software-development-mexico-guide', 'flutter-vs-native-how-to-choose', 'ai-for-business-practical-uses') : array('como-elegir-empresa-de-desarrollo-de-software-mexico', 'flutter-o-nativo-como-elegir-tecnologia-app', 'ia-para-empresas-mexico-usos-reales', 'como-publicar-app-app-store-google-play')); if ($guias) : ?>
<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>08</b> — <?php tac_e('Guías', 'Guides'); ?></div><h2><?php tac_e('Para decidir con información', 'Read before you decide'); ?></h2></div>
  </div>
  <?php echo $guias; // phpcs:ignore -- escapado en tac_guias_html() ?>
</div></section>
<?php endif; ?>

<?php include __DIR__ . '/_cta.php'; ?>
<script type="application/ld+json"><?php
echo wp_json_encode(array(
    '@context' => 'https://schema.org',
    '@graph'   => array(
        array(
            '@type'       => 'Service',
            'name'        => tac_t('Desarrollo de software en Puebla', 'Software development in Mexico'),
            'serviceType' => tac_t('Desarrollo de software, apps móviles e inteligencia artificial', 'Software, mobile app and AI development'),
            'provider'    => array('@id' => 'https://thealchemistcode.org/#organization'),
            'areaServed'  => $L
                ? array(array('@type' => 'Country', 'name' => 'United States'), array('@type' => 'Country', 'name' => 'Mexico'))
                : array(array('@type' => 'City', 'name' => 'Puebla'), array('@type' => 'State', 'name' => 'Puebla'), array('@type' => 'Country', 'name' => 'México')),
            'url'         => get_permalink(),
        ),
        array(
            '@type'      => 'FAQPage',
            'mainEntity' => array_map(function ($q) use ($L) {
                return array('@type' => 'Question', 'name' => $L ? $q[1] : $q[0], 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $L ? $q[3] : $q[2]));
            }, $faq),
        ),
    ),
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
