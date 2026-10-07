// Captura la intro de marca en varios instantes y el cursor sobre un botón.
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const p = await (await b.newContext({ viewport: { width: 1440, height: 900 }, locale: 'es-MX' })).newPage();
  const t0 = Date.now();
  await p.goto(process.argv[2], { waitUntil: 'domcontentloaded', timeout: 60000 });
  for (const t of [400, 1000, 1800, 3400]) {
    const wait = t - (Date.now() - t0);
    if (wait > 0) await p.waitForTimeout(wait);
    await p.screenshot({ path: `screens/qa/tac/intro-${t}.png` });
  }
  await p.mouse.move(600, 400);
  await p.mouse.move(640, 420, { steps: 5 });
  const box = (await p.locator('.tac-hero .tac-btn-primary').first().boundingBox()) || { x: 140, y: 620, width: 200, height: 50 };
  await p.mouse.move(box.x + box.width / 2, box.y + box.height / 2, { steps: 10 });
  await p.waitForTimeout(600);
  await p.screenshot({ path: 'screens/qa/tac/cursor-link.png', clip: { x: Math.max(0, box.x - 80), y: box.y - 60, width: 520, height: 180 } });
  console.log(JSON.stringify(await p.evaluate(() => ({ cls: document.documentElement.className, intro: !!document.querySelector('.tac-intro'), cur: getComputedStyle(document.querySelector('.tac-cursor')).display }))));
  await b.close();
})();
