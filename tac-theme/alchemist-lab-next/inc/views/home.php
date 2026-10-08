<?php if (!defined('ABSPATH')) { exit; }
$tac_home = true; ?>
<section class="tac-hero">
  <span class="tac-blob b1" aria-hidden="true"></span><span class="tac-blob b2" aria-hidden="true"></span>
  <div class="tac-wrap">
    <div>
      <h1 class="tac-pill tac-rise"><i></i><?php tac_e('Desarrollo de software, apps e IA en Puebla, México', 'Nearshore software, app and AI development from Mexico'); ?></h1>
      <p class="tac-display"><?php echo tac_words(tac_t('Apps e IA [[listas para producción.]]', 'Apps and AI, [[built for production.]]')); // phpcs:ignore ?></p>
      <p class="tac-sub tac-rise" style="--d:250ms"><?php tac_e('Estudio dirigido por su fundador. Diseñamos, construimos, lanzamos y operamos software para empresas de México y Estados Unidos, con el mismo rigor que aplicamos a nuestras propias apps en las tiendas.', 'A founder-led studio. We design, build, launch and run software for companies in the US and Mexico, held to the same standard as the apps we publish ourselves.'); ?></p>
      <div class="tac-ctas tac-rise" style="--d:350ms">
        <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?> <span class="arr">→</span></a>
        <a class="tac-btn tac-btn-line" href="#<?php tac_e('casos', 'work'); ?>"><?php tac_e('Ver casos', 'See our work'); ?></a>
      </div>
    </div>
    <div class="tac-stage" data-parallax aria-hidden="true">
      <div class="ph l"><div data-reveal style="--d:300ms"><?php echo tac_phone('soccer24', '', true); ?></div></div>
      <div class="ph c"><div data-reveal style="--d:150ms"><?php echo tac_phone('inventra', '', true); ?></div></div>
      <div class="ph r"><div data-reveal style="--d:450ms"><?php echo tac_phone('caltracker', '', true); ?></div></div>
      <div class="tac-chip k1"><img src="<?php echo esc_url(tac_icon('soccer24')); ?>" alt=""><span>4.5★ · 75,685<small><?php tac_e('Soccer24 en Google Play', 'Soccer24 on Google Play'); ?></small></span></div>
      <div class="tac-chip k2"><span class="ic"><?php echo tac_svg('shield'); ?></span><span>NDA · <?php tac_e('código tuyo', 'your IP'); ?><small><?php tac_e('contrato en MXN o USD', 'contracts in USD or MXN'); ?></small></span></div>
      <div class="tac-chip k3"><span class="ic"><?php echo tac_svg('spark'); ?></span><span><?php tac_e('IA aplicada', 'Applied AI'); ?><small><?php tac_e('OCR, fotos y redacción', 'OCR, photos and writing'); ?></small></span></div>
      <div class="tac-chip k4"><img src="<?php echo esc_url(tac_icon('inventra')); ?>" alt=""><span>Inventra<small><?php tac_e('inventario multisucursal', 'multi-branch inventory'); ?></small></span></div>
    </div>
  </div>
  <div class="tac-wrap"><?php include __DIR__ . '/_proof.php'; ?></div>
</section>
<?php include __DIR__ . '/_clients.php'; ?>

<section class="tac-logos">
  <div class="tac-wrap"><p data-reveal><?php tac_e('17 apps propias publicadas en App Store, Google Play y escritorio', '17 of our own apps, live on the App Store, Google Play and desktop'); ?></p></div>
  <div data-reveal style="--d:120ms"><?php echo tac_app_marquee(); // phpcs:ignore ?></div>
</section>

<section class="tac-manifest" data-manifest>
  <div class="tac-wrap"><p><?php echo tac_manifest(tac_t('[[Publicamos, no prometemos.]] Lo que no está en la tienda no cuenta: por eso diseñamos, programamos, lanzamos y operamos [[nuestras propias apps,]] y seguimos ahí después del lanzamiento.', "[[We ship, we don't promise.]] If it isn't in the store, it doesn't count: that's why we design, build, launch and run [[our own apps,]] and we're still there after launch.")); // phpcs:ignore ?></p></div>
</section>

<section class="tac-sec" id="<?php tac_e('soluciones', 'solutions'); ?>">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>01</b> — <?php tac_e('Soluciones', 'Solutions'); ?></div><h2><?php tac_e('Lo que construimos para empresas', 'What we build for businesses'); ?></h2></div>
      <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Partimos de tu necesidad, no de la tecnología. Cada solución se apoya en algo que ya construimos y operamos.', 'We start from your need, not from the technology. Each solution builds on something we already run in production.'); ?></p>
    </div>
    <?php include __DIR__ . '/_solutions.php'; ?>
  </div>
</section>

<section class="tac-sec" id="<?php tac_e('casos', 'work'); ?>" style="padding-top:40px">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>02</b> — <?php tac_e('Casos seleccionados', 'Selected work'); ?></div><h2><?php tac_e('Resultados que puedes comprobar', 'Results you can check'); ?></h2></div>
      <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Cada cifra viene de la tienda o de la consola, con su fecha. Tres productos que operamos y un sistema clínico para una institución.', 'Every number comes from the store or the console, with its date. Three products we run and a clinical system for an institution.'); ?></p>
    </div>
  </div>
  <?php include __DIR__ . '/_cases.php'; ?>
</section>

<section class="tac-foldsec" data-fold aria-labelledby="tac-fold-t">
  <div class="tac-fold-sticky">
    <div class="tac-wrap tac-fold-grid">
      <div class="tac-fold-copy">
        <div class="tac-label" data-reveal><?php tac_e('Plegables y tablets', 'Foldables and tablets'); ?></div>
        <h2 id="tac-fold-t"><?php tac_e('Apps que se despliegan con tu teléfono', 'Apps that unfold with your phone'); ?></h2>
        <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Diseñamos para teléfonos, plegables y tablets. Así se ve PeakPlay en un Pixel 9 Pro Fold: la misma pantalla, reorganizada al abrirlo.', 'We design for phones, foldables and tablets. This is PeakPlay on a Pixel 9 Pro Fold: the same screen, rearranged as it opens.'); ?></p>
        <ol class="tac-fold-steps">
          <li data-step="0"><b><?php tac_e('Cerrado', 'Closed'); ?></b><span><?php tac_e('Pantalla exterior de 6.3″: dos columnas de equipos.', '6.3″ outer screen: two columns of teams.'); ?></span></li>
          <li data-step="1"><b><?php tac_e('Al abrirlo', 'Opening'); ?></b><span><?php tac_e('La app sigue en la misma pantalla, sin recargar.', 'The app stays on the same screen, no reload.'); ?></span></li>
          <li data-step="2"><b><?php tac_e('Abierto', 'Open'); ?></b><span><?php tac_e('Pantalla interior de 8″: el mismo diseño, en cuatro columnas.', '8″ inner screen: the same layout, in four columns.'); ?></span></li>
        </ol>
        <div class="tac-asof"><?php tac_e('Capturas de PeakPlay en el emulador del Pixel 9 Pro Fold, octubre de 2026.', 'PeakPlay screenshots on the Pixel 9 Pro Fold emulator, October 2026.'); ?></div>
      </div>
      <div class="tac-fold-stage">
        <?php echo tac_fold('peakplay', tac_t('PeakPlay en un teléfono plegable: cerrado muestra dos columnas de equipos y abierto, cuatro.', 'PeakPlay on a foldable phone: two columns of teams when closed, four when open.')); // phpcs:ignore ?>
        <div class="tac-fold-tag" aria-hidden="true"><span class="c"><?php tac_e('Cerrado · 6.3″', 'Closed · 6.3″'); ?></span><span class="o"><?php tac_e('Abierto · 8″', 'Open · 8″'); ?></span></div>
      </div>
    </div>
  </div>
</section>

<section class="tac-sec tac-infra-sec" id="<?php tac_e('infraestructura', 'infrastructure'); ?>" style="padding-top:60px">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>03</b> — <?php tac_e('Infraestructura y redes', 'Infrastructure and networks'); ?></div><h2><?php tac_e('Servidores, nube y redes que operamos todos los días', 'Servers, cloud and networks we run every day'); ?></h2></div>
      <div data-reveal style="--d:120ms"><p class="tac-sub"><?php tac_e('La app es lo que se ve. Detrás hay servidores, redes y monitoreo que tienen que funcionar a las tres de la mañana: los diseñamos, los montamos y los operamos.', 'The app is the part people see. Behind it are servers, networks and monitoring that have to work at 3 a.m.: we design them, set them up and run them.'); ?></p>
        <a class="tac-more" href="<?php echo esc_url(tac_url('infra')); ?>"><?php tac_e('Infraestructura y redes para empresas', 'Infrastructure and networks for businesses'); ?> <span aria-hidden="true">→</span></a></div>
    </div>
    <?php include __DIR__ . '/_infra.php'; ?>
  </div>
</section>

<section class="tac-sec" id="<?php tac_e('proceso', 'process'); ?>" style="padding-top:60px">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>04</b> — <?php tac_e('Cómo trabajamos', 'How we work'); ?></div><h2><?php tac_e('De la idea a la tienda, y después', 'From idea to store, and beyond'); ?></h2></div>
      <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Un proceso corto y visible. Ves avances en tu teléfono, no informes.', 'A short, visible process. You see progress on your phone, not in reports.'); ?></p>
    </div>
    <?php include __DIR__ . '/_process.php'; ?>
  </div>
</section>

<section class="tac-sec" id="<?php tac_e('contratacion', 'engagement'); ?>" style="padding-top:40px">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>05</b> — <?php tac_e('Cómo contratarnos', 'How to engage'); ?></div><h2><?php tac_e('Tres formas de trabajar, todas por escrito', 'Three ways to work together, all in writing'); ?></h2></div>
      <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('La primera llamada y la propuesta no tienen costo. El precio y los plazos quedan en el contrato antes de escribir una línea de código.', 'The first call and the proposal are free. Price and timeline go into the contract before we write a line of code.'); ?></p>
    </div>
    <?php include __DIR__ . '/_engage.php'; ?>
  </div>
</section>

<section class="tac-sec tac-near-sec" id="<?php tac_e('estados-unidos', 'us-teams'); ?>" style="padding-top:40px">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>06</b> — <?php tac_e('Para equipos en Estados Unidos', 'For US teams'); ?></div><h2><?php tac_e('Tan cerca como un equipo en tu ciudad', 'As close as a team in your city'); ?></h2></div>
      <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Trabajamos con empresas de México y Estados Unidos con las mismas reglas: horario compartido, inglés directo y la propiedad del código de tu lado.', 'We work with companies in the US and Mexico under the same rules: shared hours, direct English and the IP on your side.'); ?></p>
    </div>
    <?php include __DIR__ . '/_nearshore.php'; ?>
  </div>
</section>

<section class="tac-sec" id="<?php tac_e('investigacion', 'research'); ?>" style="padding-top:40px">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>07</b> — <?php tac_e('Investigación e impacto', 'Research and impact'); ?></div><h2><?php tac_e('IA con respaldo de investigación', 'AI backed by research'); ?></h2></div>
      <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Antes de ponerle IA a tu producto, la hemos publicado, aplicado en finanzas y llevado a apps que la gente usa.', 'Before we put AI in your product, we have published on it, applied it in finance and shipped it in apps people use.'); ?></p>
    </div>
    <?php include __DIR__ . '/_research.php'; ?>
  </div>
</section>

<?php $guias = tac_guias_html(tac_lang() === 'en'
    ? array('nearshore-software-development-mexico-guide', 'flutter-vs-native-how-to-choose', 'ai-for-business-practical-uses')
    : array('como-elegir-empresa-de-desarrollo-de-software-mexico', 'flutter-o-nativo-como-elegir-tecnologia-app', 'ia-para-empresas-mexico-usos-reales'));
if ($guias) : ?>
<section class="tac-sec" id="<?php tac_e('guias', 'guides'); ?>" style="padding-top:40px">
  <div class="tac-wrap">
    <div class="tac-sec-head">
      <div data-reveal><div class="tac-label"><b>08</b> — <?php tac_e('Guías', 'Guides'); ?></div><h2><?php tac_e('Para decidir con información', 'Read before you decide'); ?></h2></div>
      <div data-reveal style="--d:120ms"><p class="tac-sub"><?php tac_e('Lo que preguntan las empresas antes de contratar software, respondido con lo que aprendimos al publicar nuestras apps.', 'What companies ask before hiring a software team, answered with what we learned shipping our own apps.'); ?></p>
        <a class="tac-more" href="<?php echo esc_url(tac_url('blog')); ?>"><?php tac_e('Todas las guías', 'All guides'); ?> <span aria-hidden="true">→</span></a></div>
    </div>
    <?php echo $guias; // phpcs:ignore -- escapado en tac_guias_html() ?>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/_coder.php'; ?>

<?php include __DIR__ . '/_proof_people.php'; ?>

<?php include __DIR__ . '/_contact_section.php'; ?>
