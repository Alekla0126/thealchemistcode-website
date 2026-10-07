<?php if (!defined('ABSPATH')) { exit; }
/*
 * Página de servicio (infra, movil, ia, flutter). El contenido vive en inc/data/servicios.json;
 * la vista solo fija $tac_srv. Schema: Service + FAQPage (las migas las pone All in One SEO).
 */
$L = tac_lang() === 'en' ? 1 : 0;
$todo = json_decode((string) file_get_contents(get_template_directory() . '/inc/data/servicios.json'), true) ?: array();
$S = $todo[$tac_srv] ?? null;
if (!$S) {
    return;
}
$tx = function ($par) use ($L) {
    return is_array($par) ? (string) ($par[$L] ?? $par[0]) : (string) $par;
};
// app:<clave> → página de la app; view:<vista> → página del sitio; anchor:<es>|<en> → sección del home
$enlace = function ($u) use ($L) {
    if (strpos($u, 'app:') === 0) {
        return tac_app_url(substr($u, 4)) ?: tac_url('apps');
    }
    if (strpos($u, 'view:') === 0) {
        return tac_url(substr($u, 5));
    }
    if (strpos($u, 'anchor:') === 0) {
        $a = explode('|', substr($u, 7));
        return tac_url('home') . '#' . ($a[$L] ?? $a[0]);
    }
    return $u;
};
// contador de secciones (los parciales incluidos usan $n y $i en sus propios bucles)
$tac_sec_n = 0;
$num = function () use (&$tac_sec_n) {
    $tac_sec_n++;
    return sprintf('%02d', $tac_sec_n);
};
$otros = array(
    'local'   => array('Desarrollo de software en Puebla', 'Software development company in Mexico'),
    'movil'   => array('Desarrollo de apps móviles', 'Mobile app development'),
    'flutter' => array('Desarrollo en Flutter', 'Flutter development'),
    'ia'      => array('Inteligencia artificial para empresas', 'AI development'),
    'infra'   => array('Infraestructura y redes', 'Infrastructure and networks'),
);
$bloque = $S['bloque'] ?? array();
?>
<section class="tac-phero tac-srv-hero"><div class="tac-wrap">
  <div class="tac-label" data-reveal><b>—</b> <?php echo esc_html($tx($S['label'])); ?></div>
  <h1 data-reveal style="--d:80ms"><?php echo esc_html($tx($S['h1'])); ?></h1>
  <p class="tac-sub" data-reveal style="--d:160ms"><?php echo esc_html($tx($S['intro'])); ?></p>
  <div class="tac-ctas" data-reveal style="--d:240ms">
    <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?> <span class="arr">→</span></a>
    <a class="tac-btn tac-btn-line" href="<?php echo esc_url(tac_url('home') . '#' . tac_t('casos', 'work')); ?>"><?php tac_e('Ver casos', 'See our work'); ?></a>
  </div>
  <?php if (!empty($S['prueba'])) : ?>
    <div class="tac-proof" data-reveal style="--d:320ms">
      <?php foreach ($S['prueba'] as $p) :
          $u = $enlace($p['u']);
          $ext = strpos($u, home_url()) !== 0; ?>
        <a class="tac-proof-i" href="<?php echo esc_url($u); ?>"<?php echo $ext ? ' target="_blank" rel="noopener"' : ''; ?>>
          <b<?php echo !empty($p['n']) ? ' data-count="' . (int) $p['n'] . '" data-suffix="' . esc_attr($p['suf'] ?? '') . '"' : ''; ?>><?php echo esc_html($tx($p['v'])); ?></b>
          <span class="l"><?php echo esc_html($tx($p['l'])); ?></span>
          <span class="d"><?php echo esc_html($tx($p['d'])); ?></span>
          <small><?php echo esc_html($tx($p['s'])); ?> <i aria-hidden="true">↗</i></small>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div></section>

<?php if ($bloque) : ?>
<section class="tac-sec tac-srv-bloque" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php echo esc_html($tx($bloque['label'])); ?></div><h2><?php echo esc_html($tx($bloque['h2'])); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php echo esc_html($tx($bloque['sub'])); ?></p>
  </div>
  <?php if ($bloque['tipo'] === 'infra') : ?>
    <?php include __DIR__ . '/_infra.php'; ?>
  <?php elseif ($bloque['tipo'] === 'phones') : ?>
    <div class="tac-srv-phones n<?php echo (int) count($bloque['apps']); ?>" data-reveal>
      <?php foreach ($bloque['apps'] as $i => $k) : ?>
        <a href="<?php echo esc_url(tac_app_url($k) ?: tac_url('apps')); ?>" style="--i:<?php echo (int) $i; ?>"><?php echo tac_phone($k, tac_apps_live()['apps'][$k]['nombre'] ?? ucfirst($k)); // phpcs:ignore ?></a>
      <?php endforeach; ?>
    </div>
  <?php elseif ($bloque['tipo'] === 'fold') : ?>
    <div class="tac-appfold tac-srv-fold" data-fold-auto>
      <?php echo tac_fold($bloque['app'], tac_t('PeakPlay en un teléfono plegable: cerrado muestra dos columnas de equipos y abierto, cuatro.', 'PeakPlay on a foldable phone: two columns of teams when closed, four when open.')); // phpcs:ignore ?>
      <div class="tac-fold-tag" aria-hidden="true"><span class="c"><?php tac_e('Cerrado · 6.3″', 'Closed · 6.3″'); ?></span><span class="o"><?php tac_e('Abierto · 8″', 'Open · 8″'); ?></span></div>
      <button type="button" class="tac-fold-btn" data-fold-toggle data-open="<?php echo esc_attr(tac_t('Plegar', 'Fold')); ?>" data-closed="<?php echo esc_attr(tac_t('Desplegar', 'Unfold')); ?>"><?php tac_e('Plegar', 'Fold'); ?></button>
    </div>
  <?php endif; ?>
</div></section>
<?php endif; ?>

<?php if (!empty($S['items'])) : ?>
<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Qué incluye', 'What’s included'); ?></div><h2><?php echo esc_html($tx($S['items_h2'])); ?></h2></div>
  </div>
  <div class="tac-local-grid tac-srv-grid">
    <?php foreach ($S['items'] as $i => $it) : ?>
      <article class="tac-local-card" data-reveal style="--d:<?php echo (int) ($i % 2 * 80); ?>ms">
        <span class="k"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
        <h3><?php echo esc_html($tx($it['t'])); ?></h3>
        <p><?php echo esc_html($tx($it['p'])); ?></p>
        <?php if (!empty($it['app'])) :
            $nombre = tac_apps_live()['apps'][$it['app']]['nombre'] ?? ucfirst($it['app']); ?>
          <a class="ex" href="<?php echo esc_url(tac_app_url($it['app']) ?: tac_url('apps')); ?>"><img src="<?php echo esc_url(tac_icon($it['app'])); ?>" alt="" width="28" height="28" loading="lazy"><span><?php tac_e('Hecho por nosotros:', 'Built by us:'); ?> <b><?php echo esc_html($nombre); ?></b></span></a>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>
</div></section>
<?php endif; ?>

<?php if (!empty($S['usos'])) : ?>
<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Para quién', 'Who it’s for'); ?></div><h2><?php echo esc_html($tx($S['usos_h2'])); ?></h2></div>
  </div>
  <dl class="tac-uses">
    <?php foreach ($S['usos'] as $i => $u) : ?>
      <div data-reveal style="--d:<?php echo (int) ($i * 60); ?>ms"><dt><?php echo esc_html($tx($u['t'])); ?></dt><dd><?php echo esc_html($tx($u['p'])); ?></dd></div>
    <?php endforeach; ?>
  </dl>
</div></section>
<?php endif; ?>

<?php if (!empty($S['enfoque'])) : ?>
<section class="tac-sec tac-near-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Criterio', 'Approach'); ?></div><h2><?php echo esc_html($tx($S['enfoque_h2'])); ?></h2></div>
  </div>
  <div class="tac-srv-principles">
    <?php foreach ($S['enfoque'] as $i => $e) : ?>
      <div data-reveal style="--d:<?php echo (int) ($i * 70); ?>ms"><span class="n"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span><h3><?php echo esc_html($tx($e['t'])); ?></h3><p><?php echo esc_html($tx($e['p'])); ?></p></div>
    <?php endforeach; ?>
  </div>
</div></section>
<?php endif; ?>

<?php if (!empty($S['casos'])) : ?>
<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Resultados', 'Results'); ?></div><h2><?php tac_e('En producción, no en una presentación', 'In production, not in a slide deck'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Cifras de Play Console y de nuestra propia infraestructura, octubre de 2026.', 'Figures from Play Console and our own infrastructure, October 2026.'); ?></p>
  </div>
  <div class="tac-local-cases n<?php echo (int) count($S['casos']); ?>">
    <?php foreach ($S['casos'] as $i => $c) : ?>
      <a class="tac-local-case" href="<?php echo esc_url(tac_app_url($c['app']) ?: tac_url('apps')); ?>" data-reveal style="--d:<?php echo (int) ($i * 80); ?>ms">
        <img src="<?php echo esc_url(tac_icon($c['app'])); ?>" alt="" width="48" height="48" loading="lazy">
        <b><?php echo esc_html($c['v']); ?></b><span><?php echo esc_html($tx($c['l'])); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</div></section>
<?php endif; ?>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Cómo trabajamos', 'How we work'); ?></div><h2><?php tac_e('De la primera llamada a producción, y después', 'From the first call to production, and beyond'); ?></h2></div>
  </div>
  <?php if (!empty($S['pasos'])) : ?>
    <div class="tac-steps" data-reveal>
      <?php foreach ($S['pasos'] as $i => $s) : ?>
        <div class="tac-step" style="--n:<?php echo (int) $i; ?>"><div class="n"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></div><h3><?php echo esc_html($tx($s['t'])); ?></h3><p><?php echo esc_html($tx($s['p'])); ?></p></div>
      <?php endforeach; ?>
    </div>
  <?php else : include __DIR__ . '/_process.php'; endif; ?>
</div></section>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Costos', 'Pricing'); ?></div><h2><?php tac_e('Precio y plazos por escrito, antes de empezar', 'Price and timeline in writing, before we start'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('La primera llamada y la propuesta no tienen costo. Elige el modelo que mejor encaje con tu proyecto.', 'The first call and the proposal are free. Pick the model that best fits your project.'); ?></p>
  </div>
  <?php include __DIR__ . '/_engage.php'; ?>
</div></section>

<?php if (!empty($S['faq'])) : ?>
<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Preguntas frecuentes', 'FAQ'); ?></div><h2><?php tac_e('Lo que suelen preguntarnos', 'What people usually ask'); ?></h2></div>
  </div>
  <div class="tac-faq">
    <?php foreach ($S['faq'] as $q) : ?>
      <details data-reveal><summary><?php echo esc_html($tx($q['q'])); ?></summary><p><?php echo esc_html($tx($q['a'])); ?></p></details>
    <?php endforeach; ?>
  </div>
</div></section>
<?php endif; ?>

<section class="tac-sec tac-srv-rel" style="padding-top:20px;padding-bottom:40px"><div class="tac-wrap">
  <h2 class="tac-srv-rel-h"><?php tac_e('Otros servicios', 'Other services'); ?></h2>
  <ul>
    <?php foreach ($otros as $v => $t) : if ($v === $tac_srv) { continue; } ?>
      <li><a href="<?php echo esc_url(tac_url($v)); ?>"><?php echo esc_html($t[$L]); ?> <span aria-hidden="true">→</span></a></li>
    <?php endforeach; ?>
    <li><a href="<?php echo esc_url(tac_url('apps')); ?>"><?php tac_e('Las 17 apps', 'All 17 apps'); ?> <span aria-hidden="true">→</span></a></li>
  </ul>
</div></section>

<?php include __DIR__ . '/_cta.php'; ?>
<script type="application/ld+json"><?php
echo wp_json_encode(array(
    '@context' => 'https://schema.org',
    '@graph'   => array_values(array_filter(array(
        array(
            '@type'       => 'Service',
            '@id'         => get_permalink() . '#service',
            'name'        => $tx($S['h1']),
            'serviceType' => $tx($S['tipo']),
            'description' => $tx($S['intro']),
            'provider'    => array('@id' => 'https://thealchemistcode.org/#organization'),
            'areaServed'  => $L
                ? array(array('@type' => 'Country', 'name' => 'United States'), array('@type' => 'Country', 'name' => 'Mexico'))
                : array(array('@type' => 'City', 'name' => 'Puebla'), array('@type' => 'Country', 'name' => 'México'), array('@type' => 'Country', 'name' => 'Estados Unidos')),
            'url'         => get_permalink(),
            'inLanguage'  => $L ? 'en' : 'es-MX',
        ),
        empty($S['faq']) ? null : array(
            '@type'      => 'FAQPage',
            'mainEntity' => array_map(function ($q) use ($tx) {
                return array('@type' => 'Question', 'name' => $tx($q['q']), 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $tx($q['a'])));
            }, $S['faq']),
        ),
    ))),
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
