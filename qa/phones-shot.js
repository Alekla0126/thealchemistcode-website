const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const p = await (await b.newContext({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 2, reducedMotion: 'reduce' })).newPage();
  await p.goto(process.argv[2] || 'https://thealchemistcode.org/?ph', { waitUntil: 'networkidle' });
  await p.waitForTimeout(800);
  const st = await p.locator('.tac-stage').boundingBox();
  await p.screenshot({ path: 'screens/qa/tac/ph2-hero.png', clip: { x: st.x - 40, y: st.y - 20, width: st.width + 80, height: st.height + 40 } });
  const info = await p.evaluate(() => [...document.querySelectorAll('.tac-phone')].slice(0, 9).map(ph => { const i = ph.querySelector('img'); const r = i.getBoundingClientRect(); return { src: (i.currentSrc||'').split('/').pop(), ratio: +(r.width / r.height).toFixed(3) }; }));
  console.log(JSON.stringify(info));
  const prod = p.locator('.tac-prod').first(); await prod.scrollIntoViewIfNeeded(); await p.waitForTimeout(500);
  const pb = await prod.boundingBox();
  await p.screenshot({ path: 'screens/qa/tac/ph2-prod.png', clip: { x: pb.x, y: pb.y, width: Math.min(pb.width, 640), height: pb.height } });
  await b.close();
})();
