// Uso: node qa/vista.js <url> <desplazamiento px> <salida.png> [ancho] [alto] [dark|light]
// Captura de la ventana tras bajar N píxeles con la rueda (dispara las animaciones de aparición).
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, y, out, w = '1440', h = '900', theme = 'dark'] = process.argv.slice(2);
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const mobile = +w < 700;
  const ctx = await b.newContext({ viewport: { width: +w, height: +h }, deviceScaleFactor: mobile ? 2 : 1, isMobile: mobile, hasTouch: mobile });
  await ctx.addInitScript((t) => { try { localStorage.setItem('tac-theme', t); } catch (e) {} }, theme);
  const p = await ctx.newPage();
  await p.goto(url, { waitUntil: 'load', timeout: 60000 });
  await p.waitForTimeout(800);
  for (let s = 0; s < +y; s += 300) { await p.mouse.wheel(0, 300); await p.waitForTimeout(90); }
  await p.waitForTimeout(2600);
  await p.screenshot({ path: out });
  await b.close();
})();
