<?php if (!defined('ABSPATH')) { exit; }
$owner = esc_html(TAC_OWNER);
$mail = esc_html(TAC_MAIL);
?>
<section class="tac-phero"><div class="tac-wrap" data-reveal>
  <div class="tac-label"><b>—</b> Legal</div>
  <h1><?php tac_e('Términos de uso', 'Terms of use'); ?></h1>
  <p class="tac-asof"><?php tac_e('Última actualización: 3 de octubre de 2026', 'Last updated: October 3, 2026'); ?></p>
</div></section>
<section><div class="tac-wrap"><div class="tac-legal">
<?php if (tac_lang() === 'en') : ?>
  <h2>Who we are</h2>
  <p>The Alchemist Code is the trade name of <?php echo $owner; ?>, a Mexican sole proprietor based in Puebla, Puebla, Mexico. Contact: <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>.</p>
  <h2>Information on this site</h2>
  <p>The content of this site is general information about our services and products. It is not a binding offer: the scope, timeline, price and conditions of each project are set out in writing in the proposal and the contract we sign with each client.</p>
  <h2>Intellectual property</h2>
  <p>The texts, design and brand of this site belong to The Alchemist Code. The apps shown are our own products. App Store, Google Play and other third-party names and logos belong to their respective owners.</p>
  <h2>Links to third parties</h2>
  <p>We link to app stores and other sites we do not control. We are not responsible for their content or practices.</p>
  <h2>Privacy</h2>
  <p>How we handle personal data is described in our <a href="<?php echo esc_url(tac_url('privacy')); ?>">privacy notice</a>.</p>
  <h2>Governing law</h2>
  <p>These terms are governed by the laws of Mexico. Any dispute will be submitted to the courts of Puebla, Puebla, Mexico.</p>
<?php else : ?>
  <h2>Quiénes somos</h2>
  <p>The Alchemist Code es el nombre comercial de <?php echo $owner; ?>, persona física con actividad empresarial con domicilio en Puebla, Puebla, México. Contacto: <a href="mailto:<?php echo $mail; ?>"><?php echo $mail; ?></a>.</p>
  <h2>Información del sitio</h2>
  <p>El contenido de este sitio es información general sobre nuestros servicios y productos. No constituye una oferta vinculante: el alcance, los plazos, el precio y las condiciones de cada proyecto se establecen por escrito en la propuesta y en el contrato que firmamos con cada cliente.</p>
  <h2>Propiedad intelectual</h2>
  <p>Los textos, el diseño y la marca de este sitio pertenecen a The Alchemist Code. Las apps que se muestran son productos propios. App Store, Google Play y otros nombres y logotipos de terceros pertenecen a sus respectivos titulares.</p>
  <h2>Enlaces a terceros</h2>
  <p>Enlazamos a tiendas de apps y a otros sitios que no controlamos. No somos responsables de su contenido ni de sus prácticas.</p>
  <h2>Privacidad</h2>
  <p>El tratamiento de datos personales se describe en nuestro <a href="<?php echo esc_url(tac_url('privacy')); ?>">aviso de privacidad</a>.</p>
  <h2>Ley aplicable</h2>
  <p>Estos términos se rigen por las leyes de los Estados Unidos Mexicanos. Cualquier controversia se someterá a los tribunales competentes de Puebla, Puebla, México.</p>
<?php endif; ?>
</div></div></section>
