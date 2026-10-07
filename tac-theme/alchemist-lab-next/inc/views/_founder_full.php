<?php if (!defined('ABSPATH')) { exit; } $L = tac_lang() === 'en' ? 1 : 0;
$cv = array(
    array(array('Formación', 'Education'), array('Ingeniería en Sistemas Computacionales con honores (Universidad Iberoamericana) y maestría en Ingeniería de Software y Sistemas (HSE University).', 'B.Eng. in Computer Systems, with honors (Universidad Iberoamericana), and M.Sc. in Software and Systems Engineering (HSE University).')),
    array(array('Producción', 'Production'), array('Apps en Flutter en producción para INOWU México (2019–2022) y 17 apps propias publicadas desde 2024.', 'Production Flutter apps for INOWU México (2019–2022) and 17 apps of our own shipped since 2024.')),
    array(array('IA', 'AI'), array('Detección de lavado de dinero con IA (2020–2021) y un capítulo sobre detección de fraude con IA en Springer (2025).', 'AI for anti-money-laundering (2020–2021) and a Springer chapter on AI fraud detection (2025).')),
    array(array('Salud', 'Health'), array('Mantenimiento y nuevas funciones de IRISA para la Asociación Mexicana de Suicidología (2021).', 'Maintenance and new features for IRISA, Mexican Association of Suicidology (2021).')),
);
$idiomas = array(array('Español', 'Spanish', tac_t('nativo', 'native')), array('Inglés', 'English', 'C1'), array('Ruso', 'Russian', 'B2'), array('Alemán', 'German', 'B1')); ?>
<div class="tac-founder">
  <figure class="tac-founder-photo" data-reveal="scale">
    <img src="<?php echo esc_url(tac_img('team/yabin.webp')); ?>"<?php echo tac_srcset('team/yabin.webp'); // phpcs:ignore ?> width="560" height="700" alt="Yabin Alejandro Lagunes Cuevas" loading="lazy" decoding="async">
    <figcaption><b>Yabin Alejandro Lagunes Cuevas</b><span><?php tac_e('Fundador e ingeniero principal', 'Founder & principal engineer'); ?></span></figcaption>
  </figure>
  <div class="tac-founder-body">
    <p class="tac-founder-lead" data-reveal><?php tac_e('Cada proyecto lo dirige el fundador de principio a fin: la primera llamada, la arquitectura, el código y el lanzamiento. Según lo que necesites se suman colaboradores especializados, y tú sigues hablando con la misma persona.', 'Every project is led by the founder from start to finish: the first call, the architecture, the code and the launch. Specialists join when your project needs them, and you keep talking to the same person.'); ?></p>
    <dl class="tac-cv" data-reveal style="--d:120ms">
      <?php foreach ($cv as $c) : ?><div><dt><?php echo esc_html($c[0][$L]); ?></dt><dd><?php echo esc_html($c[1][$L]); ?></dd></div><?php endforeach; ?>
    </dl>
    <ul class="tac-langs" data-reveal style="--d:200ms" aria-label="<?php echo esc_attr(tac_t('Idiomas', 'Languages')); ?>">
      <?php foreach ($idiomas as $i) : ?><li><?php echo esc_html($L ? $i[1] : $i[0]); ?> <span><?php echo esc_html($i[2]); ?></span></li><?php endforeach; ?>
    </ul>
  </div>
</div>
