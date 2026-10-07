// Uso: node qa/cases.js <url> <nombre> [ancho alto] — captura cada caso de "Resultados que puedes comprobar".
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, name, w = '1440', h = '900'] = process.argv.slice(2);
  const mobile = +w < 700;
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const p = await (await b.newContext({ viewport: { width: +w, height: +h }, deviceScaleFactor: mobile ? 2 : 1, isMobile: mobile, hasTouch: mobile })).newPage();
  await p.goto(url, { waitUntil: 'networkidle', timeout: 90000 });
  await p.waitForTimeout(2000);
  const n = await p.locator('.tac-cases .tac-prod').count();
  for (let i = 0; i < n; i++) {
    const el = p.locator('.tac-cases .tac-prod').nth(i);
    await el.scrollIntoViewIfNeeded(); await p.waitForTimeout(1300);
    await el.screenshot({ path: `screens/qa/tac/${name}-case${i}.png` });
  }
  const hs = await p.evaluate(() => [...document.querySelectorAll('.tac-cases .tac-prod')].map(e => Math.round(e.getBoundingClientRect().height)));
  console.log(JSON.stringify({ name, n, heights: hs }));
  await b.close();
})();
