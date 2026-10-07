// Uso: node qa/perf.js <url> [movil]  — peso, peticiones, LCP/CLS y recursos más pesados.
const { chromium } = require(process.env.HOME + '/node_modules/playwright-core');
(async () => {
  const [url, mob] = process.argv.slice(2);
  const b = await chromium.launch({ channel: 'chrome', headless: true });
  const ctx = await b.newContext(mob ? { viewport: { width: 390, height: 844 }, deviceScaleFactor: 3, isMobile: true, hasTouch: true } : { viewport: { width: 1440, height: 900 } });
  const p = await ctx.newPage();
  const cdp = await ctx.newCDPSession(p);
  await cdp.send('Network.enable');
  if (mob) await cdp.send('Network.emulateNetworkConditions', { offline: false, latency: 150, downloadThroughput: 1.6e6 / 8 * 10, uploadThroughput: 750e3 / 8 });
  const sizes = {};
  cdp.on('Network.responseReceived', e => { sizes[e.requestId] = { url: e.response.url, type: e.type }; });
  cdp.on('Network.loadingFinished', e => { if (sizes[e.requestId]) sizes[e.requestId].bytes = e.encodedDataLength; });
  await p.addInitScript(() => {
    window.__lcp = 0; window.__cls = 0;
    new PerformanceObserver(l => { for (const e of l.getEntries()) window.__lcp = e.startTime; }).observe({ type: 'largest-contentful-paint', buffered: true });
    new PerformanceObserver(l => { for (const e of l.getEntries()) if (!e.hadRecentInput) window.__cls += e.value; }).observe({ type: 'layout-shift', buffered: true });
  });
  const t0 = Date.now();
  await p.goto(url, { waitUntil: 'load', timeout: 90000 });
  const load = Date.now() - t0;
  await p.waitForTimeout(3000);
  const m = await p.evaluate(() => ({ lcp: Math.round(window.__lcp), cls: +window.__cls.toFixed(3), ttfb: Math.round(performance.getEntriesByType('navigation')[0].responseStart) }));
  const list = Object.values(sizes).filter(x => x.bytes);
  const total = list.reduce((a, x) => a + x.bytes, 0);
  const byType = {}; list.forEach(x => byType[x.type] = (byType[x.type] || 0) + x.bytes);
  console.log(JSON.stringify({ url, mobile: !!mob, load_ms: load, ...m, requests: list.length, total_kb: Math.round(total / 1024), by_type_kb: Object.fromEntries(Object.entries(byType).map(([k, v]) => [k, Math.round(v / 1024)])) }));
  list.sort((a, b2) => b2.bytes - a.bytes).slice(0, 8).forEach(x => console.log(String(Math.round(x.bytes / 1024)).padStart(5), 'KB', x.type.padEnd(10), x.url.replace(/^https?:\/\//, '').slice(0, 110)));
  await b.close();
})();
