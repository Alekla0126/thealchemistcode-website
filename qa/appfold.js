// Uso: node qa/appfold.js <url> <nombre> [ancho alto] — el plegable de la página de una app abriéndose solo.
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, name, w = '1440', h = '900'] = process.argv.slice(2);
  const mobile = +w < 700;
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const p = await (await b.newContext({ viewport: { width: +w, height: +h }, deviceScaleFactor: mobile ? 2 : 1, isMobile: mobile, hasTouch: mobile })).newPage();
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  await p.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 }); await p.waitForFunction(() => document.querySelector('[data-fold-auto]') && getComputedStyle(document.querySelector('[data-fold-auto]')).getPropertyValue('--f').trim() === '0.0000');
  if (mobile) { await p.waitForTimeout(400); await p.evaluate(() => document.querySelector('.tac-appfold').scrollIntoView({ block: 'center' })); }
  const fs = [];
  let last = 0;
  for (const t of [200, 1300, 1650, 2000, 3400]) {
    await p.waitForTimeout(t - last); last = t;
    await p.screenshot({ path: `screens/qa/tac/${name}-${t}.png` });
    fs.push(await p.evaluate(() => getComputedStyle(document.querySelector('[data-fold-auto]')).getPropertyValue('--f')));
  }
  await p.click('[data-fold-toggle]');
  await p.waitForTimeout(2300);
  await p.screenshot({ path: `screens/qa/tac/${name}-replegado.png` });
  fs.push(await p.evaluate(() => getComputedStyle(document.querySelector('[data-fold-auto]')).getPropertyValue('--f') + ' ' + document.querySelector('[data-fold-toggle]').textContent));
  console.log(JSON.stringify({ name, f: fs, errors }));
  await b.close();
})();
