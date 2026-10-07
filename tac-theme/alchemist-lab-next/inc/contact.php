<?php
/**
 * Formulario de contacto propio: guarda cada solicitud como entrada privada (tac_lead) y avisa por correo.
 * Protecciones: origen del envío, campo trampa, tiempo mínimo de llenado y límite por IP.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    register_post_type('tac_lead', array(
        'labels'       => array('name' => 'Solicitudes', 'singular_name' => 'Solicitud'),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-email-alt',
        'supports'     => array('title', 'editor', 'custom-fields'),
        'capability_type' => 'post',
        'capabilities' => array('create_posts' => 'do_not_allow'),
        'map_meta_cap' => true,
    ));
});

function tac_need_options() {
    return array(
        'nueva'      => array('App nueva', 'New app'),
        'mejorar'    => array('Mejorar una app', 'Improve an app'),
        'ia'         => array('Inteligencia artificial', 'Artificial intelligence'),
        'integracion'=> array('Integración con mi sistema', 'Integrate my system'),
        'infra'      => array('Infraestructura', 'Infrastructure'),
        'soporte'    => array('Soporte', 'Support'),
    );
}

function tac_budget_options() {
    return array(
        'nose'  => array('Aún no lo sé', 'Not sure yet'),
        'b1'    => array('Menos de $100k MXN', 'Under US$5k'),
        'b2'    => array('$100k–300k MXN', 'US$5k–15k'),
        'b3'    => array('$300k–1M MXN', 'US$15k–50k'),
        'b4'    => array('Más de $1M MXN', 'Over US$50k'),
    );
}

function tac_contact_form() {
    $lang = tac_lang();
    $status = isset($_GET['enviado']) ? sanitize_key(wp_unslash($_GET['enviado'])) : '';
    ob_start(); ?>
<form class="tac-form" method="post" action="<?php echo esc_url(get_permalink()); ?>#formulario" id="formulario">
  <input type="hidden" name="tac_form" value="contact">
  <input type="hidden" name="lang" value="<?php echo esc_attr($lang); ?>">
  <input type="hidden" name="back" value="<?php echo esc_url(get_permalink()); ?>">
  <input type="hidden" name="t0" value="<?php echo esc_attr(time()); ?>">
  <?php if ($status === '1') : ?>
    <div class="tac-msg ok" role="status"><?php tac_e('Gracias. Recibimos tu mensaje y te respondemos en persona, normalmente en menos de un día hábil.', 'Thank you. We got your message and will reply personally, usually within one business day.'); ?></div>
  <?php elseif ($status === '0') : ?>
    <div class="tac-msg err" role="alert"><?php tac_e('No pudimos enviar el formulario. Revisa los campos obligatorios o escríbenos por WhatsApp.', 'We could not send the form. Check the required fields or message us on WhatsApp.'); ?></div>
  <?php endif; ?>
  <label class="tac-fld"><?php tac_e('Nombre *', 'Name *'); ?><input class="tac-inp" name="nombre" required maxlength="120" autocomplete="name" placeholder="<?php echo esc_attr(tac_t('Tu nombre', 'Your name')); ?>"></label>
  <label class="tac-fld"><?php tac_e('Empresa', 'Company'); ?><input class="tac-inp" name="empresa" maxlength="160" autocomplete="organization" placeholder="<?php echo esc_attr(tac_t('Nombre de tu empresa', 'Your company')); ?>"></label>
  <label class="tac-fld"><?php tac_e('Correo *', 'Email *'); ?><input class="tac-inp" type="email" name="correo" required maxlength="160" autocomplete="email" placeholder="<?php echo esc_attr(tac_t('tu@empresa.com', 'you@company.com')); ?>"></label>
  <label class="tac-fld">WhatsApp<input class="tac-inp" name="whatsapp" maxlength="40" autocomplete="tel" placeholder="<?php echo esc_attr(tac_t('Opcional', 'Optional')); ?>"></label>
  <fieldset class="tac-fld full" style="border:0;padding:0;margin:0"><legend style="margin-bottom:7px"><?php tac_e('¿Qué necesitas?', 'What do you need?'); ?></legend><div class="tac-chips">
    <?php foreach (tac_need_options() as $k => $o) : ?>
      <label><input type="checkbox" name="necesidad[]" value="<?php echo esc_attr($k); ?>"><span><?php echo esc_html($lang === 'en' ? $o[1] : $o[0]); ?></span></label>
    <?php endforeach; ?>
  </div></fieldset>
  <fieldset class="tac-fld full" style="border:0;padding:0;margin:0"><legend style="margin-bottom:7px"><?php tac_e('Presupuesto aproximado', 'Approximate budget'); ?></legend><div class="tac-chips">
    <?php foreach (tac_budget_options() as $k => $o) : ?>
      <label><input type="radio" name="presupuesto" value="<?php echo esc_attr($k); ?>"><span><?php echo esc_html($lang === 'en' ? $o[1] : $o[0]); ?></span></label>
    <?php endforeach; ?>
  </div></fieldset>
  <label class="tac-fld full"><?php tac_e('Cuéntanos el proyecto *', 'Tell us about the project *'); ?><textarea class="tac-inp" name="mensaje" required maxlength="4000" placeholder="<?php echo esc_attr(tac_t('Qué quieres lograr, para quién y para cuándo…', 'What you want to achieve, for whom and by when…')); ?>"></textarea></label>
  <label class="tac-hp" aria-hidden="true">Sitio web<input name="sitio_web" tabindex="-1" autocomplete="off"></label>
  <label class="tac-consent"><input type="checkbox" name="consentimiento" value="1" required><span><?php
    printf(
        tac_t('Acepto el <a href="%s">aviso de privacidad</a>. Usamos tus datos solo para responderte.', 'I accept the <a href="%s">privacy notice</a>. We only use your data to reply.'),
        esc_url(tac_url('privacy'))
    ); ?></span></label>
  <button class="tac-btn tac-btn-primary" type="submit"><?php tac_e('Enviar', 'Send'); ?> →</button>
</form>
<?php
    return ob_get_clean();
}

function tac_handle_contact() {
    $back = isset($_POST['back']) ? esc_url_raw(wp_unslash($_POST['back'])) : home_url('/');
    if (strpos($back, home_url()) !== 0) {
        $back = home_url('/');
    }
    $fail = function () use ($back) {
        wp_safe_redirect(add_query_arg('enviado', '0', $back) . '#formulario');
        exit;
    };

    // Sin nonce: las páginas con el formulario se sirven desde la caché y un nonce caducaría en un día.
    // En su lugar se exige que el envío venga de este mismo sitio.
    $ref = wp_get_raw_referer();
    if ($ref && wp_parse_url($ref, PHP_URL_HOST) !== wp_parse_url(home_url(), PHP_URL_HOST)) {
        $fail();
    }
    // Trampas para bots: campo oculto relleno o envío en menos de 3 segundos. Se finge éxito.
    $t0 = isset($_POST['t0']) ? (int) $_POST['t0'] : 0;
    if (!empty($_POST['sitio_web']) || ($t0 && time() - $t0 < 3)) {
        wp_safe_redirect(add_query_arg('enviado', '1', $back) . '#formulario');
        exit;
    }
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    $key = 'tac_rl_' . md5($ip);
    $count = (int) get_transient($key);
    if ($count >= 5) {
        $fail();
    }
    set_transient($key, $count + 1, HOUR_IN_SECONDS);

    $f = array(
        'nombre'    => sanitize_text_field(wp_unslash($_POST['nombre'] ?? '')),
        'empresa'   => sanitize_text_field(wp_unslash($_POST['empresa'] ?? '')),
        'correo'    => sanitize_email(wp_unslash($_POST['correo'] ?? '')),
        'whatsapp'  => sanitize_text_field(wp_unslash($_POST['whatsapp'] ?? '')),
        'mensaje'   => sanitize_textarea_field(wp_unslash($_POST['mensaje'] ?? '')),
        'lang'      => (($_POST['lang'] ?? '') === 'en') ? 'en' : 'es',
    );
    $needs = array_intersect(array_map('sanitize_key', (array) wp_unslash($_POST['necesidad'] ?? array())), array_keys(tac_need_options()));
    $budget = sanitize_key(wp_unslash($_POST['presupuesto'] ?? ''));
    $budget = isset(tac_budget_options()[$budget]) ? tac_budget_options()[$budget][0] : '';
    if ($f['nombre'] === '' || !is_email($f['correo']) || $f['mensaje'] === '' || empty($_POST['consentimiento'])) {
        $fail();
    }
    $need_labels = array_map(function ($k) { return tac_need_options()[$k][0]; }, $needs);

    $body = "Nombre: {$f['nombre']}\nEmpresa: {$f['empresa']}\nCorreo: {$f['correo']}\nWhatsApp: {$f['whatsapp']}\n"
          . 'Necesita: ' . implode(', ', $need_labels) . "\nPresupuesto: {$budget}\nIdioma: {$f['lang']}\n\n{$f['mensaje']}\n\n"
          . "Aceptó el aviso de privacidad el " . wp_date('Y-m-d H:i') . " desde {$back}";

    $id = wp_insert_post(array(
        'post_type'    => 'tac_lead',
        'post_status'  => 'private',
        'post_title'   => $f['nombre'] . ($f['empresa'] ? ' · ' . $f['empresa'] : ''),
        'post_content' => $body,
    ));
    if ($id && !is_wp_error($id)) {
        update_post_meta($id, 'correo', $f['correo']);
        update_post_meta($id, 'presupuesto', $budget);
    }

    wp_mail(
        TAC_MAIL,
        '[Web] Nueva solicitud: ' . $f['nombre'] . ($f['empresa'] ? ' (' . $f['empresa'] . ')' : ''),
        $body,
        array('Reply-To: ' . $f['nombre'] . ' <' . $f['correo'] . '>')
    );

    wp_safe_redirect(add_query_arg('enviado', '1', $back) . '#formulario');
    exit;
}
// El formulario se envía a la propia página: el hosting pone una verificación de navegador delante de /wp-admin/
// (incluido admin-post.php) que recarga la página y perdería los datos del POST.
add_action('template_redirect', function () {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['tac_form'] ?? '') === 'contact') {
        tac_handle_contact();
    }
}, 0);
