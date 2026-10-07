/* thealchemistcode.org · animaciones y modo oscuro.
   Todo respeta "reducir movimiento" y, en pantallas táctiles, se omiten los efectos de cursor. */
(function () {
  var d = document, w = window, root = d.documentElement;
  var reduce = w.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = w.matchMedia('(pointer: fine)').matches;
  var clamp = function (v, a, b) { return Math.max(a, Math.min(b, v)); };

  /* ---------- tema claro / oscuro ---------- */
  var themeMeta = d.querySelector('meta[name="theme-color"]');
  function applyTheme(t) {
    root.setAttribute('data-theme', t);
    if (themeMeta) themeMeta.setAttribute('content', t === 'dark' ? '#060D1A' : '#F5F7FB');
  }
  applyTheme(root.getAttribute('data-theme') || 'dark');
  d.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      try { localStorage.setItem('tac-theme', next); } catch (e) {}
      btn.setAttribute('aria-pressed', next === 'dark');
      if (!d.startViewTransition || reduce) { applyTheme(next); return; }
      var r = btn.getBoundingClientRect(), x = r.left + r.width / 2, y = r.top + r.height / 2;
      var end = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));
      root.classList.add('tac-vt-theme');
      var vt = d.startViewTransition(function () { applyTheme(next); });
      vt.ready.then(function () {
        root.animate({ clipPath: ['circle(0px at ' + x + 'px ' + y + 'px)', 'circle(' + end + 'px at ' + x + 'px ' + y + 'px)'] },
          { duration: 650, easing: 'cubic-bezier(.2,.7,.2,1)', pseudoElement: '::view-transition-new(root)' });
      });
      vt.finished.then(function () { root.classList.remove('tac-vt-theme'); });
    });
  });

  /* ---------- intro de marca: se quita del DOM al terminar ---------- */
  var intro = d.querySelector('div.tac-intro');
  if (intro) {
    if (root.classList.contains('tac-intro-on')) {
      setTimeout(function () { intro.remove(); }, 2400);
      // Las esperas extra del hero solo sirven mientras se ve la intro.
      setTimeout(function () { root.classList.remove('tac-intro-on'); }, 3200);
    } else {
      intro.remove();
    }
  }

  /* ---------- scroll suave (Lenis) ---------- */
  var lenis = null;
  if (!reduce && fine && w.Lenis) {
    lenis = new w.Lenis({ lerp: 0.1, wheelMultiplier: 1, anchors: { offset: -90 } });
    (function raf(t) { lenis.raf(t); requestAnimationFrame(raf); })(performance.now());
  }

  /* ---------- títulos: dividir en palabras ---------- */
  d.querySelectorAll('.tac h2, .tac-phero h1').forEach(function (h) {
    if (h.closest('.tac-hero') || h.querySelector('*')) return;
    var words = h.textContent.trim().split(/\s+/);
    h.innerHTML = words.map(function (wd, i) { return '<span class="tw"><span style="--i:' + i + '">' + wd.replace(/[&<>]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;' }[c]; }) + '</span></span>'; }).join(' ');
    h.setAttribute('data-split', '');
    h.setAttribute('aria-label', words.join(' '));
  });

  /* ---------- rótulo del pie: letra por letra ---------- */
  var fw = d.querySelector('.tac-fword');
  if (fw && !fw.querySelector('.fl')) {
    var txt = fw.textContent;
    fw.innerHTML = Array.prototype.map.call(txt, function (c, i) { return c === ' ' ? '<span class="sp"></span>' : '<span class="fl" style="--i:' + i + '">' + c + '</span>'; }).join('');
  }

  /* ---------- aparición al hacer scroll ---------- */
  var watch = d.querySelectorAll('[data-reveal], [data-split], .tac-fword');
  if (reduce || !('IntersectionObserver' in w)) {
    watch.forEach(function (el) { el.classList.add('in'); });
  } else {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
    watch.forEach(function (el) { io.observe(el); });
  }

  /* ---------- contadores ---------- */
  var fmt = new Intl.NumberFormat('en-US');
  function count(el) {
    var to = +el.getAttribute('data-count'), suf = el.getAttribute('data-suffix') || '';
    if (reduce) { el.textContent = fmt.format(to) + suf; return; }
    var t0 = performance.now(), dur = 1600;
    (function step(t) {
      var p = Math.min(1, (t - t0) / dur), v = Math.round(to * (1 - Math.pow(1 - p, 4)));
      el.textContent = fmt.format(v) + suf;
      if (p < 1) requestAnimationFrame(step);
    })(t0);
  }
  if ('IntersectionObserver' in w) {
    var co = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { co.unobserve(e.target); count(e.target); } });
    }, { threshold: 0.6 });
    d.querySelectorAll('[data-count]').forEach(function (el) { co.observe(el); });
  }

  /* ---------- manifiesto: palabras que se encienden con el scroll ---------- */
  var mf = d.querySelector('[data-manifest]');
  var mwords = mf ? mf.querySelectorAll('.mw') : [];

  /* ---------- productos en scroll horizontal ---------- */
  var hs = d.querySelector('[data-hs]'), hsTrack, hsOn = false, hsN = 0;
  function hsSetup() {
    if (!hs) return;
    var want = !reduce && innerWidth >= 1100;
    if (want === hsOn) return;
    hsOn = want;
    root.classList.toggle('tac-hs-on', hsOn);
    hsTrack = hs.querySelector('.tac-hs-track');
    hsN = hsTrack.children.length;
    hs.style.setProperty('--n', hsN);
    hsTrack.querySelectorAll('[data-reveal]').forEach(function (el) { el.classList.add('in'); });
    if (!hsOn) hsTrack.style.removeProperty('--x');
  }
  hsSetup();
  w.addEventListener('resize', hsSetup);

  /* ---------- relojes de la barra superior (Puebla y EE. UU.) ---------- */
  var clocks = d.querySelectorAll('time[data-tz]');
  if (clocks.length && w.Intl) {
    var tick = function () {
      clocks.forEach(function (c) {
        try { c.textContent = new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: c.getAttribute('data-tz') }).format(new Date()); } catch (e) {}
      });
    };
    tick(); setInterval(tick, 20000);
  }

  /* ---------- plegable: se abre con el scroll ---------- */
  var fold = d.querySelector('[data-fold]'), foldSteps = fold ? fold.querySelectorAll('[data-step]') : [];
  function foldSet(f) {
    fold.style.setProperty('--f', f.toFixed(4));
    // sombra máxima cuando la mitad izquierda pasa por la vertical
    fold.style.setProperty('--sh2', (Math.sin((1 - f) * Math.PI) * 0.9).toFixed(3));
    var s = f < 0.12 ? 0 : (f < 0.9 ? 1 : 2);
    foldSteps.forEach(function (li, i) { li.classList.toggle('on', i === s); });
  }
  if (fold && reduce) foldSet(1);

  /* ---------- plegable en la página de una app: se abre solo al aparecer ---------- */
  d.querySelectorAll('[data-fold-auto]').forEach(function (box) {
    var btn = box.querySelector('[data-fold-toggle]'), cur = reduce ? 1 : 0, raf = 0;
    function set(f) {
      cur = f;
      box.style.setProperty('--f', f.toFixed(4));
      box.style.setProperty('--sh2', (Math.sin((1 - f) * Math.PI) * 0.9).toFixed(3));
      if (btn) btn.textContent = btn.getAttribute(f > 0.5 ? 'data-open' : 'data-closed');
    }
    function go(to, delay) {
      cancelAnimationFrame(raf);
      if (reduce) { set(to); return; }
      var from = cur, t0 = 0, dur = 1700;
      setTimeout(function () {
        (function step(t) {
          if (!t0) t0 = t;
          var p = Math.min(1, (t - t0) / dur), e = p < 0.5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2;
          set(from + (to - from) * e);
          if (p < 1) raf = requestAnimationFrame(step);
        })(performance.now());
      }, delay || 0);
    }
    set(cur);
    if (!reduce && 'IntersectionObserver' in w) {
      var io2 = new IntersectionObserver(function (es) {
        if (es[0].isIntersecting) { io2.disconnect(); go(1, 650); }
      }, { threshold: 0.45 });
      io2.observe(box);
    } else { set(1); }
    var toggle = function () { go(cur > 0.5 ? 0 : 1); };
    if (btn) btn.addEventListener('click', toggle);
    var dev = box.querySelector('.tac-fold');
    if (dev) dev.addEventListener('click', toggle);
  });

  /* ---------- bucle de scroll ---------- */
  var h = d.querySelector('.tac-h'), bar = d.querySelector('.tac-progress'), hero = d.querySelector('.tac-hero');
  var steps = d.querySelector('.tac-steps');
  var tracks = d.querySelectorAll('.tac-track'), lastY = w.scrollY, vel = 0, ticking = false;
  function onScroll() {
    var y = w.scrollY, vh = innerHeight;
    if (h) h.classList.toggle('scrolled', y > 8);
    var max = root.scrollHeight - vh;
    if (bar) bar.style.setProperty('--p', max > 0 ? (y / max).toFixed(4) : 0);
    if (hero && !reduce) hero.style.setProperty('--sp', clamp(y / (hero.offsetHeight || 1), 0, 1).toFixed(3));
    if (mf && !reduce) {
      var r = mf.getBoundingClientRect();
      var p = clamp((vh * 0.85 - r.top) / (r.height + vh * 0.35), 0, 1);
      var n = Math.round(p * mwords.length);
      mwords.forEach(function (el, i) { el.classList.toggle('on', i < n); });
    } else if (mf) { mwords.forEach(function (el) { el.classList.add('on'); }); }
    if (hsOn && hsTrack) {
      var hr = hs.getBoundingClientRect(), total = hs.offsetHeight - (vh - 100);
      var hp = clamp(-hr.top / (total || 1), 0, 1);
      var dist = hsTrack.scrollWidth - innerWidth;
      hsTrack.style.setProperty('--x', (-dist * hp).toFixed(1) + 'px');
      hs.style.setProperty('--hp', hp.toFixed(3));
      var cur = hs.querySelector('.tac-hs-ui .cur');
      if (cur) cur.textContent = String(Math.min(hsN, Math.floor(hp * hsN * 0.999) + 1)).padStart(2, '0');
    }
    if (fold && !reduce) {
      var fr = fold.getBoundingClientRect(), span = fold.offsetHeight - vh;
      var fp = clamp((-fr.top) / (span || 1), 0, 1);
      var ft = clamp((fp - 0.14) / 0.62, 0, 1);
      foldSet(ft * ft * (3 - 2 * ft));
    }
    vel = vel * 0.8 + Math.abs(y - lastY) * 0.2; lastY = y;
    ticking = false;
  }
  function req() { if (!ticking) { ticking = true; requestAnimationFrame(onScroll); } }
  w.addEventListener('scroll', req, { passive: true });
  w.addEventListener('resize', req);
  onScroll();

  /* la cinta de apps acelera con la velocidad del scroll */
  if (!reduce && tracks.length && tracks[0].getAnimations) {
    (function spin() {
      var rate = 1 + Math.min(vel * 0.12, 5);
      tracks.forEach(function (t) { t.getAnimations().forEach(function (a) { a.playbackRate += (rate - a.playbackRate) * 0.08; }); });
      vel *= 0.94;
      requestAnimationFrame(spin);
    })();
  }

  if (reduce || !fine) return;

  /* ---------- efectos de cursor (solo con ratón) ---------- */
  var stage = d.querySelector('[data-parallax]'), rafM = 0, mx = 0, my = 0, cx = 0, cy = 0;
  w.addEventListener('mousemove', function (e) {
    mx = e.clientX / innerWidth - 0.5; my = e.clientY / innerHeight - 0.5; cx = e.clientX; cy = e.clientY;
    if (!rafM) rafM = requestAnimationFrame(function () {
      if (stage) { stage.style.setProperty('--mx', mx.toFixed(3)); stage.style.setProperty('--my', my.toFixed(3)); }
      if (hero) { var hr = hero.getBoundingClientRect(); hero.style.setProperty('--hx', (cx - hr.left) + 'px'); hero.style.setProperty('--hy', (cy - hr.top) + 'px'); }
      rafM = 0;
    });
  }, { passive: true });

  /* tarjetas con inclinación y foco */
  d.querySelectorAll('.tac-card, .tac-principle, .tac-prod').forEach(function (el) {
    var tilt = !el.classList.contains('tac-prod');
    el.addEventListener('pointermove', function (e) {
      var r = el.getBoundingClientRect(), px = (e.clientX - r.left) / r.width, py = (e.clientY - r.top) / r.height;
      el.style.setProperty('--px', (px * 100).toFixed(1) + '%');
      el.style.setProperty('--py', (py * 100).toFixed(1) + '%');
      if (tilt) { el.style.setProperty('--ry', ((px - 0.5) * 5).toFixed(2) + 'deg'); el.style.setProperty('--rx', ((0.5 - py) * 5).toFixed(2) + 'deg'); }
    });
    el.addEventListener('pointerleave', function () { el.style.setProperty('--rx', '0deg'); el.style.setProperty('--ry', '0deg'); });
  });

  /* cursor propio con etiqueta según lo que hay debajo */
  var cur = d.querySelector('.tac-cursor');
  if (cur) {
    var ring = cur.querySelector('b'), dot = cur.querySelector('i'), lab = ring.querySelector('span');
    var en = (root.lang || '').indexOf('en') === 0;
    var labels = [
      ['.tac-prod', en ? 'View' : 'Ver'],
      ['.tac-app-pill', en ? 'Open' : 'Abrir'],
      ['.tac-appcard, .tac-relcard', en ? 'View app' : 'Ver app'],
      ['.tac-gallery', en ? 'Drag' : 'Desliza'],
      ['.tac-blog .wp-block-post', en ? 'Read' : 'Leer'],
      ['.tac-wa-float', 'Chat']
    ];
    var tx = -100, ty = -100, rx2 = -100, ry2 = -100, shown = false;
    root.classList.add('tac-cur');
    w.addEventListener('mousemove', function (e) {
      tx = e.clientX; ty = e.clientY;
      if (!shown) { rx2 = tx; ry2 = ty; shown = true; root.classList.remove('tac-cur-off'); }
    }, { passive: true });
    d.addEventListener('mouseleave', function () { root.classList.add('tac-cur-off'); shown = false; });
    d.addEventListener('mousedown', function () { cur.classList.add('down'); });
    d.addEventListener('mouseup', function () { cur.classList.remove('down'); });
    d.addEventListener('mouseover', function (e) {
      var t = e.target, txt = '';
      for (var n = 0; n < labels.length; n++) { if (t.closest(labels[n][0])) { txt = labels[n][1]; break; } }
      cur.classList.toggle('label', !!txt);
      cur.classList.toggle('link', !txt && !!t.closest('a, button, summary, label, input, textarea, select'));
      if (txt) lab.textContent = txt;
    });
    (function follow() {
      rx2 += (tx - rx2) * 0.18; ry2 += (ty - ry2) * 0.18;
      dot.style.transform = 'translate3d(' + tx + 'px,' + ty + 'px,0)';
      ring.style.transform = 'translate3d(' + rx2.toFixed(1) + 'px,' + ry2.toFixed(1) + 'px,0)';
      requestAnimationFrame(follow);
    })();
  }

  /* botones magnéticos */
  d.querySelectorAll('.tac-btn-primary, .tac-theme').forEach(function (b) {
    b.addEventListener('pointermove', function (e) {
      var r = b.getBoundingClientRect();
      b.style.setProperty('--mgx', ((e.clientX - r.left - r.width / 2) * 0.22).toFixed(1) + 'px');
      b.style.setProperty('--mgy', ((e.clientY - r.top - r.height / 2) * 0.3).toFixed(1) + 'px');
    });
    b.addEventListener('pointerleave', function () { b.style.setProperty('--mgx', '0px'); b.style.setProperty('--mgy', '0px'); });
  });
})();
