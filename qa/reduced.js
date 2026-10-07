// Uso: node qa/reduced.js <url> <nombre> — con "reducir movimiento": sin intro, sin cursor y todo visible sin animar.
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, name] = process.argv.slice(2);
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const ctx = await b.newContext({ viewport: { width: 1440, height: 900 }, reducedMotion: 'reduce', locale: 'es-MX' });
  const p = await ctx.newPage();
  const errors = [];
  p.on('pageerror', e => errors.push(e.message));
  await p.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await p.waitForTimeout(400);
  await p.screenshot({ path: `screens/qa/tac/${name}-top.png` });
  const r = await p.evaluate(() => {
    const hidden = [...document.querySelectorAll('[data-reveal], [data-split] .tw > span, .tac-hero h1 .w, .tac-rise')]
      .filter(el => { const cs = getComputedStyle(el); return +cs.opacity < 0.99 || (cs.transform !== 'none' && !el.closest('.tac-stage')); })
      .map(el => el.className + ' ' + (el.textContent || '').trim().slice(0, 30));
    return {
      cls: document.documentElement.className,
      intro: !!document.querySelector('div.tac-intro') && getComputedStyle(document.querySelector('div.tac-intro')).display !== 'none',
      cursor: getComputedStyle(document.querySelector('.tac-cursor') || document.body).display,
      lenis: document.documentElement.classList.contains('lenis'),
      hiddenCount: hidden.length, hidden: hidden.slice(0, 8),
    };
  });
  // al fondo, sin esperar animaciones
  await p.evaluate(() => window.scrollTo(0, document.documentElement.scrollHeight / 2));
  await p.waitForTimeout(300);
  await p.screenshot({ path: `screens/qa/tac/${name}-mid.png` });
  console.log(JSON.stringify({ name, ...r, errors }));
  await b.close();
})();
