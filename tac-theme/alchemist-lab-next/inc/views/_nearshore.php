<?php if (!defined('ABSPATH')) { exit; }
$items = array(
    array('clock', 'Tu horario', 'Your hours', 'Puebla está en UTC−6 todo el año: la misma hora que Chicago en invierno y una hora menos en verano. Juntas en tu mañana o en tu tarde.', 'Puebla is on UTC−6 all year: the same time as Chicago in winter, one hour behind in summer. Meetings in your morning or your afternoon.'),
    array('chat', 'En inglés, directo', 'In English, directly', 'Hablas con quien dirige el proyecto, en inglés (C1) o español. Sin intermediarios ni cuentas que cambian de manos.', 'You talk to the person leading the project, in English (C1) or Spanish. No account managers in between.'),
    array('doc', 'Contratos en USD', 'Contracts in USD', 'Contrato, facturación y pagos en dólares por transferencia o tarjeta. También en pesos con CFDI.', 'Contract, invoicing and payments in US dollars by wire or card. Mexican pesos with CFDI also available.'),
    array('lock', 'Tu propiedad intelectual', 'Your IP', 'Cesión completa del código y los repositorios. NDA firmado antes de conocer los detalles.', 'Full assignment of code and repositories. NDA signed before we see the details.'),
    array('phone', 'Avances que pruebas', 'Progress you can test', 'Builds frecuentes en TestFlight y en la prueba interna de Google Play, en tu propio teléfono.', 'Frequent builds on TestFlight and Google Play internal testing, on your own phone.'),
    array('shield', 'Software que se queda', 'Software that stays up', 'Operamos nuestras propias apps en producción: servidores, actualizaciones y reseñas, todos los días.', 'We run our own apps in production: servers, updates and reviews, every day.'),
);
$L = tac_lang() === 'en' ? 1 : 0; ?>
<div class="tac-near">
  <?php foreach ($items as $i => $it) : ?>
    <div class="tac-near-i" data-reveal style="--d:<?php echo (int) ($i * 70); ?>ms">
      <span class="ic"><?php echo tac_svg($it[0]); // phpcs:ignore ?></span>
      <h3><?php echo esc_html($L ? $it[2] : $it[1]); ?></h3>
      <p><?php echo esc_html($L ? $it[4] : $it[3]); ?></p>
    </div>
  <?php endforeach; ?>
</div>
