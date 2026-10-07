<?php if (!defined('ABSPATH')) { exit; }
$cl = tac_pruebas()['clientes'];
if (!$cl) { return; } ?>
<section class="tac-clients" aria-label="<?php echo esc_attr(tac_t('Clientes', 'Clients')); ?>">
  <div class="tac-wrap">
    <p data-reveal><?php tac_e('Han confiado en nosotros', 'Trusted by'); ?></p>
    <ul data-reveal style="--d:120ms">
      <?php foreach ($cl as $c) : if (empty($c['logo'])) { continue; } ?>
        <li><?php if (!empty($c['url'])) : ?><a href="<?php echo esc_url($c['url']); ?>" target="_blank" rel="noopener"><?php endif; ?><img src="<?php echo esc_url(tac_img('clientes/' . $c['logo'])); ?>" alt="<?php echo esc_attr($c['nombre'] ?? ''); ?>" loading="lazy" height="40"><?php if (!empty($c['url'])) : ?></a><?php endif; ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
