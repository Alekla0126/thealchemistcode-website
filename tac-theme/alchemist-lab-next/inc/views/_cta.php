<?php if (!defined('ABSPATH')) { exit; } ?>
<section class="tac-cta">
  <div class="tac-wrap"><div class="tac-cta-box" data-reveal="scale">
    <div><h2><?php tac_e('¿Qué tiene que salir bien en tu proyecto?', 'What does success look like for your product?'); ?></h2><p><?php tac_e('La primera llamada y la propuesta no tienen costo. Te responde en persona quien va a dirigir tu proyecto.', 'The first call and the proposal are free. The person who will lead your project replies in person.'); ?></p></div>
    <div class="tac-ctas" style="margin-top:0">
      <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_url('contact')); ?>"><?php tac_e('Iniciar un proyecto', 'Start a project'); ?> →</a>
      <a class="tac-btn tac-btn-line" href="<?php echo esc_url(tac_url('home') . '#' . tac_t('casos', 'work')); ?>"><?php tac_e('Ver casos', 'See our work'); ?></a>
    </div>
  </div></div>
</section>
