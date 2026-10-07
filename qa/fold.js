// Uso: node qa/fold.js <url> <nombre> [ancho alto] — captura el plegable en varios puntos del scroll.
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, name, w = '1440', h = '900'] = process.argv.slice(2);
  const mobile = +w < 700;
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const p = await (await b.newContext({ viewport: { width: +w, height: +h }, deviceScaleFactor: mobile ? 2 : 1, isMobile: mobile, hasTouch: mobile })).newPage();
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  await p.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await p.waitForTimeout(2500);
  const top = await p.evaluate(() => { const s = document.querySelector('[data-fold]'); return s ? s.getBoundingClientRect().top + scrollY : -1; });
  const span = await p.evaluate(() => { const s = document.querySelector('[data-fold]'); return s.offsetHeight - innerHeight; });
  const out = [];
  for (const q of [0, 0.3, 0.45, 0.6, 1]) {
    await p.evaluate(y => window.scrollTo(0, y), Math.round(top + span * q));
    await p.waitForTimeout(900);
    const f = await p.evaluate(() => getComputedStyle(document.querySelector('[data-fold]')).getPropertyValue('--f'));
    await p.screenshot({ path: `screens/qa/tac/${name}-${Math.round(q * 100)}.png` });
    out.push(`${q}:${f}`);
  }
  console.log(JSON.stringify({ name, top, span, f: out, errors }));
  await b.close();
})();
