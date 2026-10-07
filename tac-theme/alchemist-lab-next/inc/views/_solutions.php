<?php if (!defined('ABSPATH')) { exit; }
$bars = array(38, 52, 46, 64, 72, 92); ?>
<div class="tac-bento">
  <div class="tac-card span2" data-reveal>
    <div class="tac-viz">
      <div class="viz-tag"><span><i></i><?php tac_e('Sincronizado', 'Synced'); ?></span><span><?php tac_e('3 sucursales', '3 branches'); ?></span></div>
      <div class="viz-bars"><?php foreach ($bars as $n => $h) : ?><i style="--h:<?php echo (int) $h; ?>%;--n:<?php echo (int) $n; ?>"></i><?php endforeach; ?></div>
    </div>
    <div class="k">A</div><h3><?php tac_e('Ventas, inventario y operación', 'Sales, inventory and operations'); ?></h3>
    <p><?php tac_e('Apps para tu fuerza de ventas y tu operación que funcionan sin conexión. Si hay que conectarlas a tu ERP o a tu sistema actual, empezamos con una prueba de concepto acotada.', 'Apps for your sales force and operations that work offline. If they need to talk to your ERP or current system, we start with a scoped proof of concept.'); ?></p>
    <ul><li><?php tac_e('Pedidos en campo', 'Field orders'); ?></li><li><?php tac_e('Inventario entre sucursales', 'Multi-branch inventory'); ?></li><li><?php tac_e('Lector de códigos', 'Barcode scanning'); ?></li><li><?php tac_e('Reportes', 'Reports'); ?></li></ul>
    <a class="tac-card-more" href="<?php echo esc_url(tac_url('local')); ?>"><?php tac_e('Software a la medida en Puebla', 'Custom software in Mexico'); ?> <span aria-hidden="true">→</span></a>
    <div class="ex"><img src="<?php echo esc_url(tac_icon('inventra')); ?>" alt="" width="32" height="32"><span><?php tac_e('Hecho por nosotros:', 'Built by us:'); ?> <b>Inventra</b></span></div>
  </div>
  <div class="tac-card" data-reveal style="--d:120ms">
    <div class="tac-viz">
      <div class="viz-notes">
        <div class="viz-note" style="--n:0"><img class="ap" src="<?php echo esc_url(tac_icon('soccer24')); ?>" alt=""><div><b><?php tac_e('¡Gol! 1-0 al minuto 60', 'Goal! 1-0 at minute 60'); ?></b><span>Soccer24</span></div></div>
        <div class="viz-note" style="--n:1"><img class="ap" src="<?php echo esc_url(tac_icon('clutch')); ?>" alt=""><div><b><?php tac_e('Final cerrado: 101-104', 'Close finish: 101-104'); ?></b><span>Clutch</span></div></div>
        <div class="viz-note" style="--n:2"><img class="ap" src="<?php echo esc_url(tac_icon('waterday')); ?>" alt=""><div><b><?php tac_e('Hoy toca regar 3 plantas', '3 plants need water today'); ?></b><span>Waterday</span></div></div>
      </div>
    </div>
    <div class="k">B</div><h3><?php tac_e('Apps para tus clientes', 'Customer-facing apps'); ?></h3>
    <p><?php tac_e('Lealtad, reservas, contenido y notificaciones segmentadas, con el lanzamiento en tiendas y el crecimiento incluidos.', 'Loyalty, bookings, content and segmented notifications, with store launch and growth included.'); ?></p>
    <a class="tac-card-more" href="<?php echo esc_url(tac_url('movil')); ?>"><?php tac_e('Desarrollo de apps móviles', 'Mobile app development'); ?> <span aria-hidden="true">→</span></a>
    <div class="ex"><img src="<?php echo esc_url(tac_icon('soccer24')); ?>" alt="" width="32" height="32"><span><?php tac_e('Hecho por nosotros:', 'Built by us:'); ?> <b>Soccer24</b></span></div>
  </div>
  <div class="tac-card" data-reveal>
    <div class="tac-viz">
      <div class="viz-doc"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><span class="viz-scan"></span></div>
      <div class="viz-out"><span style="--n:0"><?php tac_e('Texto extraído', 'Text extracted'); ?></span><span style="--n:1"><?php tac_e('Firma', 'Signature'); ?></span><span style="--n:2"><?php tac_e('PDF listo', 'PDF ready'); ?></span></div>
    </div>
    <div class="k">C</div><h3><?php tac_e('Inteligencia artificial aplicada', 'Applied AI'); ?></h3>
    <p><?php tac_e('IA donde ahorra trabajo de verdad, con los costos bajo control y sin enviar datos que no hace falta.', "AI where it actually saves work, with costs under control and no data sent that doesn't need to be."); ?></p>
    <a class="tac-card-more" href="<?php echo esc_url(tac_url('ia')); ?>"><?php tac_e('Inteligencia artificial para empresas', 'AI development'); ?> <span aria-hidden="true">→</span></a>
    <div class="ex"><img src="<?php echo esc_url(tac_icon('pdfmaster')); ?>" alt="" width="32" height="32"><span><?php tac_e('Hecho por nosotros:', 'Built by us:'); ?> <b>PDF Master</b>, <b>CalTracker AI</b></span></div>
  </div>
  <div class="tac-card span2" data-reveal style="--d:120ms">
    <div class="tac-viz">
      <svg class="viz-net" viewBox="0 0 640 210" preserveAspectRatio="xMidYMid meet" aria-hidden="true">
        <defs><linearGradient id="tacg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0A5FB4"/><stop offset="1" stop-color="#16B5E8"/></linearGradient></defs>
        <path class="ln" d="M320 105 C240 105 200 50 120 50"/><path class="ln" d="M320 105 C240 105 200 160 120 160"/>
        <path class="ln" d="M320 105 C400 105 440 50 520 50"/><path class="ln" d="M320 105 C400 105 440 160 520 160"/>
        <circle class="pulse" cx="320" cy="105" r="34"/>
        <circle class="core" cx="320" cy="105" r="30"/>
        <text x="320" y="109" text-anchor="middle" style="fill:#fff">API</text>
        <circle class="nd" cx="120" cy="50" r="16"/><text x="120" y="86" text-anchor="middle">iOS</text>
        <circle class="nd" cx="120" cy="160" r="16"/><text x="120" y="196" text-anchor="middle">Android</text>
        <circle class="nd" cx="520" cy="50" r="16"/><text x="520" y="86" text-anchor="middle">VPN</text>
        <circle class="nd" cx="520" cy="160" r="16"/><text x="520" y="196" text-anchor="middle"><?php tac_e('Escritorio', 'Desktop'); ?></text>
      </svg>
    </div>
    <div class="k">D</div><h3><?php tac_e('Infraestructura, redes y seguridad', 'Infrastructure, networks and security'); ?></h3>
    <p><?php tac_e('Servidores y nube, VPN para sucursales y personal remoto, monitoreo, backend y apps de escritorio para macOS, Windows y Linux.', 'Servers and cloud, VPNs for branches and remote staff, monitoring, backend and desktop apps for macOS, Windows and Linux.'); ?></p>
    <ul><li><?php tac_e('Servidores y nube', 'Servers and cloud'); ?></li><li><?php tac_e('APIs y notificaciones', 'APIs and notifications'); ?></li><li><?php tac_e('VPN y redes privadas', 'VPN and private networks'); ?></li><li><?php tac_e('Apps de escritorio', 'Desktop apps'); ?></li></ul>
    <a class="tac-card-more" href="<?php echo esc_url(tac_url('infra')); ?>"><?php tac_e('Infraestructura y redes', 'Infrastructure and networks'); ?> <span aria-hidden="true">→</span></a>
    <div class="ex"><img src="<?php echo esc_url(tac_icon('komodo')); ?>" alt="" width="32" height="32"><span><?php tac_e('Hecho por nosotros:', 'Built by us:'); ?> <b>Komodo VPN</b>, <b>Alchemist Coder</b></span></div>
  </div>
</div>
