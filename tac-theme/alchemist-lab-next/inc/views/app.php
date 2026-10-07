<?php if (!defined('ABSPATH')) { exit; }
$k = (string) get_post_meta(get_the_ID(), 'tac_app', true);
$lang = tac_lang();
$live = tac_apps_live();
$app = $live['apps'][$k] ?? null;
$txt = tac_apps_content()[$k][$lang] ?? array();
if (!$app) {
    return;
}
$name = $app['nombre'];
$cat = $live['categorias'][$app['categoria']][$lang] ?? '';
$h1 = $txt['h1'] ?? $name;
$intro = $txt['intro'] ?? ($app[$lang] ?? '');
$features = $txt['features'] ?? array();
$shots = tac_app_shots($k);
$ios = tac_store_link($k, 'ios');
$play = tac_store_link($k, 'android');
$desk = $app['enlaces']['escritorio'] ?? '';
$web = $app['enlaces']['web'] ?? '';
$icon = tac_icon($k);
$is_desktop = $k === 'coder';
?>
<section class="tac-phero tac-apphero">
  <div class="tac-wrap">
    <nav class="tac-crumbs" aria-label="<?php echo esc_attr(tac_t('Ruta', 'Breadcrumb')); ?>" data-reveal>
      <a href="<?php echo esc_url(tac_url('home')); ?>"><?php tac_e('Inicio', 'Home'); ?></a><span>/</span><a href="<?php echo esc_url(tac_url('apps')); ?>">Apps</a><span>/</span><b><?php echo esc_html($name); ?></b>
    </nav>
    <div class="tac-apphero-grid">
      <div>
        <div class="tac-apphead" data-reveal>
          <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($name); ?>" width="84" height="84" fetchpriority="high">
          <div><span class="tac-label"><?php echo esc_html($cat); ?></span><p class="nm"><?php echo esc_html($name); ?></p></div>
        </div>
        <h1 data-reveal style="--d:80ms"><?php echo esc_html($h1); ?></h1>
        <p class="tac-sub" data-reveal style="--d:160ms"><?php echo esc_html($intro); ?></p>
        <div class="tac-stores" data-reveal style="--d:240ms">
          <?php if ($ios) : ?><a class="tac-store" href="<?php echo esc_url($ios); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.4 12.6c0-2.4 2-3.6 2.1-3.7-1.1-1.7-2.9-1.9-3.5-1.9-1.5-.2-2.9.9-3.7.9-.8 0-1.9-.9-3.2-.8-1.6 0-3.1 1-4 2.4-1.7 3-.4 7.4 1.2 9.8.8 1.2 1.8 2.5 3 2.4 1.2 0 1.7-.8 3.1-.8 1.5 0 1.9.8 3.2.8 1.3 0 2.2-1.2 3-2.4.9-1.4 1.3-2.7 1.3-2.8 0 0-2.5-1-2.5-3.9ZM14 5.4c.7-.8 1.1-1.9 1-3-1 0-2.1.7-2.8 1.5-.6.7-1.2 1.8-1 2.9 1.1.1 2.1-.6 2.8-1.4Z"/></svg><span><small><?php tac_e('Descárgala en', 'Download on the'); ?></small>App Store</span></a><?php endif; ?>
          <?php if ($play) : ?><a class="tac-store" href="<?php echo esc_url($play); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#34A853" d="M3.6 2.3 13.4 12l-9.8 9.7c-.3-.2-.5-.6-.5-1.1V3.4c0-.5.2-.9.5-1.1Z"/><path fill="#FBBC04" d="m16.6 15.2-3.2-3.2 3.2-3.2 3.7 2.1c1 .6 1 1.6 0 2.2l-3.7 2.1Z"/><path fill="#EA4335" d="M13.4 12 3.6 21.7c.4.3 1 .3 1.6 0l11.4-6.5-3.2-3.2Z"/><path fill="#4285F4" d="M13.4 12 16.6 8.8 5.2 2.3c-.6-.3-1.2-.3-1.6 0L13.4 12Z"/></svg><span><small><?php tac_e('Disponible en', 'Get it on'); ?></small>Google Play</span></a><?php endif; ?>
          <?php if ($desk) : ?><a class="tac-store" href="<?php echo esc_url($desk); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg><span><small>macOS · Windows · Linux</small><?php tac_e('Descargar gratis', 'Free download'); ?></span></a><?php endif; ?>
          <?php if ($is_desktop) : // código abierto (AGPL-3.0): el repo es parte de la invitación ?><a class="tac-store" href="https://github.com/Alekla0126/alchemist-coder" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.3a9.9 9.9 0 0 0-3.1 19.3c.5.1.7-.2.7-.5v-1.8c-2.8.6-3.4-1.2-3.4-1.2-.4-1.2-1.1-1.5-1.1-1.5-.9-.6.1-.6.1-.6 1 .1 1.5 1 1.5 1 .9 1.5 2.3 1.1 2.9.8.1-.6.3-1.1.6-1.3-2.2-.3-4.5-1.1-4.5-4.9 0-1.1.4-2 1-2.7-.1-.3-.4-1.3.1-2.7 0 0 .8-.3 2.7 1a9.4 9.4 0 0 1 5 0c1.9-1.3 2.7-1 2.7-1 .5 1.4.2 2.4.1 2.7.6.7 1 1.6 1 2.7 0 3.8-2.3 4.6-4.5 4.9.4.3.7.9.7 1.8v2.7c0 .3.2.6.7.5A9.9 9.9 0 0 0 12 2.3Z"/></svg><span><small><?php tac_e('Código abierto · AGPL-3.0', 'Open source · AGPL-3.0'); ?></small><?php tac_e('Colabora en GitHub', 'Contribute on GitHub'); ?></span></a><?php endif; ?>
        </div>
        <?php if ($web) : ?><p class="tac-appweb" data-reveal style="--d:300ms"><a href="<?php echo esc_url($web); ?>" target="_blank" rel="noopener"><?php tac_e('Sitio oficial de la app', 'Official app website'); ?> ↗</a></p><?php endif; ?>
      </div>
      <div class="tac-appvisual" data-reveal="scale" style="--d:200ms">
        <?php if (is_readable(trailingslashit(wp_upload_dir()['basedir']) . 'tac-site/fold/' . $k . '-inner.webp')) : ?>
          <div class="tac-appfold" data-fold-auto>
            <?php echo tac_fold($k, sprintf(tac_t('%s en un teléfono plegable: se abre y la app pasa de dos a cuatro columnas.', '%s on a foldable phone: it opens and the app goes from two to four columns.'), $name), true); // phpcs:ignore ?>
            <div class="tac-fold-tag" aria-hidden="true"><span class="c"><?php tac_e('Cerrado · 6.3″', 'Closed · 6.3″'); ?></span><span class="o"><?php tac_e('Abierto · 8″', 'Open · 8″'); ?></span></div>
            <button type="button" class="tac-fold-btn" data-fold-toggle data-open="<?php echo esc_attr(tac_t('Plegar', 'Fold')); ?>" data-closed="<?php echo esc_attr(tac_t('Desplegar', 'Unfold')); ?>"><?php tac_e('Plegar', 'Fold'); ?></button>
          </div>
        <?php elseif (is_readable(trailingslashit(wp_upload_dir()['basedir']) . 'tac-site/screens/' . $k . '.webp')) : ?>
          <?php echo tac_phone($k, $name, true); ?>
        <?php elseif (!empty($shots[0])) : ?>
          <img class="tac-shotcard<?php echo $is_desktop ? ' wide' : ''; ?>" src="<?php echo esc_url($shots[0]['src']); ?>"<?php echo $shots[0]['srcset']; // phpcs:ignore -- escapado en tac_srcset() ?> width="<?php echo (int) $shots[0]['w']; ?>" height="<?php echo (int) $shots[0]['h']; ?>" alt="<?php echo esc_attr(sprintf(tac_t('Captura de %s', '%s screenshot'), $name)); ?>" fetchpriority="high">
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php if ($features) : ?>
<section class="tac-sec" style="padding-top:30px">
  <div class="tac-wrap">
    <div class="tac-feats">
      <?php foreach ($features as $i => $f) : ?>
        <div class="tac-card" data-reveal style="--d:<?php echo (int) ($i * 90); ?>ms"><div class="k"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></div><h3><?php echo esc_html($f['t'] ?? ''); ?></h3><p><?php echo esc_html($f['d'] ?? ''); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (count($shots) > 1) : ?>
<section class="tac-gallery-sec">
  <div class="tac-wrap"><div class="tac-label" data-reveal><b>—</b> <?php tac_e('Capturas de la tienda', 'Store screenshots'); ?></div></div>
  <div class="tac-gallery<?php echo $is_desktop ? ' wide' : ''; ?>" data-lenis-prevent-wheel>
    <?php foreach ($shots as $i => $s) : ?>
      <img src="<?php echo esc_url($s['src']); ?>"<?php echo $s['srcset']; // phpcs:ignore -- escapado en tac_srcset() ?> width="<?php echo (int) $s['w']; ?>" height="<?php echo (int) $s['h']; ?>" alt="<?php echo esc_attr(sprintf(tac_t('Captura %1$d de %2$s', '%2$s screenshot %1$d'), $i + 1, $name)); ?>" loading="lazy" decoding="async" data-reveal style="--d:<?php echo (int) ($i * 80); ?>ms">
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<section class="tac-sec" style="padding-top:70px;padding-bottom:30px">
  <div class="tac-wrap">
    <div class="tac-madeby" data-reveal>
      <div>
        <div class="tac-label"><?php tac_e('Hecha por The Alchemist Code', 'Built by The Alchemist Code'); ?></div>
        <h2><?php tac_e('¿Quieres una app así para tu empresa?', 'Want an app like this for your business?'); ?></h2>
        <p class="tac-sub"><?php tac_e('Diseñamos, desarrollamos, lanzamos y damos soporte a apps para iOS, Android y escritorio. El diagnóstico y la propuesta no tienen costo.', 'We design, build, launch and support apps for iOS, Android and desktop. Discovery and proposal are free.'); ?></p>
      </div>
      <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?> <span class="arr">→</span></a>
    </div>
  </div>
</section>

<?php
$related = array();
foreach ($live['apps'] as $rk => $ra) {
    if ($rk !== $k && $ra['categoria'] === $app['categoria'] && tac_app_url($rk)) {
        $related[$rk] = $ra;
    }
}
$related = array_slice($related, 0, 3, true);
if ($related) : ?>
<section class="tac-sec" style="padding-top:50px">
  <div class="tac-wrap">
    <div class="tac-label" data-reveal><b>—</b> <?php tac_e('Más apps de', 'More apps in'); ?> <?php echo esc_html($cat); ?></div>
    <div class="tac-related">
      <?php foreach ($related as $rk => $ra) : ?>
        <a class="tac-card tac-relcard" href="<?php echo esc_url(tac_app_url($rk)); ?>" data-reveal>
          <img src="<?php echo esc_url(tac_icon($rk)); ?>" alt="" width="52" height="52" loading="lazy">
          <div><h3><?php echo esc_html($ra['nombre']); ?></h3><p><?php echo esc_html($ra[$lang] ?? ''); ?></p></div>
          <span class="arr">→</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
$os = array();
if ($ios) { $os[] = 'iOS'; }
if ($play) { $os[] = 'Android'; }
if ($desk) { $os = array('macOS', 'Windows', 'Linux'); }
$ld = array(
    '@context' => 'https://schema.org',
    '@graph' => array(
        array_filter(array(
            '@type' => $is_desktop ? 'SoftwareApplication' : 'MobileApplication',
            'name' => $name,
            'description' => $intro,
            'applicationCategory' => tac_schema_category($app['categoria']),
            'operatingSystem' => implode(', ', $os),
            'image' => $icon,
            'screenshot' => array_map(function ($s) { return $s['src']; }, $shots),
            'url' => get_permalink(),
            'installUrl' => $ios ?: ($play ?: $desk),
            'sameAs' => array_values(array_filter(array($app['enlaces']['ios'] ?? '', $app['enlaces']['android'] ?? '', $web))),
            'offers' => array('@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'),
            'author' => array('@type' => 'Organization', 'name' => 'The Alchemist Code', 'url' => home_url('/')),
            'inLanguage' => $lang === 'en' ? 'en' : 'es',
        )),
    ),
);
?>
<script type="application/ld+json"><?php echo wp_json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
<?php include __DIR__ . '/_cta.php'; ?>
