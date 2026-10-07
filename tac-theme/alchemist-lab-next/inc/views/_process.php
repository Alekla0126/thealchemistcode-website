<?php if (!defined('ABSPATH')) { exit; }
$steps = array(
  array('Diagnóstico', 'Discovery', 'Entendemos el problema, los usuarios y tus sistemas. Sin costo.', 'We map the problem, the users and your systems. Free.'),
  array('Propuesta y diseño', 'Proposal and design', 'Alcance, plazos y precio por escrito, con las pantallas antes del código.', 'Scope, timeline and price in writing, with screens before code.'),
  array('Desarrollo', 'Build', 'Entregas frecuentes que instalas en tu propio teléfono.', 'Frequent builds you install on your own phone.'),
  array('Lanzamiento', 'Launch', 'Publicación en App Store y Google Play, incluida la revisión de Apple.', "Release on the App Store and Google Play, including Apple's review."),
  array('Soporte', 'Support', 'Mantenimiento mensual con tiempos de respuesta por escrito.', 'Monthly maintenance with written response times.'),
); ?>
<div class="tac-steps" data-reveal>
  <?php foreach ($steps as $i => $s) : ?>
    <div class="tac-step" style="--n:<?php echo (int) $i; ?>"><div class="n"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></div><h3><?php echo esc_html(tac_t($s[0], $s[1])); ?></h3><p><?php echo esc_html(tac_t($s[2], $s[3])); ?></p></div>
  <?php endforeach; ?>
</div>
