// Uso: node qa/shoot.js <url> <nombre> [ancho] [alto] [tema] [dpr]
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, name, w = '1440', h = '900', theme = 'dark', dpr = '1'] = process.argv.slice(2);
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const ctx = await browser.newContext({
    viewport: { width: +w, height: +h }, deviceScaleFactor: +dpr, locale: 'es-MX',
    isMobile: +w < 700, hasTouch: +w < 700,
  });
  await ctx.addInitScript((t) => { try { localStorage.setItem('alekla_scheme', t); } catch (e) {} }, theme);
  const page = await ctx.newPage();
  const errors = [];
  page.on('pageerror', (e) => errors.push('pageerror: ' + e.message));
  page.on('console', (m) => { if (m.type() === 'error') errors.push('console: ' + m.text()); });
  page.on('requestfailed', (r) => errors.push('failed: ' + r.url()));
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await page.waitForTimeout(4200);
  const out = `screens/qa/${name}`;
  await page.screenshot({ path: `${out}-0-hero.png` });
  // recorrer la página para disparar los revelados
  const total = await page.evaluate(() => document.documentElement.scrollHeight);
  for (let y = 0; y < total; y += 500) {
    await page.mouse.wheel(0, 500);
    await page.waitForTimeout(160);
  }
  await page.waitForTimeout(1500);
  const ids = ['about', 'services', 'apps', 'experience', 'works', 'pricing', 'testimonials', 'blog', 'contact'];
  let i = 1;
  for (const id of ids) {
    const ok = await page.evaluate((id) => {
      const el = document.getElementById(id); if (!el) return false;
      window.scrollTo(0, el.getBoundingClientRect().top + window.scrollY - 40); return true;
    }, id);
    if (!ok) continue;
    await page.waitForTimeout(1400);
    await page.screenshot({ path: `${out}-${i++}-${id}.png` });
  }
  await page.evaluate(() => window.scrollTo(0, document.documentElement.scrollHeight));
  await page.waitForTimeout(1600);
  await page.screenshot({ path: `${out}-${i++}-footer.png` });
  console.log(JSON.stringify({ errors: errors.slice(0, 20), height: total }, null, 1));
  await browser.close();
})();
