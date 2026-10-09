<?php if (!defined('ABSPATH')) { exit; }
/*
 * Caso de estudio completo. La página guarda tac_caso=<clave>; el contenido vive en inc/data/casos.json.
 * Solo hechos comprobables (repos, consolas de las tiendas, monitor); cada cifra lleva su fuente.
 */
$k = (string) get_post_meta(get_the_ID(), 'tac_caso', true);
$C = tac_casos_data()[$k] ?? null;
if (!$C) {
    return;
}
$L = tac_lang() === 'en' ? 1 : 0;
$tx = function ($par) use ($L) {
    return is_array($par) ? (string) ($par[$L] ?? $par[0]) : (string) $par;
};
$n = 0;
$num = function () use (&$n) {
    $n++;
    return sprintf('%02d', $n);
};
$app = $C['app'] ?? '';
$app_url = $app ? tac_app_url($app) : '';
$srv = tac_servicios();
?>
<section class="tac-phero tac-srv-hero tac-caso-hero"><div class="tac-wrap">
  <nav class="tac-crumbs" aria-label="<?php echo esc_attr(tac_t('Ruta', 'Breadcrumb')); ?>" data-reveal>
    <a href="<?php echo esc_url(tac_url('home')); ?>"><?php tac_e('Inicio', 'Home'); ?></a><span>/</span><a href="<?php echo esc_url(tac_url('casos')); ?>"><?php tac_e('Casos', 'Work'); ?></a><span>/</span><b><?php echo esc_html($C['nombre']); ?></b>
  </nav>
  <div class="tac-caso-top">
    <?php if ($app) : ?><img src="<?php echo esc_url(tac_icon($app)); ?>" alt="" width="72" height="72"><?php endif; ?>
    <div class="tac-label"><?php echo esc_html($tx($C['tipo'])); ?></div>
  </div>
  <h1 data-reveal style="--d:80ms"><?php echo esc_html($tx($C['h1'])); ?></h1>
  <p class="tac-sub" data-reveal style="--d:160ms"><?php echo esc_html($tx($C['resumen'])); ?></p>
  <dl class="tac-case-meta tac-caso-meta" data-reveal style="--d:200ms">
    <?php foreach ($C['meta'] as $m) : ?><div><dt><?php echo esc_html($tx($m['k'])); ?></dt><dd><?php echo esc_html($tx($m['v'])); ?></dd></div><?php endforeach; ?>
  </dl>
  <div class="tac-ctas" data-reveal style="--d:240ms">
    <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto parecido', 'Start a similar project'); ?> <span class="arr">→</span></a>
    <?php if ($app_url) : ?><a class="tac-btn tac-btn-line" href="<?php echo esc_url($app_url); ?>"><?php tac_e('Ver la app', 'See the app'); ?></a><?php endif; ?>
  </div>
  <?php if (!empty($C['metricas'])) : ?>
    <div class="tac-proof" data-reveal style="--d:320ms">
      <?php foreach ($C['metricas'] as $m) :
          $u = $m['u'] ?? ''; ?>
        <<?php echo $u ? 'a href="' . esc_url($u) . '" target="_blank" rel="noopener"' : 'div'; ?> class="tac-proof-i">
          <b<?php echo !empty($m['n']) ? ' data-count="' . (int) $m['n'] . '"' : ''; ?>><?php echo esc_html($tx($m['v'])); ?></b>
          <span class="l"><?php echo esc_html($tx($m['l'])); ?></span>
          <span class="d"><?php echo esc_html($tx($m['d'])); ?></span>
          <small><?php echo esc_html($tx($m['s'])); ?><?php echo $u ? ' <i aria-hidden="true">↗</i>' : ''; ?></small>
        </<?php echo $u ? 'a' : 'div'; ?>>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div></section>

<?php if (!empty($C['phones'])) : ?>
<section class="tac-sec" style="padding-top:20px;padding-bottom:20px"><div class="tac-wrap">
  <div class="tac-srv-phones n<?php echo (int) count($C['phones']); ?>" data-reveal>
    <?php foreach ($C['phones'] as $i => $ph) : ?><span style="--i:<?php echo (int) $i; ?>"><?php echo tac_phone($ph, $C['nombre']); // phpcs:ignore ?></span><?php endforeach; ?>
  </div>
</div></section>
<?php endif; ?>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('El reto', 'The challenge'); ?></div><h2><?php echo esc_html($tx($C['reto_h2'])); ?></h2></div>
    <div data-reveal style="--d:120ms" class="tac-caso-texto"><?php foreach ($C['reto'] as $p) : ?><p><?php echo esc_html($tx($p)); ?></p><?php endforeach; ?></div>
  </div>
</div></section>

<section class="tac-sec tac-near-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('Qué decidimos', 'What we decided'); ?></div><h2><?php tac_e('Decisiones técnicas, y por qué', 'Technical decisions, and why'); ?></h2></div>
  </div>
  <div class="tac-srv-principles">
    <?php foreach ($C['decisiones'] as $i => $d) : ?>
      <div data-reveal style="--d:<?php echo (int) ($i * 70); ?>ms"><span class="n"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span><h3><?php echo esc_html($tx($d['t'])); ?></h3><p><?php echo esc_html($tx($d['p'])); ?></p></div>
    <?php endforeach; ?>
  </div>
</div></section>

<?php if (!empty($C['operacion'])) : ?>
<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — <?php tac_e('En producción', 'In production'); ?></div><h2><?php echo esc_html($tx($C['operacion_h2'])); ?></h2></div>
  </div>
  <dl class="tac-uses">
    <?php foreach ($C['operacion'] as $i => $o) : ?>
      <div data-reveal style="--d:<?php echo (int) ($i * 60); ?>ms"><dt><?php echo esc_html($tx($o['t'])); ?></dt><dd><?php echo esc_html($tx($o['p'])); ?></dd></div>
    <?php endforeach; ?>
  </dl>
</div></section>
<?php endif; ?>

<section class="tac-sec" style="padding-top:40px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b><?php echo esc_html($num()); ?></b> — Stack</div><h2><?php tac_e('Con qué está hecho', 'What it\'s built with'); ?></h2></div>
  </div>
  <ul class="tac-stack tac-caso-stack" data-reveal><?php foreach ($C['stack'] as $s) : ?><li><?php echo esc_html($tx($s)); ?></li><?php endforeach; ?></ul>
  <?php if (!empty($C['servicio']) && isset($srv[$C['servicio']])) : ?>
    <a class="tac-post-srv tac-caso-srv" href="<?php echo esc_url(tac_url($C['servicio'])); ?>" data-reveal>
      <span class="tac-label"><?php tac_e('Servicio relacionado', 'Related service'); ?></span>
      <b><?php echo esc_html($srv[$C['servicio']][$L]); ?> <span aria-hidden="true">→</span></b>
    </a>
  <?php endif; ?>
  <?php
  $otros = array();
  foreach (tac_casos_data() as $ok => $oc) {
      if ($ok !== $k && ($u = tac_caso_url($ok))) {
          $otros[] = '<a href="' . esc_url($u) . '">' . esc_html($oc['nombre']) . ' <span aria-hidden="true">→</span></a>';
      }
  }
  if ($otros) : ?>
    <nav class="tac-post-more tac-caso-otros" aria-label="<?php echo esc_attr(tac_t('Otros casos', 'Other case studies')); ?>"><?php echo implode('', $otros); // phpcs:ignore -- escapado arriba ?><a class="back" href="<?php echo esc_url(tac_url('casos')); ?>"><?php tac_e('Todos los casos', 'All case studies'); ?></a></nav>
  <?php endif; ?>
</div></section>

<?php include __DIR__ . '/_cta.php'; ?>
<script type="application/ld+json"><?php
echo wp_json_encode(array(
    '@context'   => 'https://schema.org',
    '@type'      => 'Article',
    'headline'   => $tx($C['h1']),
    'description' => $tx($C['resumen']),
    'inLanguage' => $L ? 'en' : 'es-MX',
    'author'     => array('@id' => 'https://thealchemistcode.org/#organization'),
    'publisher'  => array('@id' => 'https://thealchemistcode.org/#organization'),
    'mainEntityOfPage' => get_permalink(),
    'about'      => array('@type' => 'SoftwareApplication', 'name' => $C['nombre'], 'applicationCategory' => $C['categoria'] ?? 'MobileApplication', 'operatingSystem' => $C['so'] ?? 'iOS, Android'),
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
