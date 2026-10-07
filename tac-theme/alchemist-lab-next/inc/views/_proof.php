<?php if (!defined('ABSPATH')) { exit; }
// Franja de pruebas del hero: cifra + etiqueta + definición + fuente (patrón Netguru/Metalab). Todo verificable.
$pruebas = array(
    array('100,000+', 100000, '+', array('Instalaciones en Google Play', 'Google Play installs'), array('De nuestras apps propias', 'Across our own apps'), array('Play Console · sep 2026', 'Play Console · Sep 2026'), tac_url('apps')),
    array('4.5★', 0, '', array('Soccer24 en Google Play', 'Soccer24 on Google Play'), array('Calificación pública de usuarios', 'Public user rating'), array('Google Play · oct 2026', 'Google Play · Oct 2026'), 'https://play.google.com/store/apps/details?id=com.alekla0126.soccer24'),
    array('17', 17, '', array('Apps publicadas', 'Apps shipped'), array('iOS, Android y escritorio, operadas por nosotros', 'iOS, Android and desktop, run in-house'), array('App Store y Google Play', 'App Store and Google Play'), tac_url('apps')),
    array('2025', 0, '', array('Investigación publicada', 'Published research'), array('Detección de fraude por phishing con IA', 'AI-driven phishing fraud detection'), array('Springer Nature · LNNS', 'Springer Nature · LNNS'), 'https://doi.org/10.1007/978-3-031-85363-0_21'),
);
$L = tac_lang() === 'en' ? 1 : 0; ?>
<div class="tac-proof" data-reveal style="--d:520ms">
  <?php foreach ($pruebas as $p) :
      $ext = strpos($p[6], home_url()) !== 0; ?>
    <a class="tac-proof-i" href="<?php echo esc_url($p[6]); ?>"<?php echo $ext ? ' target="_blank" rel="noopener"' : ''; ?>>
      <b<?php echo $p[1] ? ' data-count="' . (int) $p[1] . '" data-suffix="' . esc_attr($p[2]) . '"' : ''; ?>><?php echo esc_html($p[0]); ?></b>
      <span class="l"><?php echo esc_html($p[3][$L]); ?></span>
      <span class="d"><?php echo esc_html($p[4][$L]); ?></span>
      <small><?php echo esc_html($p[5][$L]); ?> <i aria-hidden="true">↗</i></small>
    </a>
  <?php endforeach; ?>
</div>
