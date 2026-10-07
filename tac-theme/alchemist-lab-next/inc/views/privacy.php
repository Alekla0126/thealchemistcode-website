<?php if (!defined('ABSPATH')) { exit; }
$owner = esc_html(TAC_OWNER);
$mail = esc_html(TAC_MAIL);
?>
<section class="tac-phero"><div class="tac-wrap" data-reveal>
  <div class="tac-label"><b>—</b> Legal</div>
  <h1><?php tac_e('Aviso de privacidad integral', 'Privacy notice'); ?></h1>
  <p class="tac-asof"><?php tac_e('Última actualización: 3 de octubre de 2026', 'Last updated: October 3, 2026'); ?></p>
</div></section>
<section><div class="tac-wrap"><div class="tac-legal">
<?php if (tac_lang() === 'en') : ?>
  <h2>Who is responsible for your data</h2>
  <p><?php echo $owner; ?>, a Mexican sole proprietor (persona física con actividad empresarial) doing business as <strong>The Alchemist Code</strong>, based in Puebla, Puebla, Mexico, is responsible for the processing of your personal data under Mexico's Federal Law on the Protection of Personal Data Held by Private Parties (LFPDPPP). Contact: <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>.</p>
  <h2>What data we collect</h2>
  <ul><li>Through the contact form: name, company, email, WhatsApp number (optional), what you need, approximate budget and the description of your project.</li><li>Through WhatsApp, email or video calls: the data you choose to share with us.</li><li>While browsing: only the technical cookies the site needs to work.</li></ul>
  <p>We do not ask for sensitive personal data. Please do not include it in your messages.</p>
  <h2>What we use it for</h2>
  <p><strong>Primary purposes</strong> (needed to serve you): replying to your request; preparing the discovery, proposal and quote; communicating with you about your project; and, if you hire us, fulfilling the contract and our tax obligations (invoicing).</p>
  <p><strong>Secondary purposes:</strong> none. We do not use your data for advertising or sell it to anyone.</p>
  <h2>Who we share it with</h2>
  <p>We do not transfer your personal data to third parties without your consent, except where the law requires it. Our hosting and email providers process data on our behalf as processors, solely to provide those services.</p>
  <h2>Your rights (ARCO)</h2>
  <p>You may access, rectify, cancel or oppose the processing of your personal data, and revoke your consent, by writing to <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>. Include your name, a way to reply to you, a copy of an ID and a description of your request. We will reply within the time limits set by law.</p>
  <h2>How long we keep it</h2>
  <p>For as long as needed to handle your request or the contracted project, and for the periods required by tax and commercial law.</p>
  <h2 id="cookies">Cookies</h2>
  <p>Today this site only uses technical cookies that it needs to work; it does not use analytics or advertising cookies. If we add analytics in the future, we will ask for your consent first and update this notice. You can delete cookies from your browser at any time.</p>
  <h2>Changes to this notice</h2>
  <p>Any change will be published on this page with its update date.</p>
<?php else : ?>
  <h2>Responsable de tus datos</h2>
  <p><?php echo $owner; ?>, persona física con actividad empresarial que opera con el nombre comercial <strong>The Alchemist Code</strong>, con domicilio en Puebla, Puebla, México, es responsable del tratamiento de tus datos personales conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares. Contacto: <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>.</p>
  <h2>Datos que recabamos</h2>
  <ul><li>En el formulario de contacto: nombre, empresa, correo electrónico, número de WhatsApp (opcional), lo que necesitas, presupuesto aproximado y la descripción de tu proyecto.</li><li>Por WhatsApp, correo o videollamada: los datos que decidas compartirnos.</li><li>Al navegar: solo las cookies técnicas que el sitio necesita para funcionar.</li></ul>
  <p>No solicitamos datos personales sensibles. Te pedimos no incluirlos en tus mensajes.</p>
  <h2>Para qué los usamos</h2>
  <p><strong>Finalidades primarias</strong> (necesarias para atenderte): responder tu solicitud; preparar el diagnóstico, la propuesta y la cotización; comunicarnos contigo sobre tu proyecto; y, si nos contratas, cumplir el contrato y nuestras obligaciones fiscales (facturación).</p>
  <p><strong>Finalidades secundarias:</strong> ninguna. No usamos tus datos para publicidad ni los vendemos.</p>
  <h2>Con quién los compartimos</h2>
  <p>No transferimos tus datos personales a terceros sin tu consentimiento, salvo en los casos que prevé la ley. Nuestros proveedores de alojamiento web y de correo electrónico los tratan por nuestra cuenta, como encargados, solo para prestar esos servicios.</p>
  <h2>Tus derechos ARCO</h2>
  <p>Puedes acceder a tus datos, rectificarlos, cancelarlos u oponerte a su tratamiento, así como revocar tu consentimiento, escribiendo a <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>. Incluye tu nombre, un medio para responderte, copia de una identificación y la descripción de lo que solicitas. Te responderemos en los plazos que establece la ley.</p>
  <h2>Cuánto tiempo los conservamos</h2>
  <p>El tiempo necesario para atender tu solicitud o el proyecto contratado, y los plazos que exigen las leyes fiscales y mercantiles.</p>
  <h2 id="cookies">Cookies</h2>
  <p>Hoy este sitio solo usa cookies técnicas, necesarias para que funcione; no usa cookies de analítica ni de publicidad. Si en el futuro añadimos analítica, primero te pediremos tu consentimiento y actualizaremos este aviso. Puedes borrar las cookies desde tu navegador en cualquier momento.</p>
  <h2>Cambios a este aviso</h2>
  <p>Cualquier cambio se publicará en esta página con su fecha de actualización.</p>
<?php endif; ?>
</div></div></section>
