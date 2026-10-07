// Uso: node qa/page.js <url> <nombre> [ancho] [alto] [tema] [fullPage]
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, name, w = '1440', h = '900', theme = 'dark', full = '0'] = process.argv.slice(2);
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const ctx = await browser.newContext({ viewport: { width: +w, height: +h }, locale: 'es-MX', isMobile: +w < 700, hasTouch: +w < 700 });
  await ctx.addInitScript((t) => { try { localStorage.setItem('alekla_scheme', t); } catch (e) {} }, theme);
  const page = await ctx.newPage();
  const errors = [];
  page.on('pageerror', (e) => errors.push('pageerror: ' + e.message));
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await page.waitForTimeout(2500);
  await page.screenshot({ path: `screens/qa/${name}.png`, fullPage: full === '1' });
  console.log(JSON.stringify({ errors, h: await page.evaluate(() => document.documentElement.scrollHeight) }));
  await browser.close();
})();
