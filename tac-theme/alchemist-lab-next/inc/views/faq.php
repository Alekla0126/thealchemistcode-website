<?php if (!defined('ABSPATH')) { exit; }
$qs = array(
  array('¿Cuánto cuesta una app?', 'How much does an app cost?', 'Depende del alcance. El diagnóstico y la propuesta no tienen costo: recibes alcance, plazos y precio por escrito antes de empezar, sin compromiso.', 'It depends on scope. Discovery and proposal are free: you get scope, timeline and price in writing before we start, with no commitment.'),
  array('¿Cuánto tarda?', 'How long does it take?', 'Los plazos van por escrito en la propuesta, divididos en entregas que puedes instalar en tu teléfono para ver el avance.', 'Timelines are written into the proposal, split into builds you can install on your phone to see progress.'),
  array('¿Facturan?', 'Do you issue invoices?', 'Sí. Facturamos en México (CFDI), también cuando el pago es en dólares.', 'Yes. We issue Mexican invoices (CFDI), also when you pay in US dollars.'),
  array('¿El código es mío?', 'Do I own the code?', 'Sí. Al terminar te entregamos el código, los repositorios, la documentación y los accesos.', 'Yes. On completion you get the code, repositories, documentation and access.'),
  array('¿Firman un acuerdo de confidencialidad (NDA)?', 'Do you sign an NDA?', 'Sí, antes de que nos cuentes los detalles del proyecto, si lo necesitas.', 'Yes, before you share project details, if you need it.'),
  array('¿Quién hace el trabajo?', 'Who does the work?', 'Somos un estudio dirigido por su fundador: él dirige cada proyecto de principio a fin y se suman colaboradores especializados cuando hacen falta. Hablas siempre con quien diseña y construye.', 'We are a founder-led studio: the founder leads every project end to end, with specialists when the work calls for them. You always talk to the person who designs and builds.'),
  array('¿Trabajan en remoto?', 'Do you work remotely?', 'Sí. Por videollamada (Google Meet), WhatsApp y correo, en horario de México (UTC−6), muy cerca del horario central de EE. UU.', 'Yes. Over video calls (Google Meet), WhatsApp and email, on Mexico time (UTC−6), very close to US Central.'),
  array('¿Qué pasa después del lanzamiento?', 'What happens after launch?', 'Ofrecemos soporte mensual con tiempos por escrito: falla crítica, respuesta en 4 horas hábiles; falla normal, en 1 día hábil. Además, 60 días de garantía de defectos sin costo.', 'We offer monthly support with written response times: critical issues within 4 business hours, normal issues within 1 business day, plus a 60-day defect warranty at no cost.'),
  array('¿Pueden integrar la app con mi ERP o sistema actual?', 'Can you integrate with my ERP or current system?', 'Empezamos con una prueba de concepto acotada para validar la integración antes del proyecto completo.', 'We start with a scoped proof of concept to validate the integration before the full project.'),
  array('¿Qué documentos entregan a un área de compras?', 'What paperwork can you provide to procurement?', 'Constancia de situación fiscal, opinión de cumplimiento del SAT (32-D) positiva, contrato modelo y NDA, a solicitud.', 'Mexican tax status certificate, positive SAT compliance opinion (32-D), model contract and NDA, on request.'),
  array('¿Publican la app en las tiendas?', 'Do you publish the app in the stores?', 'Sí. Nos encargamos de la publicación en App Store y Google Play, incluida la revisión de Apple, y de la ficha de la tienda.', "Yes. We handle the App Store and Google Play release, including Apple's review, and the store listing."),
  array('¿Atienden fuera de Puebla?', 'Do you work outside Puebla?', 'Sí. Trabajamos en remoto con clientes de todo México y de EE. UU., por videollamada, WhatsApp y correo.', 'Yes. We work remotely with clients across Mexico and the US, over video calls, WhatsApp and email.'),
  array('¿Trabajan con startups de EE. UU. y cobran en dólares?', 'Do you work with US startups and bill in USD?', 'Sí. Firmamos contrato y NDA, aceptamos pagos en USD y trabajamos en horario UTC−6, muy cerca del horario central de EE. UU.', 'Yes. We sign a contract and NDA, accept USD payments and work on UTC−6, very close to US Central time.'),
  array('¿Flutter o nativo?', 'Flutter or native?', 'Usamos Flutter cuando conviene un solo código para iOS y Android, y Swift o Kotlin nativo cuando la app lo necesita, por ejemplo para widgets o funciones del sistema.', 'We use Flutter when one codebase for iOS and Android makes sense, and native Swift or Kotlin when the app needs it, for example for widgets or system features.'),
  array('¿Pueden añadir inteligencia artificial a mi app actual?', 'Can you add AI to my existing app?', 'Sí. Primero revisamos tu app y te proponemos dónde la IA ahorra trabajo de verdad, con los costos bajo control. El diagnóstico no tiene costo.', 'Yes. We first review your app and propose where AI genuinely saves work, with costs under control. Discovery is free.'),
  array('¿Qué incluye el diagnóstico sin costo?', 'What does the free discovery include?', 'Una llamada para entender el problema, a los usuarios y tus sistemas, y después una propuesta escrita con alcance, plazos y precio.', 'A call to understand the problem, the users and your systems, followed by a written proposal with scope, timeline and price.'),
  array('¿Pueden adaptar Inventra a mi negocio?', 'Can you adapt Inventra to my business?', 'Sí. Inventra puede ser la base de tu sistema de inventario y ventas: lo adaptamos a tus procesos en lugar de empezar de cero.', 'Yes. Inventra can be the starting point for your inventory and sales system: we adapt it to your processes instead of starting from scratch.'),
  array('¿Con qué tecnologías trabajan?', 'Which technologies do you use?', 'Flutter, Swift, Kotlin, Firebase, FastAPI, Laravel y Electron; para IA, Claude, OpenAI y Gemini.', 'Flutter, Swift, Kotlin, Firebase, FastAPI, Laravel and Electron; for AI, Claude, OpenAI and Gemini.'),
);
?>
<section class="tac-phero"><div class="tac-wrap" data-reveal>
  <div class="tac-label"><b>—</b> <?php tac_e('Preguntas frecuentes', 'FAQ'); ?></div>
  <h1><?php tac_e('Preguntas frecuentes sobre desarrollo de apps', 'App development FAQ'); ?></h1>
</div></section>
<section style="padding:0 0 96px"><div class="tac-wrap"><div class="tac-faq">
  <?php foreach ($qs as $q) : ?>
    <details data-reveal><summary><?php echo esc_html(tac_t($q[0], $q[1])); ?></summary><p><?php echo esc_html(tac_t($q[2], $q[3])); ?></p></details>
  <?php endforeach; ?>
</div></div></section>
<?php include __DIR__ . '/_cta.php'; ?>
<script type="application/ld+json"><?php
echo wp_json_encode(array(
  '@context' => 'https://schema.org', '@type' => 'FAQPage',
  'mainEntity' => array_map(function ($q) {
      return array('@type' => 'Question', 'name' => tac_t($q[0], $q[1]), 'acceptedAnswer' => array('@type' => 'Answer', 'text' => tac_t($q[2], $q[3])));
  }, $qs),
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
