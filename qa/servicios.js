// Uso: node qa/servicios.js <url> <nombre> [ancho] [alto] [tema: dark|light]
// Recorre la página para disparar las animaciones, guarda una captura completa y revisa el SEO en la página:
// h1, título, descripción, canonical, hreflang, JSON-LD, imágenes rotas y errores de consola.
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, name, w = '1440', h = '900', theme = 'dark'] = process.argv.slice(2);
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const mobile = +w < 700;
  const ctx = await browser.newContext({ viewport: { width: +w, height: +h }, deviceScaleFactor: mobile ? 2 : 1, locale: 'es-MX', isMobile: mobile, hasTouch: mobile });
  await ctx.addInitScript((t) => { try { localStorage.setItem('tac-theme', t); } catch (e) {} }, theme);
  const page = await ctx.newPage();
  const errors = [];
  page.on('pageerror', (e) => errors.push('pageerror: ' + e.message));
  page.on('console', (m) => { if (m.type() === 'error') errors.push('console: ' + m.text().slice(0, 160)); });
  page.on('requestfailed', (r) => { if (!/hcaptcha|google-analytics|googletagmanager/.test(r.url())) errors.push('failed: ' + r.url()); });
  page.on('response', (r) => { if (r.status() >= 400 && !/favicon/.test(r.url())) errors.push(r.status() + ': ' + r.url()); });
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await page.waitForTimeout(1200);
  const H = await page.evaluate(() => document.documentElement.scrollHeight);
  for (let y = 0; y < H; y += +h * 0.6) { await page.mouse.wheel(0, +h * 0.6); await page.waitForTimeout(160); }
  await page.waitForTimeout(1500);
  await page.evaluate(() => scrollTo(0, 0));
  await page.waitForTimeout(600);
  await page.screenshot({ path: `/tmp/srv-${name}.png`, fullPage: true });
  const seo = await page.evaluate(() => {
    const q = (s, a) => { const e = document.querySelector(s); return e ? (a ? e.getAttribute(a) : e.textContent.trim()) : null; };
    const ld = [...document.querySelectorAll('script[type="application/ld+json"]')].map((s) => {
      try { const j = JSON.parse(s.textContent); const g = j['@graph'] || [j]; return g.map((n) => n['@type']).join(','); } catch (e) { return 'INVÁLIDO: ' + e.message; }
    });
    return {
      title: document.title, h1: [...document.querySelectorAll('h1')].map((e) => e.textContent.trim()),
      desc: q('meta[name="description"]', 'content'), canonical: q('link[rel="canonical"]', 'href'),
      robots: q('meta[name="robots"]', 'content'), og: q('meta[property="og:image"]', 'content'),
      hreflang: [...document.querySelectorAll('link[rel="alternate"][hreflang]')].map((l) => l.hreflang + ' ' + l.href.replace(location.origin, '')),
      ld, broken: [...document.images].filter((i) => i.complete && !i.naturalWidth && i.getBoundingClientRect().width).map((i) => i.src),
      words: document.querySelector('main, .entry-content, body').innerText.split(/\s+/).length,
      overflowX: document.documentElement.scrollWidth > innerWidth + 1,
    };
  });
  console.log(JSON.stringify({ name, H, ...seo, errors: errors.slice(0, 10) }, null, 1));
  await browser.close();
})();
