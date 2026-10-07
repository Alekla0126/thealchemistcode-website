<?php if (!defined('ABSPATH')) { exit; }
$tac_h = isset($tac_contact_h1) ? 'h1' : 'h2'; ?>
<section class="tac-sec tac-contact" id="<?php tac_e('contacto', 'contact'); ?>">
  <div class="tac-wrap tac-cgrid">
    <div data-reveal>
      <div class="tac-label"><?php tac_e('Contacto', 'Contact'); ?></div>
      <<?php echo $tac_h; ?> style="margin-top:16px;font-size:42px"><?php echo isset($tac_contact_h1) ? esc_html(tac_t('Cotiza tu app o proyecto de IA', 'Get a quote for your app or AI project')) : esc_html(tac_t('¿Qué tiene que salir bien en tu proyecto?', 'What does success look like for your product?')); ?></<?php echo $tac_h; ?>>
      <p class="tac-sub" style="margin-top:18px"><?php tac_e('La primera llamada y la propuesta no tienen costo. Te responde en persona quien va a dirigir tu proyecto.', 'The first call and the proposal are free. The person who will lead your project replies in person.'); ?></p>
      <div class="tac-channels">
        <a class="tac-ch" href="mailto:<?php echo esc_attr(TAC_MAIL); ?>"><?php echo tac_svg('mail'); ?><span><?php tac_e('Correo', 'Email'); ?><small><?php echo esc_html(TAC_MAIL); ?></small></span></a>
        <a class="tac-ch" href="<?php echo esc_url(tac_wa(tac_t('Hola, quiero agendar una videollamada de 30 minutos por Google Meet.', 'Hi, I would like to book a 30-minute Google Meet call.'))); ?>" target="_blank" rel="noopener"><?php echo tac_svg('video'); ?><span><?php tac_e('Videollamada de 30 min', '30-min video call'); ?><small><?php tac_e('Por Google Meet. Pídela por WhatsApp o correo', 'On Google Meet. Request it by WhatsApp or email'); ?></small></span></a>
        <a class="tac-ch" href="<?php echo esc_url(tac_wa(tac_t('Hola, quiero platicar sobre un proyecto.', 'Hi, I would like to talk about a project.'))); ?>" target="_blank" rel="noopener"><?php echo tac_svg('wa'); ?><span>WhatsApp<small><?php echo esc_html(TAC_WA_HUMAN); ?></small></span></a>
        <div class="tac-ch"><?php echo tac_svg('clock'); ?><span><?php tac_e('Horario', 'Hours'); ?><small><?php tac_e('Lunes a viernes, horario de México (UTC−6)', 'Monday to Friday, Mexico time (UTC−6)'); ?></small></span></div>
      </div>
    </div>
    <div data-reveal style="--d:150ms"><?php echo tac_contact_form(); // phpcs:ignore ?></div>
  </div>
</section>
