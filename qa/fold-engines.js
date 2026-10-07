// Uso: node qa/fold-engines.js <url> — el plegable del home en Chrome y en WebKit (Safari) en varios puntos.
const pw = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const url = process.argv[2];
  for (const [name, launch] of [['chrome', () => pw.chromium.launch({ channel: 'chrome', headless: true })], ['webkit', () => pw.webkit.launch({ headless: true })]]) {
    const b = await launch();
    const p = await (await b.newContext({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 })).newPage();
    const errs = []; p.on('pageerror', e => errs.push(e.message));
    await p.goto(url, { waitUntil: 'networkidle', timeout: 90000 });
    await p.waitForTimeout(2500);
    const sec = await p.evaluate(() => { const s = document.querySelector('[data-fold]'); return { top: s.getBoundingClientRect().top + scrollY, span: s.offsetHeight - innerHeight }; });
    const out = [];
    for (const q of [0, 0.3, 0.42, 0.52, 0.62, 1]) {
      await p.evaluate(y => window.scrollTo(0, y), Math.round(sec.top + sec.span * q));
      await p.waitForTimeout(700);
      out.push(await p.evaluate(() => getComputedStyle(document.querySelector('[data-fold]')).getPropertyValue('--f').trim()));
      const box = await p.locator('.tac-fold-stage').boundingBox();
      await p.screenshot({ path: `screens/qa/tac/eng-${name}-${Math.round(q * 100)}.png`, clip: { x: Math.max(0, box.x - 20), y: Math.max(0, box.y - 20), width: Math.min(1440 - box.x + 20, box.width + 40), height: box.height + 40 } });
    }
    console.log(name, JSON.stringify(out), errs.slice(0, 3));
    await b.close();
  }
})();
