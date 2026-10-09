<?php if (!defined('ABSPATH')) { exit; }
/*
 * "¿Cuánto cuesta una app?": qué mueve el precio y un armador de proyecto que manda el resumen al contacto.
 * Sin montos inventados: los precios "desde" salen de pruebas.json (modelos de contratación) cuando el dueño los defina.
 * La complejidad que calcula el armador es orientativa y así se dice en la página.
 */
$L = tac_lang() === 'en' ? 1 : 0;
// grupo → [título, tipo (multi|uno), opciones: clave → [es, en, peso, necesidad del formulario]]
$grupos = array(
    'plataformas' => array(array('Plataformas', 'Platforms'), 'multi', array(
        'ios'        => array('iPhone (iOS)', 'iPhone (iOS)', 2, 'nueva'),
        'android'    => array('Android', 'Android', 2, 'nueva'),
        'web'        => array('Web o panel en el navegador', 'Web app or browser dashboard', 2, 'nueva'),
        'escritorio' => array('Escritorio (macOS, Windows, Linux)', 'Desktop (macOS, Windows, Linux)', 2, 'nueva'),
    )),
    'funciones' => array(array('Funciones', 'Features'), 'multi', array(
        'cuentas'   => array('Cuentas e inicio de sesión', 'Accounts and sign-in', 1, ''),
        'pagos'     => array('Pagos o suscripciones', 'Payments or subscriptions', 2, ''),
        'avisos'    => array('Notificaciones', 'Notifications', 1, ''),
        'mapas'     => array('Mapas y ubicación', 'Maps and location', 2, ''),
        'chat'      => array('Chat o mensajes', 'Chat or messaging', 2, ''),
        'offline'   => array('Funcionar sin conexión', 'Works offline', 2, ''),
        'admin'     => array('Panel de administración', 'Admin dashboard', 2, ''),
        'reportes'  => array('Reportes y exportar a Excel o PDF', 'Reports and Excel/PDF export', 1, ''),
        'camara'    => array('Cámara o lector de códigos', 'Camera or barcode scanning', 1, ''),
        'idiomas'   => array('Varios idiomas', 'Multiple languages', 1, ''),
    )),
    'integraciones' => array(array('Conexiones con otros sistemas', 'Integrations'), 'multi', array(
        'erp'       => array('Mi ERP o sistema actual', 'My ERP or current system', 3, 'integracion'),
        'cfdi'      => array('Facturación CFDI', 'Mexican e-invoicing (CFDI)', 2, 'integracion'),
        'pasarela'  => array('Pasarela de pagos', 'Payment gateway', 2, 'integracion'),
        'mensajes'  => array('WhatsApp, SMS o correo', 'WhatsApp, SMS or email', 1, 'integracion'),
        'api'       => array('API de terceros', 'Third-party APIs', 2, 'integracion'),
    )),
    'ia' => array(array('Inteligencia artificial', 'Artificial intelligence'), 'multi', array(
        'ocr'       => array('Leer documentos (OCR)', 'Read documents (OCR)', 2, 'ia'),
        'vision'    => array('Reconocer imágenes', 'Recognize images', 3, 'ia'),
        'asistente' => array('Asistente o chatbot', 'Assistant or chatbot', 2, 'ia'),
        'prediccion'=> array('Clasificar o predecir', 'Classify or predict', 3, 'ia'),
    )),
    'punto' => array(array('Punto de partida', 'Starting point'), 'uno', array(
        'cero'      => array('Idea, sin diseño', 'An idea, no design yet', 3, 'nueva'),
        'diseno'    => array('Ya tengo el diseño', 'I already have designs', 1, 'nueva'),
        'existente' => array('Tengo una app y quiero mejorarla', 'I have an app to improve', 2, 'mejorar'),
    )),
    'plazo' => array(array('¿Para cuándo?', 'Timeline'), 'uno', array(
        'pronto'    => array('Lo antes posible', 'As soon as possible', 0, ''),
        'trimestre' => array('En 1 a 3 meses', 'In 1 to 3 months', 0, ''),
        'sinprisa'  => array('Sin prisa', 'No rush', 0, ''),
    )),
);
$factores = array(
    array('Plataformas', 'Platforms', 'iPhone, Android, web o escritorio. Con Flutter, iOS y Android comparten casi todo el código, así que la segunda plataforma cuesta mucho menos que hacer dos apps.', 'iPhone, Android, web or desktop. With Flutter, iOS and Android share almost all of the code, so the second platform costs far less than building two apps.'),
    array('Pantallas y funciones', 'Screens and features', 'Cada función tiene su peso: pagos, mapas, chat o modo sin conexión suman más que una lista o un formulario.', 'Each feature has its own weight: payments, maps, chat or offline mode add more than a list or a form.'),
    array('Backend e integraciones', 'Backend and integrations', 'Conectar tu ERP, facturación o pagos suele ser lo que más varía, porque depende de cómo esté hecho el otro sistema.', 'Connecting your ERP, invoicing or payments is usually the most variable part, because it depends on how the other system is built.'),
    array('Diseño', 'Design', 'Partir de una idea requiere diseño de producto; partir de pantallas aprobadas acorta el camino.', 'Starting from an idea needs product design; starting from approved screens shortens the path.'),
    array('Inteligencia artificial', 'Artificial intelligence', 'Además del desarrollo, la IA tiene costo por uso. Lo medimos en una prueba de concepto antes de comprometer el presupuesto.', 'Beyond development, AI has a usage cost. We measure it in a proof of concept before you commit the budget.'),
    array('Publicación y mantenimiento', 'Release and maintenance', 'Revisión de Apple, fichas de las tiendas y actualizaciones cada año. Una app sin mantenimiento deja de poder actualizarse.', 'Apple review, store listings and yearly updates. An app nobody maintains eventually can no longer be updated.'),
);
$aparte = array(
    array('Cuenta de Apple Developer', 'Apple Developer account', '99 dólares al año', 'US$99 per year'),
    array('Cuenta de Google Play', 'Google Play account', '25 dólares, un solo pago', 'US$25, one time'),
    array('Servidores y base de datos', 'Servers and database', 'Según usuarios y tráfico; te damos el estimado mensual antes de empezar', 'Depends on users and traffic; you get a monthly estimate before we start'),
    array('Servicios de terceros', 'Third-party services', 'Mapas, SMS, correo o IA: se pagan por uso al proveedor', 'Maps, SMS, email or AI: paid per use to the provider'),
    array('Dominio y correo', 'Domain and email', 'Si la app tiene sitio web o correos propios', 'If the app has its own website or email'),
);
$faq = array(
    array('¿Cuánto cuesta una app en México?', 'How much does an app cost?',
        'Depende de las plataformas, las funciones, las conexiones con otros sistemas y si lleva IA. Por eso no damos un precio de catálogo: después de una llamada sin costo recibes alcance, calendario y precio por escrito.',
        'It depends on platforms, features, integrations and whether it uses AI. That is why we don\'t quote a catalog price: after a free call you get scope, schedule and price in writing.'),
    array('¿Por qué no publican un precio fijo?', 'Why don\'t you publish a fixed price?',
        'Porque dos apps con el mismo nombre pueden costar muy distinto. Un precio sin conocer el alcance o es demasiado alto para cubrir el riesgo, o termina en cobros extra. Preferimos fijarlo por escrito antes de empezar.',
        'Because two apps with the same label can cost very different amounts. A price without knowing the scope is either padded to cover the risk or ends in extra charges. We prefer to set it in writing before we start.'),
    array('¿Cómo puedo bajar el costo?', 'How can I lower the cost?',
        'Empieza con lo mínimo que resuelve el problema (un MVP), usa Flutter para iOS y Android con un solo código, y deja para una segunda fase lo que no sea indispensable. También conviene usar software comercial para lo estándar y desarrollar a la medida solo lo que te distingue.',
        'Start with the smallest version that solves the problem (an MVP), use Flutter for iOS and Android from one codebase, and leave non-essentials for a second phase. It also pays to use off-the-shelf software for the standard parts and build custom only what sets you apart.'),
    array('¿El mantenimiento es obligatorio?', 'Is maintenance required?',
        'No es obligatorio contratarlo con nosotros, pero alguien tiene que hacerlo: Apple y Google cambian sus requisitos cada año y una app sin actualizaciones deja de poder publicarse.',
        'You don\'t have to hire us for it, but someone has to do it: Apple and Google change their requirements every year, and an app without updates eventually can\'t be published.'),
    array('¿Puedo pagar en pesos o en dólares?', 'Can I pay in pesos or dollars?',
        'Sí. Facturamos con CFDI en pesos o en dólares, por transferencia o tarjeta.',
        'Yes. We invoice in US dollars or Mexican pesos (CFDI), by wire or card.'),
);
$precios = array_filter(array(tac_precio_desde('proyecto'), tac_precio_desde('equipo', ' al mes', ' / month'), tac_precio_desde('mantenimiento', ' al mes', ' / month')));
?>
<section class="tac-phero tac-srv-hero"><div class="tac-wrap">
  <div class="tac-label" data-reveal><b>—</b> <?php tac_e('Costos', 'Pricing'); ?></div>
  <h1 data-reveal style="--d:80ms"><?php tac_e('¿Cuánto cuesta desarrollar una app en México?', 'How much does it cost to build an app?'); ?></h1>
  <p class="tac-sub" data-reveal style="--d:160ms"><?php tac_e('No hay un precio único: depende de las plataformas, las funciones, las conexiones con tus sistemas y si lleva inteligencia artificial. Aquí te explicamos qué mueve el precio y puedes armar tu proyecto en dos minutos. Con ese resumen te enviamos alcance, calendario y precio por escrito, sin costo.', 'There is no single price: it depends on platforms, features, integrations with your systems and whether it uses AI. Here is what drives the price, and you can outline your project in two minutes. With that summary we send you scope, schedule and price in writing, at no cost.'); ?></p>
  <div class="tac-ctas" data-reveal style="--d:240ms">
    <a class="tac-btn tac-btn-primary" href="#<?php tac_e('arma-tu-proyecto', 'outline-your-project'); ?>"><?php tac_e('Armar mi proyecto', 'Outline my project'); ?> <span class="arr">↓</span></a>
    <a class="tac-btn tac-btn-line" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Hablar con nosotros', 'Talk to us'); ?></a>
  </div>
  <?php if ($precios) : ?><p class="tac-asof" data-reveal><?php echo esc_html(implode(' · ', $precios)); ?></p><?php endif; ?>
</div></section>

<section class="tac-sec" id="<?php tac_e('arma-tu-proyecto', 'outline-your-project'); ?>" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>01</b> — <?php tac_e('Arma tu proyecto', 'Outline your project'); ?></div><h2><?php tac_e('Marca lo que necesitas', 'Pick what you need'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('La complejidad que ves es orientativa. El precio te lo damos por escrito después de entender tu caso.', 'The complexity you see is a rough guide. We give you the price in writing once we understand your case.'); ?></p>
  </div>
  <div class="tac-brief" data-brief data-contact="<?php echo esc_url(tac_url('contact')); ?>">
    <form class="tac-brief-form" onsubmit="return false">
      <?php foreach ($grupos as $g => $grupo) : ?>
        <fieldset data-grupo="<?php echo esc_attr($g); ?>">
          <legend><?php echo esc_html($grupo[0][$L]); ?></legend>
          <div class="opts">
            <?php foreach ($grupo[2] as $k => $o) : ?>
              <label><input type="<?php echo $grupo[1] === 'uno' ? 'radio' : 'checkbox'; ?>" name="<?php echo esc_attr($g); ?>" value="<?php echo esc_attr($k); ?>" data-peso="<?php echo (int) $o[2]; ?>" data-nec="<?php echo esc_attr($o[3]); ?>" data-txt="<?php echo esc_attr($o[$L]); ?>"><span><?php echo esc_html($o[$L]); ?></span></label>
            <?php endforeach; ?>
          </div>
        </fieldset>
      <?php endforeach; ?>
    </form>
    <aside class="tac-brief-out" aria-live="polite">
      <div class="tac-label"><?php tac_e('Tu proyecto', 'Your project'); ?></div>
      <div class="nivel"><span data-nivel-txt><?php tac_e('Marca al menos una plataforma', 'Pick at least one platform'); ?></span><i><b data-nivel-bar></b></i></div>
      <ul data-resumen></ul>
      <p class="nota" data-nivel-nota></p>
      <button type="button" class="tac-btn tac-btn-primary" data-enviar disabled><?php tac_e('Enviar y recibir precio', 'Send and get a price'); ?> <span class="arr">→</span></button>
      <p class="nota"><?php tac_e('Te llevamos al formulario con este resumen ya escrito. No se envía nada hasta que tú lo mandes.', 'We take you to the form with this summary already filled in. Nothing is sent until you send it.'); ?></p>
      <template data-niveles><?php echo esc_html(wp_json_encode(array(
          'baja'  => array(tac_t('Complejidad baja', 'Low complexity'), tac_t('Una app enfocada, con pocas funciones y sin conexiones complejas.', 'A focused app with few features and no complex integrations.')),
          'media' => array(tac_t('Complejidad media', 'Medium complexity'), tac_t('Varias funciones o una conexión con otro sistema. Conviene definir bien qué va en la primera versión.', 'Several features or one integration. Worth defining carefully what goes into the first version.')),
          'alta'  => array(tac_t('Complejidad alta', 'High complexity'), tac_t('Varias plataformas, integraciones o IA. Suele convenir empezar con una prueba de concepto o un MVP por fases.', 'Several platforms, integrations or AI. Usually best to start with a proof of concept or a phased MVP.')),
          'intro' => tac_t('Proyecto armado en thealchemistcode.org:', 'Project outlined on thealchemistcode.org:'),
      ))); ?></template>
    </aside>
  </div>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>02</b> — <?php tac_e('Qué mueve el precio', 'What drives the price'); ?></div><h2><?php tac_e('Seis factores que deciden el costo', 'Six factors that decide the cost'); ?></h2></div>
  </div>
  <div class="tac-local-grid">
    <?php foreach ($factores as $i => $f) : ?>
      <article class="tac-local-card" data-reveal style="--d:<?php echo (int) ($i % 2 * 80); ?>ms"><span class="k"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span><h3><?php echo esc_html($L ? $f[1] : $f[0]); ?></h3><p><?php echo esc_html($L ? $f[3] : $f[2]); ?></p></article>
    <?php endforeach; ?>
  </div>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>03</b> — <?php tac_e('Costos aparte', 'Other costs'); ?></div><h2><?php tac_e('Lo que pagarás aunque nadie te lo diga', 'What you\'ll pay that nobody mentions'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('No son honorarios nuestros: los pagas directo a cada proveedor, y en la propuesta te los decimos por adelantado.', 'These aren\'t our fees: you pay each provider directly, and our proposal lists them upfront.'); ?></p>
  </div>
  <dl class="tac-uses">
    <?php foreach ($aparte as $i => $a) : ?>
      <div data-reveal style="--d:<?php echo (int) ($i * 60); ?>ms"><dt><?php echo esc_html($L ? $a[1] : $a[0]); ?></dt><dd><?php echo esc_html($L ? $a[3] : $a[2]); ?></dd></div>
    <?php endforeach; ?>
  </dl>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>04</b> — <?php tac_e('Cómo contratarnos', 'How to engage'); ?></div><h2><?php tac_e('Tres formas de trabajar, todas por escrito', 'Three ways to work together, all in writing'); ?></h2></div>
  </div>
  <?php include __DIR__ . '/_engage.php'; ?>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>05</b> — <?php tac_e('Preguntas frecuentes', 'FAQ'); ?></div><h2><?php tac_e('Sobre precios', 'About pricing'); ?></h2></div>
  </div>
  <div class="tac-faq">
    <?php foreach ($faq as $q) : ?>
      <details data-reveal><summary><?php echo esc_html($L ? $q[1] : $q[0]); ?></summary><p><?php echo esc_html($L ? $q[3] : $q[2]); ?></p></details>
    <?php endforeach; ?>
  </div>
  <?php $guias = tac_guias_html($L ? array('how-to-choose-a-software-development-company', 'flutter-vs-native-how-to-choose') : array('software-a-la-medida-o-software-comercial', 'como-publicar-app-app-store-google-play', 'flutter-o-nativo-como-elegir-tecnologia-app')); ?>
  <?php if ($guias) : ?><div style="margin-top:40px"><?php echo $guias; // phpcs:ignore -- escapado en tac_guias_html() ?></div><?php endif; ?>
</div></section>

<?php include __DIR__ . '/_cta.php'; ?>
<script type="application/ld+json"><?php
echo wp_json_encode(array(
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(function ($q) use ($L) {
        return array('@type' => 'Question', 'name' => $L ? $q[1] : $q[0], 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $L ? $q[3] : $q[2]));
    }, $faq),
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
