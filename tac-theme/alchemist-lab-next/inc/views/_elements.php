<?php if (!defined('ABSPATH')) { exit; }
$els = array(
    array('m', 'Fl', 'Flutter'), array('m', 'Sw', 'Swift'), array('m', 'Kt', 'Kotlin'), array('m', 'Wk', 'WidgetKit'),
    array('b', 'Fb', 'Firebase'), array('b', 'Py', 'FastAPI'), array('b', 'Lv', 'Laravel'), array('b', 'El', 'Electron'),
    array('a', 'Cl', 'Claude'), array('a', 'Oa', 'OpenAI'),
);
?>
<div class="tac-ptable">
  <?php foreach ($els as $i => $e) : ?>
    <div class="tac-el <?php echo esc_attr($e[0]); ?>" data-reveal style="--d:<?php echo (int) ($i * 60); ?>ms"><span class="z"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span><span class="s"><?php echo esc_html($e[1]); ?></span><span class="nm"><?php echo esc_html($e[2]); ?></span></div>
  <?php endforeach; ?>
</div>
<div class="tac-legend"><span><i style="background:var(--azul)"></i><?php tac_e('Móvil', 'Mobile'); ?></span><span><i style="background:var(--cian)"></i><?php tac_e('Backend y escritorio', 'Backend and desktop'); ?></span><span><i style="background:var(--ink)"></i><?php tac_e('Inteligencia artificial', 'Artificial intelligence'); ?></span></div>
