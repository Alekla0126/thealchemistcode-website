<?php if (!defined('ABSPATH')) { exit; }
// Testimonios y logos: solo si hay datos reales en pruebas.json.
$pr = tac_pruebas(); $L = tac_lang() === 'en' ? 1 : 0;
if (!empty($pr['testimonios'])) : ?>
<section class="tac-sec tac-quotes-sec" style="padding-top:40px">
  <div class="tac-wrap">
    <div class="tac-sec-head"><div data-reveal><div class="tac-label"><?php tac_e('Clientes', 'Clients'); ?></div><h2><?php tac_e('Lo que dicen quienes ya trabajaron con nosotros', 'What our clients say'); ?></h2></div></div>
    <div class="tac-quotes">
      <?php foreach ($pr['testimonios'] as $i => $t) :
          $cita = $L ? ($t['cita_en'] ?: $t['cita_es']) : ($t['cita_es'] ?: $t['cita_en']);
          if (!$cita || empty($t['nombre'])) { continue; } ?>
        <figure class="tac-quote" data-reveal style="--d:<?php echo (int) ($i * 90); ?>ms">
          <blockquote>“<?php echo esc_html($cita); ?>”</blockquote>
          <figcaption>
            <?php if (!empty($t['foto'])) : ?><img src="<?php echo esc_url(tac_img('testimonios/' . $t['foto'])); ?>" alt="" width="48" height="48" loading="lazy"><?php endif; ?>
            <span><b><?php echo esc_html($t['nombre']); ?></b><?php echo esc_html(trim(($L ? ($t['cargo_en'] ?? '') : ($t['cargo_es'] ?? '')) . ', ' . ($t['empresa'] ?? ''), ', ')); ?></span>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif;
