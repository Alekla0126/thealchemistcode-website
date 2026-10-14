<?php if (!defined('ABSPATH')) { exit; }
/*
 * Panel ilustrativo de videovigilancia (página de cámaras y monitoreo). No son imágenes ni datos de un cliente:
 * el texto de la sección y la etiqueta "Ejemplo" lo dicen. Solo CSS, sin video.
 */
$L = tac_lang() === 'en' ? 1 : 0;
$camaras = array(
    array('01', array('Entrada principal', 'Main entrance'), 'ok'),
    array('02', array('Cajas', 'Registers'), 'ok'),
    array('03', array('Estacionamiento', 'Parking lot'), 'ok'),
    array('04', array('Andén de carga', 'Loading dock'), 'mov'),
    array('05', array('Bodega · pasillo B', 'Warehouse · aisle B'), 'off'),
    array('06', array('Perímetro norte', 'North perimeter'), 'ok'),
);
$sitios = array(
    array(array('Sucursal Centro', 'Downtown branch'), array('6 cámaras · grabando', '6 cameras · recording'), 'ok'),
    array(array('Bodega', 'Warehouse'), array('3 de 4 en línea', '3 of 4 online'), 'warn'),
    array(array('Oficinas', 'Offices'), array('4 cámaras · grabando', '4 cameras · recording'), 'ok'),
);
$alertas = array(
    array('02:14', array('CAM 05 sin señal · técnico avisado', 'CAM 05 offline · technician notified'), 'off'),
    array('01:52', array('Movimiento fuera de horario · andén', 'After-hours motion · loading dock'), 'mov'),
    array('00:30', array('Disco al 92 % · cambio programado', 'Drive at 92% · replacement scheduled'), 'warn'),
);
$estado = array('ok' => array('En línea', 'Online'), 'mov' => array('Movimiento', 'Motion'), 'off' => array('Sin señal', 'No signal'));
?>
<div class="tac-cams" data-reveal aria-label="<?php echo esc_attr(tac_t('Ejemplo ilustrativo de un panel de monitoreo de cámaras', 'Illustrative example of a camera monitoring dashboard')); ?>" role="img">
  <span class="tac-cams-tag"><?php tac_e('Ejemplo', 'Example'); ?></span>
  <div class="tac-cams-grid" aria-hidden="true">
    <?php foreach ($camaras as $i => $c) : ?>
      <div class="cam <?php echo esc_attr($c[2]); ?>" style="--i:<?php echo (int) $i; ?>">
        <i class="scan"></i>
        <?php if ($c[2] === 'mov') : ?><i class="zona"></i><?php endif; ?>
        <span class="top"><b>CAM <?php echo esc_html($c[0]); ?></b><?php if ($c[2] !== 'off') : ?><em class="rec">REC</em><?php endif; ?></span>
        <span class="bot"><?php echo esc_html($c[1][$L]); ?><small class="st"><i></i><?php echo esc_html($estado[$c[2]][$L]); ?></small></span>
      </div>
    <?php endforeach; ?>
  </div>
  <aside class="tac-cams-side" aria-hidden="true">
    <div class="tac-ops-h"><span class="dot"></span><?php tac_e('Centro de monitoreo · 24/7', 'Monitoring center · 24/7'); ?></div>
    <ul class="sitios">
      <?php foreach ($sitios as $s) : ?><li class="<?php echo esc_attr($s[2]); ?>"><b><?php echo esc_html($s[0][$L]); ?></b><span><?php echo esc_html($s[1][$L]); ?></span></li><?php endforeach; ?>
    </ul>
    <div class="alertas-h"><?php tac_e('Alertas de esta noche', 'Tonight\'s alerts'); ?></div>
    <ul class="alertas">
      <?php foreach ($alertas as $a) : ?><li class="<?php echo esc_attr($a[2]); ?>"><code><?php echo esc_html($a[0]); ?></code><span><?php echo esc_html($a[1][$L]); ?></span></li><?php endforeach; ?>
    </ul>
  </aside>
</div>
