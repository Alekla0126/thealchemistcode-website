<?php if (!defined('ABSPATH')) { exit; }
$lang = tac_lang();
$live = tac_apps_live(); ?>
<section class="tac-phero"><div class="tac-wrap" data-reveal>
  <div class="tac-label"><b>—</b> Apps</div>
  <h1><?php tac_e('17 apps propias, publicadas y en operación', '17 apps we built, shipped and still run'); ?></h1>
  <p class="tac-sub"><?php tac_e('Todas están publicadas hoy. Diseño, código, revisión en tiendas, servidores, reseñas y actualizaciones: todo lo hacemos nosotros.', 'All of them are live today. Design, code, store review, servers, reviews and updates: we do it all ourselves.'); ?></p>
  <div class="tac-asof"><?php tac_e('Enlaces comprobados en App Store y Google Play. Las apps de iOS en revisión de Apple aparecen al ser aprobadas.', 'Links checked against the App Store and Google Play. iOS apps still in Apple review appear once approved.'); ?></div>
</div></section>
<section style="padding:0 0 96px"><div class="tac-wrap">
  <div class="tac-filter" role="group" data-reveal>
    <button type="button" class="on" data-f=""><?php tac_e('Todas', 'All'); ?></button>
    <?php foreach ($live['categorias'] as $ck => $cn) : ?><button type="button" data-f="<?php echo esc_attr($ck); ?>"><?php echo esc_html($cn[$lang] ?? $ck); ?></button><?php endforeach; ?>
  </div>
  <div class="tac-appgrid">
    <?php $i = 0; foreach ($live['apps'] as $k => $a) :
      $url = tac_app_url($k);
      $e = $a['enlaces']; ?>
      <article class="tac-card tac-appcard" data-cat="<?php echo esc_attr($a['categoria']); ?>" data-reveal style="--d:<?php echo (int) (($i++ % 3) * 70); ?>ms">
        <div class="top"><img src="<?php echo esc_url(tac_icon($k)); ?>" alt="<?php echo esc_attr($a['nombre']); ?>" width="56" height="56" loading="lazy">
          <div><h3><?php if ($url) : ?><a href="<?php echo esc_url($url); ?>"><?php endif; ?><?php echo esc_html($a['nombre']); ?><?php if ($url) : ?></a><?php endif; ?></h3><span class="cat"><?php echo esc_html($live['categorias'][$a['categoria']][$lang] ?? ''); ?></span></div></div>
        <p><?php echo esc_html($a[$lang] ?? ''); ?></p>
        <div class="meta"><span class="os"><?php echo esc_html(implode(' · ', array_filter(array(isset($e['ios']) ? 'iOS' : '', isset($e['android']) ? 'Android' : '', isset($e['escritorio']) ? tac_t('Escritorio', 'Desktop') : '')))); ?></span>
          <?php if ($url) : ?><a class="more" href="<?php echo esc_url($url); ?>"><?php tac_e('Ver app', 'View app'); ?> <span class="arr">→</span></a><?php endif; ?></div>
      </article>
    <?php endforeach; ?>
  </div>
</div></section>
<script>
document.addEventListener('click',function(e){var b=e.target.closest('.tac-filter button');if(!b)return;var f=b.getAttribute('data-f');
b.parentNode.querySelectorAll('button').forEach(function(x){x.classList.toggle('on',x===b)});
document.querySelectorAll('.tac-appcard').forEach(function(c){c.hidden=!!f&&c.getAttribute('data-cat')!==f});});
</script>
<?php include __DIR__ . '/_coder.php'; ?>
<?php include __DIR__ . '/_cta.php'; ?>
