<?php if (!defined('ABSPATH')) { exit; }
/*
 * Infraestructura y redes: topología animada, lo que operamos hoy y seis capacidades.
 * Datos de nuestra propia infraestructura (App Monitor y plan de servidores de Komodo, 3 y 7-oct-2026).
 * Sin instalación física (cableado, Wi-Fi, conmutadores): el dueño no la ha confirmado como servicio.
 */
$L = tac_lang() === 'en' ? 1 : 0;
// nodos de la topología: x, y, etiqueta, detalle
$nodos = array(
    array(96, 92, array('Sucursales', 'Branches'), array('red privada', 'private network')),
    array(96, 318, array('Trabajo remoto', 'Remote staff'), array('VPN cifrada', 'encrypted VPN')),
    array(464, 92, array('Nube', 'Cloud'), array('Google Cloud · Firebase', 'Google Cloud · Firebase')),
    array(464, 318, array('Servidores', 'Servers'), array('Linux · Docker', 'Linux · Docker')),
    array(280, 52, array('Apps', 'Apps'), array('iOS · Android · web', 'iOS · Android · web')),
    array(280, 372, array('Monitoreo', 'Monitoring'), array('alertas automáticas', 'automatic alerts')),
);
$operamos = array(
    array('vpn-ee', array('Nodo VPN · Estonia', 'VPN node · Estonia'), 'OpenVPN · TLS 1.3 · AES-256-GCM', 'Komodo VPN'),
    array('vpn-cy', array('Nodo VPN · Chipre', 'VPN node · Cyprus'), 'OpenVPN · TLS 1.3 · AES-256-GCM', 'Komodo VPN'),
    array('api', array('API en Cloud Run', 'API on Cloud Run'), array('Escala a cero · pagos con Stripe', 'Scales to zero · Stripe payments'), 'PDF Master'),
    array('firebase', array('Backends en Firebase', 'Firebase backends'), array('Firestore · notificaciones en tiempo real', 'Firestore · real-time notifications'), 'Soccer24'),
    array('monitor', array('Monitor de nuestras apps', 'Monitor for our apps'), array('Tiendas · APIs · servidores · facturación', 'Stores · APIs · servers · billing'), tac_t('Propio', 'In-house')),
);
$capacidades = array(
    array('cloud', 'Servidores y nube', 'Servers and cloud',
        'Servidores Linux, Google Cloud (Cloud Run y Cloud Functions) y Firebase. Elegimos por carga y por costo: la API de PDF Master escala a cero cuando nadie la usa.',
        'Linux servers, Google Cloud (Cloud Run and Cloud Functions) and Firebase. We choose by load and cost: the PDF Master API scales to zero when nobody is using it.'),
    array('net', 'Redes privadas y VPN', 'Private networks and VPN',
        'Conectamos sucursales, bodegas y personal remoto con una VPN cifrada, con accesos por usuario que se dan de alta o se revocan en minutos.',
        'We connect branches, warehouses and remote staff over an encrypted VPN, with per-user access you can grant or revoke in minutes.'),
    array('lock', 'Seguridad', 'Security',
        'HTTPS automático, cifrado en tránsito, firewall, accesos mínimos y llaves fuera del código. Las llaves de IA viven en el servidor, nunca dentro de la app.',
        'Automatic HTTPS, encryption in transit, firewalls, least-privilege access and secrets kept out of the code. AI keys live on the server, never inside the app.'),
    array('pulse', 'Monitoreo y alertas', 'Monitoring and alerts',
        'Revisamos servidores, APIs, tiendas y facturación de la nube de forma automática. Si algo falla, lo sabemos antes que tus clientes.',
        'We check servers, APIs, app stores and cloud billing automatically. If something breaks, we know before your customers do.'),
    array('stack', 'Despliegue automático', 'Automated deployment',
        'Docker y GitHub Actions: cada versión se construye, se prueba y se publica igual, sin pasos a mano ni "en mi máquina sí funcionaba".',
        'Docker and GitHub Actions: every release is built, tested and shipped the same way, with no manual steps and no "it worked on my machine".'),
    array('chart', 'Costos bajo control', 'Costs under control',
        'Modelamos cuánto cuesta cada usuario antes de crecer. Comparamos proveedores y movemos cargas a donde cuestan menos, sin bajar la calidad.',
        'We model the cost per user before you scale, compare providers and move workloads to where they cost less, without lowering quality.'),
);
?>
<div class="tac-infra" data-reveal>
  <div class="tac-topo" aria-hidden="true">
    <svg viewBox="0 0 560 420" preserveAspectRatio="xMidYMid meet">
      <defs>
        <radialGradient id="tac-hub" cx="50%" cy="40%" r="60%"><stop offset="0" stop-color="#16B5E8"/><stop offset="1" stop-color="#0A5FB4"/></radialGradient>
      </defs>
      <circle class="ring" cx="280" cy="206" r="120"/><circle class="ring r2" cx="280" cy="206" r="176"/>
      <?php foreach ($nodos as $i => $n) :
          $d = sprintf('M280 206 Q%d %d %d %d', (int) ((280 + $n[0]) / 2 + ($n[1] < 206 ? -18 : 18)), (int) ((206 + $n[1]) / 2), $n[0], $n[1]); ?>
        <path id="tac-l<?php echo (int) $i; ?>" class="ln<?php echo $i === 1 || $i === 0 ? ' vpn' : ''; ?>" d="<?php echo esc_attr($d); ?>"/>
        <circle class="pk" r="3.2"><animateMotion dur="<?php echo esc_attr(2.4 + $i * 0.35); ?>s" begin="<?php echo esc_attr($i * 0.4); ?>s" repeatCount="indefinite" keyPoints="<?php echo $i % 2 ? '0;1' : '1;0'; ?>" keyTimes="0;1" calcMode="linear"><mpath href="#tac-l<?php echo (int) $i; ?>"/></animateMotion></circle>
      <?php endforeach; ?>
      <?php foreach ($nodos as $n) : ?>
        <g class="nd" transform="translate(<?php echo (int) $n[0]; ?> <?php echo (int) $n[1]; ?>)">
          <circle r="9"/><circle class="halo" r="9"/>
          <text y="<?php echo $n[1] < 206 ? -33 : 30; ?>" text-anchor="middle"><?php echo esc_html($n[2][$L]); ?></text>
          <text class="sub" y="<?php echo $n[1] < 206 ? -19 : 44; ?>" text-anchor="middle"><?php echo esc_html($n[3][$L]); ?></text>
        </g>
      <?php endforeach; ?>
      <circle class="hub-p" cx="280" cy="206" r="40"/>
      <circle cx="280" cy="206" r="36" fill="url(#tac-hub)"/>
      <text class="hub" x="280" y="202" text-anchor="middle"><?php tac_e('Tu', 'Your'); ?></text>
      <text class="hub" x="280" y="217" text-anchor="middle"><?php tac_e('empresa', 'company'); ?></text>
    </svg>
  </div>
  <div class="tac-ops">
    <div class="tac-ops-h"><span class="dot"></span><?php tac_e('Lo que operamos hoy', 'What we run today'); ?></div>
    <ul>
      <?php foreach ($operamos as $o) : ?>
        <li>
          <code><?php echo esc_html($o[0]); ?></code>
          <div><b><?php echo esc_html($o[1][$L]); ?></b><span><?php echo esc_html(is_array($o[2]) ? $o[2][$L] : $o[2]); ?></span></div>
          <em><?php echo esc_html($o[3]); ?></em>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="tac-asof"><?php tac_e('Infraestructura propia en producción; nodos revisados con un handshake TLS real el 3 de octubre de 2026.', 'Our own infrastructure in production; nodes checked with a real TLS handshake on October 3, 2026.'); ?></div>
  </div>
</div>
<div class="tac-near tac-infra-caps">
  <?php foreach ($capacidades as $i => $c) : ?>
    <div class="tac-near-i" data-reveal style="--d:<?php echo (int) ($i * 70); ?>ms">
      <span class="ic"><?php echo tac_svg($c[0]); // phpcs:ignore ?></span>
      <h3><?php echo esc_html($L ? $c[2] : $c[1]); ?></h3>
      <p><?php echo esc_html($L ? $c[4] : $c[3]); ?></p>
    </div>
  <?php endforeach; ?>
</div>
