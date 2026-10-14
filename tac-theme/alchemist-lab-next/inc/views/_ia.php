<?php if (!defined('ABSPATH')) { exit; }
/*
 * Sección de inteligencia artificial: demostración con pestañas (documentos, fotos, asistente), capacidades y pruebas.
 * La demostración es ilustrativa y lo dice; las pruebas son reales (Springer, PDF Master, CalTracker AI, Instanotes AI).
 * $tac_ia_modo = 'demo' muestra solo la demostración (página de IA); por defecto, todo (home).
 */
$L = tac_lang() === 'en' ? 1 : 0;
$ia_modo = isset($tac_ia_modo) ? $tac_ia_modo : 'completo';
$ia_t = function ($es, $en) use ($L) { return $L ? $en : $es; };
$campos = array(
    array($ia_t('Proveedor', 'Vendor'), 'Distribuidora del Centro'),
    array('RFC', 'XAXX010101000'),
    array($ia_t('Fecha', 'Date'), '08/10/2026'),
    array($ia_t('Total', 'Total'), '$12,480.00 MXN'),
);
$capacidades = array(
    array('doc', $ia_t('Lectura de documentos', 'Document reading'), $ia_t('Facturas, contratos e identificaciones convertidos en datos para tu sistema.', 'Invoices, contracts and IDs turned into data for your system.')),
    array('spark', $ia_t('Visión por computadora', 'Computer vision'), $ia_t('Fotos de productos, inspecciones y evidencias que se clasifican solas.', 'Product photos, inspections and evidence classified automatically.')),
    array('chat', $ia_t('Asistentes con tus datos', 'Assistants on your data'), $ia_t('Respuestas y borradores con la información de tu empresa, revisados por una persona.', 'Answers and drafts from your company\'s data, reviewed by a person.')),
    array('stack', $ia_t('Automatización', 'Automation'), $ia_t('Flujos que hoy se copian a mano entre correo, hojas de cálculo y sistemas.', 'Workflows people copy by hand between email, spreadsheets and systems.')),
    array('shield', $ia_t('Fraude y riesgo', 'Fraud and risk'), $ia_t('Modelos que marcan phishing, transacciones atípicas o patrones de riesgo.', 'Models that flag phishing, unusual transactions or risky patterns.')),
    array('lock', $ia_t('IA en el dispositivo', 'On-device AI'), $ia_t('Cuando se puede, la IA corre en el teléfono y tus datos no salen.', 'When possible, AI runs on the phone and your data stays there.')),
);
$pruebas = array(
    array('Springer Nature · 2025', $ia_t('Capítulo sobre detección de phishing con IA', 'Chapter on AI phishing detection'), 'https://doi.org/10.1007/978-3-031-85363-0_21'),
    array('PDF Master', $ia_t('OCR en el teléfono, sin subir documentos', 'On-device OCR, no uploads'), tac_app_url('pdfmaster')),
    array('CalTracker AI', $ia_t('Una foto del platillo, calorías estimadas', 'One photo of a dish, estimated calories'), tac_app_url('caltracker')),
    array('Instanotes AI', $ia_t('Notas de voz a texto, resumen y traducción', 'Voice notes to text, summaries, translation'), tac_app_url('instanotes')),
);
?>
<div class="tac-ia" data-ia-demo data-reveal>
  <div class="tac-ia-tabs" role="tablist" aria-label="<?php echo esc_attr($ia_t('Ejemplos de IA', 'AI examples')); ?>">
    <button type="button" role="tab" aria-selected="true" data-tab="0"><?php echo esc_html($ia_t('Documentos', 'Documents')); ?></button>
    <button type="button" role="tab" aria-selected="false" data-tab="1"><?php echo esc_html($ia_t('Fotos', 'Photos')); ?></button>
    <button type="button" role="tab" aria-selected="false" data-tab="2"><?php echo esc_html($ia_t('Asistente', 'Assistant')); ?></button>
    <span class="tac-cams-tag"><?php echo esc_html($ia_t('Ejemplo', 'Example')); ?></span>
  </div>
  <div class="tac-ia-panes">
    <div class="pane on" role="tabpanel" data-pane="0">
      <div class="doc" aria-hidden="true">
        <b><?php echo esc_html($ia_t('FACTURA', 'INVOICE')); ?></b><i></i><i></i><i class="c"></i><i></i><i class="c"></i><i></i><span class="beam"></span>
      </div>
      <div class="flecha" aria-hidden="true">→</div>
      <div class="out">
        <div class="h"><?php echo esc_html($ia_t('Datos extraídos', 'Extracted data')); ?></div>
        <dl>
          <?php foreach ($campos as $i => $c) : ?><div style="--n:<?php echo (int) $i; ?>"><dt><?php echo esc_html($c[0]); ?></dt><dd><?php echo esc_html($c[1]); ?></dd></div><?php endforeach; ?>
        </dl>
        <p class="ok" style="--n:4"><?php echo esc_html($ia_t('Lista para tu sistema · 1 campo para revisión humana', 'Ready for your system · 1 field for human review')); ?></p>
      </div>
    </div>
    <div class="pane" role="tabpanel" data-pane="1" hidden>
      <div class="foto" aria-hidden="true"><span class="plato"></span><span class="box b1"><em><?php echo esc_html($ia_t('Arroz', 'Rice')); ?></em></span><span class="box b2"><em><?php echo esc_html($ia_t('Pollo', 'Chicken')); ?></em></span><span class="box b3"><em><?php echo esc_html($ia_t('Ensalada', 'Salad')); ?></em></span></div>
      <div class="flecha" aria-hidden="true">→</div>
      <div class="out">
        <div class="h"><?php echo esc_html($ia_t('Estimación', 'Estimate')); ?></div>
        <dl>
          <div style="--n:0"><dt><?php echo esc_html($ia_t('Arroz', 'Rice')); ?></dt><dd>180 kcal</dd></div>
          <div style="--n:1"><dt><?php echo esc_html($ia_t('Pollo', 'Chicken')); ?></dt><dd>240 kcal</dd></div>
          <div style="--n:2"><dt><?php echo esc_html($ia_t('Ensalada', 'Salad')); ?></dt><dd>60 kcal</dd></div>
          <div style="--n:3"><dt><?php echo esc_html($ia_t('Total', 'Total')); ?></dt><dd>480 kcal</dd></div>
        </dl>
        <p class="ok" style="--n:4"><?php echo esc_html($ia_t('Así funciona CalTracker AI, nuestra app de nutrición', 'This is how CalTracker AI, our nutrition app, works')); ?></p>
      </div>
    </div>
    <div class="pane" role="tabpanel" data-pane="2" hidden>
      <div class="chat">
        <p class="q" style="--n:0"><?php echo esc_html($ia_t('¿Cuántas piezas del SKU 1042 quedan en la sucursal Centro?', 'How many units of SKU 1042 are left at the downtown branch?')); ?></p>
        <p class="a" style="--n:1"><?php echo esc_html($ia_t('Quedan 38 piezas. Al ritmo de ventas de esta semana alcanzan para 6 días; conviene reabastecer el jueves.', '38 units left. At this week\'s sales pace they last 6 days; restocking on Thursday makes sense.')); ?><small><?php echo esc_html($ia_t('Fuente: tu sistema de inventario', 'Source: your inventory system')); ?></small></p>
        <p class="q" style="--n:2"><?php echo esc_html($ia_t('Prepara el pedido al proveedor.', 'Draft the order to the supplier.')); ?></p>
        <p class="a" style="--n:3"><?php echo esc_html($ia_t('Listo el borrador por 120 piezas. Queda pendiente de tu aprobación antes de enviarlo.', 'Draft ready for 120 units. It waits for your approval before it is sent.')); ?></p>
      </div>
    </div>
  </div>
</div>
<?php if ($ia_modo !== 'demo') : ?>
<div class="tac-near tac-ia-caps">
  <?php foreach ($capacidades as $i => $c) : ?>
    <div class="tac-near-i" data-reveal style="--d:<?php echo (int) ($i * 70); ?>ms">
      <span class="ic"><?php echo tac_svg($c[0]); // phpcs:ignore ?></span>
      <h3><?php echo esc_html($c[1]); ?></h3>
      <p><?php echo esc_html($c[2]); ?></p>
    </div>
  <?php endforeach; ?>
</div>
<div class="tac-ia-pruebas" data-reveal>
  <?php foreach ($pruebas as $p) :
      $ext = $p[2] && strpos($p[2], home_url()) !== 0; ?>
    <a href="<?php echo esc_url($p[2] ?: tac_url('apps')); ?>"<?php echo $ext ? ' target="_blank" rel="noopener"' : ''; ?>><b><?php echo esc_html($p[0]); ?></b><span><?php echo esc_html($p[1]); ?></span></a>
  <?php endforeach; ?>
</div>
<?php endif; ?>
