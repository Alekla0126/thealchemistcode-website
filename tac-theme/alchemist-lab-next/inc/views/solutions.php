<?php if (!defined('ABSPATH')) { exit; } ?>
<section class="tac-phero"><div class="tac-wrap" data-reveal>
  <div class="tac-label"><b>01</b> — <?php tac_e('Soluciones', 'Solutions'); ?></div>
  <h1><?php tac_e('Desarrollo de apps a la medida, IA e infraestructura', 'Custom app, AI and infrastructure development'); ?></h1>
  <p class="tac-sub"><?php tac_e('Partimos de tu necesidad, no de la tecnología. Cada solución se apoya en algo que ya construimos, publicamos y operamos.', 'We start from your need, not from the technology. Each solution builds on something we already built, shipped and run.'); ?></p>
</div></section>
<section class="tac-sec" style="padding-top:10px"><div class="tac-wrap"><?php include __DIR__ . '/_solutions.php'; ?></div></section>
<section class="tac-sec"><div class="tac-wrap">
  <div class="tac-sec-head">
    <div><div class="tac-label"><b>02</b> — <?php tac_e('Cómo trabajamos', 'How we work'); ?></div><h2><?php tac_e('De la idea a la tienda, y después', 'From idea to store, and beyond'); ?></h2></div>
    <p class="tac-sub"><?php tac_e('Un proceso corto y visible. Ves avances en tu teléfono, no informes.', 'A short, visible process. You see progress on your phone, not in reports.'); ?></p>
  </div>
  <?php include __DIR__ . '/_process.php'; ?>
</div></section>
<section class="tac-sec"><div class="tac-wrap"><div class="tac-sec-head"><div><div class="tac-label"><b>03</b> — <?php tac_e('Cómo contratarnos', 'How to engage'); ?></div><h2><?php tac_e('Tres formas de trabajar, todas por escrito', 'Three ways to work together, all in writing'); ?></h2></div></div><?php include __DIR__ . '/_engage.php'; ?></div></section>
<section class="tac-sec" style="padding-top:20px"><div class="tac-wrap"><p class="tac-local-more"><?php tac_e('¿Buscas un equipo en Puebla?', 'Looking for a team in Mexico?'); ?> <a href="<?php echo esc_url(tac_url('local')); ?>"><?php tac_e('Desarrollo de software en Puebla', 'Software development company in Mexico'); ?></a></p></div></section>
<?php include __DIR__ . '/_cta.php'; ?>
