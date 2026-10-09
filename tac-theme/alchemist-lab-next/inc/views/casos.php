<?php if (!defined('ABSPATH')) { exit; }
/* Índice de casos: los casos completos (casos.json) y, debajo, los casos resumidos del home. */
$L = tac_lang() === 'en' ? 1 : 0;
$tx = function ($par) use ($L) {
    return is_array($par) ? (string) ($par[$L] ?? $par[0]) : (string) $par;
};
?>
<section class="tac-phero tac-srv-hero"><div class="tac-wrap">
  <div class="tac-label" data-reveal><b>—</b> <?php tac_e('Casos', 'Work'); ?></div>
  <h1 data-reveal style="--d:80ms"><?php tac_e('Casos de estudio: software que ya está en producción', 'Case studies: software already in production'); ?></h1>
  <p class="tac-sub" data-reveal style="--d:160ms"><?php tac_e('Qué problema había, qué decidimos y qué resultó, con las cifras de las consolas de las tiendas y de nuestra propia infraestructura.', 'What the problem was, what we decided and how it turned out, with figures from the store consoles and our own infrastructure.'); ?></p>
</div></section>

<section class="tac-sec" style="padding-top:20px"><div class="tac-wrap">
  <div class="tac-casos-lista">
    <?php foreach (tac_casos_data() as $k => $C) :
        $u = tac_caso_url($k);
        if (!$u) {
            continue;
        } ?>
      <a class="tac-caso-card" href="<?php echo esc_url($u); ?>" data-reveal>
        <div class="txt">
          <div class="top"><?php if (!empty($C['app'])) : ?><img src="<?php echo esc_url(tac_icon($C['app'])); ?>" alt="" width="48" height="48" loading="lazy"><?php endif; ?><span class="tac-label"><?php echo esc_html($tx($C['tipo'])); ?></span></div>
          <h2><?php echo esc_html($tx($C['h1'])); ?></h2>
          <p><?php echo esc_html($tx($C['resumen'])); ?></p>
          <?php if (!empty($C['metricas'])) : ?>
            <div class="nums"><?php foreach (array_slice($C['metricas'], 0, 3) as $m) : ?><span><b><?php echo esc_html($tx($m['v'])); ?></b><?php echo esc_html($tx($m['l'])); ?></span><?php endforeach; ?></div>
          <?php endif; ?>
          <span class="mas"><?php tac_e('Leer el caso', 'Read the case study'); ?> →</span>
        </div>
        <?php if (!empty($C['phones'][0])) : ?><div class="media"><?php echo tac_phone($C['phones'][0], ''); // phpcs:ignore ?></div><?php endif; ?>
      </a>
    <?php endforeach; ?>
  </div>
</div></section>

<section class="tac-sec" style="padding-top:20px"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div data-reveal><div class="tac-label"><b>—</b> <?php tac_e('Más trabajo', 'More work'); ?></div><h2><?php tac_e('Casos resumidos', 'Case summaries'); ?></h2></div>
    <p class="tac-sub" data-reveal style="--d:120ms"><?php tac_e('Los mismos casos que ves en el inicio, con IRISA, el sistema clínico de la Asociación Mexicana de Suicidología.', 'The same cases you see on the home page, including IRISA, the clinical system for the Mexican Association of Suicidology.'); ?></p>
  </div>
</div>
<?php include __DIR__ . '/_cases.php'; ?>
</section>

<?php include __DIR__ . '/_cta.php'; ?>
