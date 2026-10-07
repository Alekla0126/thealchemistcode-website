// Uso: node qa/tac.js <nombre> <url> [ancho] [alto] [tema: dark|light|default] [maxCapturas]
// Recorre la página con la rueda (dispara animaciones) y guarda capturas de pantalla sucesivas.
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [name, url, w = '1440', h = '900', theme = 'default', max = '14'] = process.argv.slice(2);
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const mobile = +w < 700;
  const ctx = await browser.newContext({ viewport: { width: +w, height: +h }, deviceScaleFactor: mobile ? 2 : 1, locale: 'es-MX', isMobile: mobile, hasTouch: mobile });
  if (theme !== 'default') await ctx.addInitScript((t) => { try { localStorage.setItem('tac-theme', t); } catch (e) {} }, theme);
  const page = await ctx.newPage();
  const errors = [];
  page.on('pageerror', (e) => errors.push('pageerror: ' + e.message));
  page.on('console', (m) => { if (m.type() === 'error') errors.push('console: ' + m.text().slice(0, 160)); });
  page.on('requestfailed', (r) => { if (!/hcaptcha|google-analytics|googletagmanager/.test(r.url())) errors.push('failed: ' + r.url()); });
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await page.waitForTimeout(1800);
  const info = await page.evaluate(() => ({ theme: document.documentElement.dataset.theme, H: document.documentElement.scrollHeight }));
  let i = 0, y = 0;
  while (i < +max) {
    await page.screenshot({ path: `screens/qa/tac/${name}-${String(i).padStart(2, '0')}.png` });
    i++;
    const before = await page.evaluate(() => scrollY);
    for (let k = 0; k < 6; k++) { await page.mouse.wheel(0, +h * 0.85 / 6); await page.waitForTimeout(70); }
    await page.waitForTimeout(900);
    const after = await page.evaluate(() => scrollY);
    if (after <= before + 2) break;
  }
  // imágenes borrosas: naturalWidth < ancho mostrado * DPR
  const blurry = await page.evaluate(() => [...document.images].filter(im => im.complete && im.naturalWidth && im.getBoundingClientRect().width > 0)
    .map(im => ({ src: (im.currentSrc || im.src).split('/').slice(-2).join('/'), nat: im.naturalWidth, shown: Math.round(im.getBoundingClientRect().width), ratio: +(im.naturalWidth / im.getBoundingClientRect().width).toFixed(2) }))
    .filter(x => x.ratio < devicePixelRatio * 0.98));
  console.log(JSON.stringify({ name, ...info, shots: i, errors: errors.slice(0, 12), blurry: blurry.slice(0, 12) }));
  await browser.close();
})();
