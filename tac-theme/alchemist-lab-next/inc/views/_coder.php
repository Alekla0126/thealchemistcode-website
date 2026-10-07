<?php if (!defined('ABSPATH')) { exit; }
/*
 * Invitación a probar Alchemist Coder (pedido del dueño, 7-oct-2026; texto del kit de lanzamiento de MarketingEngine).
 * Solo nombres de los agentes, sin logos de terceros ni superlativos. Release v1.1.0 y licencia AGPL-3.0 comprobadas en GitHub.
 */
$shot = tac_app_shots('coder');
$repo = 'https://github.com/Alekla0126/alchemist-coder';
?>
<section class="tac-sec tac-coder-sec" style="padding-top:40px">
  <div class="tac-wrap">
    <div class="tac-coder" data-reveal>
      <div class="tx">
        <div class="tac-label"><img src="<?php echo esc_url(tac_icon('coder')); ?>" alt="" width="22" height="22"> <?php tac_e('Open source · hecho por nosotros', 'Open source · built by us'); ?></div>
        <h2><?php tac_e('Prueba Alchemist Coder', 'Try Alchemist Coder'); ?></h2>
        <p><?php tac_e('El laboratorio open-source para tus agentes de código: Claude Code, Codex, Gemini CLI y Grok en una sola app, con todo tu historial guardado y buscable.', 'The open-source lab for your coding agents: Claude Code, Codex, Gemini CLI and Grok in one desktop app, with your whole history kept and searchable.'); ?></p>
        <ul class="tac-coder-meta"><li>v1.1.0</li><li>macOS · Windows · Linux</li><li>AGPL-3.0</li></ul>
        <div class="tac-ctas">
          <a class="tac-btn tac-btn-primary" href="<?php echo esc_url(tac_t('https://coder.alekla.com/es/', 'https://coder.alekla.com/')); ?>" target="_blank" rel="noopener"><?php tac_e('Pruébalo gratis', 'Try it free'); ?> <span class="arr">↗</span></a>
          <a class="tac-btn tac-btn-line" href="<?php echo esc_url($repo); ?>" target="_blank" rel="noopener"><?php tac_e('Colabora en GitHub', 'Contribute on GitHub'); ?></a>
        </div>
        <?php if (tac_app_url('coder') && tac_current_view() !== 'app') : ?><a class="tac-more" href="<?php echo esc_url(tac_app_url('coder')); ?>"><?php tac_e('Más sobre el editor', 'More about the editor'); ?> <span aria-hidden="true">→</span></a><?php endif; ?>
      </div>
      <?php if (!empty($shot[0])) : ?>
        <figure class="tac-browser tac-coder-shot">
          <div class="bar"><i></i><i></i><i></i><span>Alchemist Coder</span></div>
          <img src="<?php echo esc_url($shot[0]['src']); ?>"<?php echo $shot[0]['srcset']; // phpcs:ignore -- escapado en tac_srcset() ?> width="<?php echo (int) $shot[0]['w']; ?>" height="<?php echo (int) $shot[0]['h']; ?>" alt="<?php echo esc_attr(tac_t('Alchemist Coder: un agente principal con sus subagentes y el historial de conversaciones', 'Alchemist Coder: a main agent with its subagents and conversation history')); ?>" loading="lazy" decoding="async">
        </figure>
      <?php endif; ?>
    </div>
  </div>
</section>
