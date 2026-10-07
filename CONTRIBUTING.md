# Contributing to thealchemistcode.org

Thanks for helping! Issues and pull requests are welcome in English or Spanish.

## Before you start

- For anything bigger than a small fix, open an issue first so we can agree on the approach.
- This is the source of a live business website. Design and code improvements are very welcome; changes to what the site says about the studio (services, figures, clients) are decided by the studio.

## Ground rules

- **No invented facts.** Every number on the site carries its source and date (Play Console, a public store listing, Crossref…). Don't add figures, clients, awards or testimonials without a verifiable source.
- **Both languages.** User-facing text goes through `tac_t('español', 'English')` / `tac_e()`, or the `[es, en]` pairs in `inc/data/*.json`.
- **Motion is optional.** Every animation must stop under `prefers-reduced-motion: reduce`, and cursor effects must be skipped on touch screens.
- **Test the foldable in WebKit.** Safari renders 3D faces differently from Chrome: run `node qa/fold-engines.js <url>` when you touch `.tac-fold`.
- **Keep secrets out.** Server details go in `.env.deploy` (git-ignored). Never commit hosts, users, keys or tokens.
- **Match the surrounding code.** Comments in Spanish, small partials in `inc/views/_*.php`, escaping with `esc_html` / `esc_attr` / `esc_url`.

## Checks before a pull request

```bash
find tac-theme -name '*.php' -exec php -l {} \; | grep -v 'No syntax errors'
node qa/servicios.js <url> <name> 1440 900 dark    # and 390 844 for mobile
node qa/servicios.js <url> <name> 1440 900 light
```

The page should show one `<h1>`, valid JSON-LD, no console errors and no horizontal overflow.

## Pull requests

- Small, focused PRs that explain *why*.
- Before/after screenshots for visual changes, on desktop and mobile.
- Contributions are accepted under the project license (GPL-2.0-or-later).

## Code of conduct

This project follows the [Contributor Covenant](CODE_OF_CONDUCT.md).

---

**En español:** abre un issue antes de cambios grandes. No agregues cifras sin fuente. Escribe los textos en español e inglés y respeta "reducir movimiento". Prueba el plegable también en WebKit. Nunca subas datos del servidor; van en `.env.deploy`.
