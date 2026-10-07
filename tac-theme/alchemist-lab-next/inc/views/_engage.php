<?php if (!defined('ABSPATH')) { exit; }
// Modelos de contratación. Los precios salen de pruebas.json; sin monto se dice cómo se fija el precio.
$modelos = array(
    array('proyecto', array('Proyecto con alcance fijo', 'Fixed-scope project'), array('Para una app nueva o un MVP.', 'For a new app or an MVP.'),
        array(array('Alcance, plazos y precio por escrito antes de empezar', 'Scope, timeline and price in writing before we start'), array('Pantallas aprobadas antes del código', 'Screens approved before code'), array('Publicación en App Store y Google Play', 'Release on the App Store and Google Play')),
        array('Precio fijo tras el diagnóstico', 'Fixed price after discovery'), array('', '')),
    array('equipo', array('Equipo dedicado', 'Dedicated team'), array('Para un producto que evoluciona cada semana.', 'For a product that evolves every week.'),
        array(array('Capacidad mensual de ingeniería y diseño', 'Monthly engineering and design capacity'), array('Prioridades que defines tú cada semana', 'Priorities you set every week'), array('Builds frecuentes en tu propio teléfono', 'Frequent builds on your own phone')),
        array('Tarifa mensual fija', 'Fixed monthly rate'), array(' al mes', ' / month')),
    array('mantenimiento', array('Mantenimiento y soporte', 'Maintenance and support'), array('Para apps que ya están en las tiendas.', 'For apps already in the stores.'),
        array(array('Actualizaciones de iOS, Android y dependencias', 'iOS, Android and dependency updates'), array('Falla crítica: respuesta en 4 horas hábiles', 'Critical issue: response within 4 business hours'), array('Servidores, monitoreo y reseñas', 'Servers, monitoring and reviews')),
        array('Plan mensual con tiempos por escrito', 'Monthly plan with written response times'), array(' al mes', ' / month')),
);
$formal = array(
    array('Contrato y NDA', 'Contract and NDA', 'Firmamos el NDA antes de que nos cuentes los detalles.', 'We sign your NDA before you share the details.'),
    array('El código es tuyo', 'You own the code', 'Al terminar, repositorios, documentación y accesos.', 'At handover: repositories, documentation and access.'),
    array('CFDI o USD', 'CFDI or USD', 'Factura mexicana, también si pagas en dólares.', 'Mexican invoices, or USD by wire or card.'),
    array('Garantía de 60 días', '60-day warranty', 'Defectos corregidos sin costo después de la entrega.', 'Defects fixed at no cost after delivery.'),
    array('Listo para compras', 'Procurement-ready', 'Constancia fiscal, opinión 32-D positiva, contrato modelo y NDA.', 'Tax certificate, compliance opinion, model contract and NDA.'),
);
$L = tac_lang() === 'en' ? 1 : 0; ?>
<div class="tac-models">
  <?php foreach ($modelos as $i => $m) :
      $precio = tac_precio_desde($m[0], $m[5][0], $m[5][1]); ?>
    <article class="tac-model" data-reveal style="--d:<?php echo (int) ($i * 90); ?>ms">
      <span class="k"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
      <h3><?php echo esc_html($m[1][$L]); ?></h3>
      <p><?php echo esc_html($m[2][$L]); ?></p>
      <ul><?php foreach ($m[3] as $x) : ?><li><?php echo esc_html($x[$L]); ?></li><?php endforeach; ?></ul>
      <div class="price"><?php echo $precio ? esc_html($precio) : esc_html($m[4][$L]); ?></div>
    </article>
  <?php endforeach; ?>
</div>
<ul class="tac-formal" data-reveal>
  <?php foreach ($formal as $f) : ?>
    <li><?php echo tac_svg('check'); // phpcs:ignore ?><span><b><?php echo esc_html($L ? $f[1] : $f[0]); ?></b><?php echo esc_html($L ? $f[3] : $f[2]); ?></span></li>
  <?php endforeach; ?>
</ul>
