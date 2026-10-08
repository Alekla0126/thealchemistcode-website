#!/usr/bin/env python3
"""Reporte de tráfico de thealchemistcode.org a partir de los registros del servidor (sin Google Analytics).

  python3 qa/trafico.py <logs...> [--desde 2026-10-01] > reporte.md

Lee registros en formato "combined" (planos o .gz). Separa personas de robots, quita nuestras propias
pruebas (Playwright, curl) y responde: ¿cuántas personas entran?, ¿de dónde llegan (Google, Bing,
ChatGPT…)?, ¿qué páginas ven?, ¿llegan al contacto? y ¿ya rastrean Google y Bing las páginas nuevas?
"""
import argparse
import collections
import datetime as dt
import gzip
import re
import sys
from urllib.parse import urlsplit

LINEA = re.compile(r'^(\S+) \S+ \S+ \[([^\]]+)\] "(\S+) (\S+) [^"]*" (\d{3}) \S+ "([^"]*)" "([^"]*)"')
ESTATICO = re.compile(r'^/(wp-content|wp-includes|wp-json|wp-admin|wp-login|xmlrpc|feed|comments/feed)|\.(css|js|png|jpe?g|webp|avif|gif|svg|ico|woff2?|xml|txt|map|json|php)(\?|$)', re.I)
ROBOT = re.compile(r'bot|crawl|spider|slurp|fetch|scan|monitor|preview|facebookexternalhit|GoogleOther|Google-|HeadlessChrome|python|curl|wget|go-http|axios|node|okhttp|java/|libwww|httpclient|ahrefs|semrush|mj12|petal|bytespider|gptbot|claude|perplexity|ccbot|applebot|yandex|baidu|dataprovider|zgrab|masscan|nmap', re.I)
BUSCADORES = {
    'google.': 'Google', 'bing.com': 'Bing', 'duckduckgo.com': 'DuckDuckGo', 'search.yahoo': 'Yahoo',
    'ecosia.org': 'Ecosia', 'search.brave.com': 'Brave', 'yandex.': 'Yandex',
    'chatgpt.com': 'ChatGPT', 'chat.openai.com': 'ChatGPT', 'perplexity.ai': 'Perplexity',
    'claude.ai': 'Claude', 'copilot.microsoft.com': 'Copilot', 'gemini.google.com': 'Gemini',
}
RASTREADORES = {
    'Googlebot': 'Googlebot', 'bingbot': 'Bingbot', 'GPTBot': 'GPTBot (OpenAI)', 'OAI-SearchBot': 'OAI-SearchBot (ChatGPT)',
    'ChatGPT-User': 'ChatGPT-User', 'ClaudeBot': 'ClaudeBot', 'PerplexityBot': 'PerplexityBot',
    'Applebot': 'Applebot', 'DuckDuckBot': 'DuckDuckBot', 'YandexBot': 'YandexBot',
}
PROPIO = 'thealchemistcode.org'


def abrir(ruta):
    return gzip.open(ruta, 'rt', errors='replace') if ruta.endswith('.gz') else open(ruta, errors='replace')


def fuente(ref):
    if not ref or ref == '-':
        return None
    host = urlsplit(ref).netloc.lower()
    if not host or host.endswith(PROPIO):
        return None
    for k, v in BUSCADORES.items():
        if k in host:
            return v
    return host.removeprefix('www.')


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('logs', nargs='+')
    ap.add_argument('--desde', default=None)
    a = ap.parse_args()
    desde = dt.date.fromisoformat(a.desde) if a.desde else None

    visitas = collections.defaultdict(set)            # día → {(ip, ua)}
    paginas = collections.Counter()
    fuentes = collections.Counter()
    aterrizajes = collections.defaultdict(collections.Counter)  # buscador → página
    contacto = collections.Counter()
    rastreo = collections.defaultdict(collections.Counter)      # rastreador → día
    rastreadas = collections.defaultdict(set)                   # rastreador → páginas
    vistos = set()
    primero = ultimo = None

    for ruta in a.logs:
        for linea in abrir(ruta):
            m = LINEA.match(linea)
            if not m:
                continue
            ip, fecha, metodo, path, status, ref, ua = m.groups()
            dia = dt.datetime.strptime(fecha.split()[0], '%d/%b/%Y:%H:%M:%S').date()
            if desde and dia < desde:
                continue
            clave = (ip, fecha, path)
            if clave in vistos:   # el mismo registro en el archivo del día y en el mensual
                continue
            vistos.add(clave)
            primero = min(primero or dia, dia)
            ultimo = max(ultimo or dia, dia)
            limpio = path.split('?')[0]
            if 'action=tac_evt' in path and not ROBOT.search(ua):
                q = dict(x.split('=', 1) for x in path.split('?', 1)[1].split('&') if '=' in x)
                contacto['clic a ' + q.get('t', '?')] += 1
                continue
            for k, nombre in RASTREADORES.items():
                if k in ua:
                    rastreo[nombre][dia] += 1
                    if not ESTATICO.search(limpio) or limpio == '/llms.txt':
                        rastreadas[nombre].add(limpio)
                    break
            if ROBOT.search(ua) or status not in ('200', '304'):
                if metodo == 'POST' and limpio.rstrip('/').endswith(('contact', 'contacto')) and not ROBOT.search(ua):
                    contacto['envíos del formulario'] += 1
                continue
            if metodo != 'GET' or ESTATICO.search(limpio):
                continue
            visitas[dia].add((ip, ua))
            paginas[limpio] += 1
            f = fuente(ref)
            if f:
                fuentes[f] += 1
                if f in BUSCADORES.values():
                    aterrizajes[f][limpio] += 1
            if limpio.rstrip('/').endswith(('contact', 'contacto')):
                contacto['visitas a contacto'] += 1

    if not primero:
        print('Sin datos.')
        return
    total = sum(len(v) for v in visitas.values())
    print(f'# Tráfico de thealchemistcode.org · {primero} a {ultimo}\n')
    print(f'- **Visitantes (personas, únicos por día):** {total} en {(ultimo - primero).days + 1} días')
    print(f'- **Páginas vistas por personas:** {sum(paginas.values())}')
    clics = ', '.join(f'{k}: {v}' for k, v in sorted(contacto.items()) if k.startswith('clic')) or 'sin datos todavía'
    print(f'- **Contacto:** {contacto["visitas a contacto"]} visitas a la página, {contacto["envíos del formulario"]} envíos del formulario')
    print(f'- **Clics para escribir (desde el 8-oct-2026):** {clics}\n')
    print('## Visitantes por semana\n')
    sem = collections.Counter()
    for d, v in visitas.items():
        sem[d - dt.timedelta(days=d.weekday())] += len(v)
    for s in sorted(sem):
        print(f'- Semana del {s}: {sem[s]}')
    print('\n## De dónde llegan (sin contar entradas directas)\n')
    for f, n in fuentes.most_common(15):
        print(f'- {f}: {n}')
    if aterrizajes:
        print('\n## Páginas a las que llegan desde buscadores y asistentes de IA\n')
        for b, c in aterrizajes.items():
            print(f'**{b}:** ' + ', '.join(f'{p} ({n})' for p, n in c.most_common(8)))
    print('\n## Páginas más vistas por personas\n')
    for p, n in paginas.most_common(20):
        print(f'- {p}: {n}')
    print('\n## Rastreadores (¿ya leen las páginas nuevas?)\n')
    nuevas = ('desarrollo-de-aplicaciones-moviles', 'inteligencia-artificial-para-empresas', 'infraestructura-y-redes',
              'desarrollo-flutter-mexico', 'desarrollo-de-software-puebla', 'software-development-company-mexico',
              'mobile-app-development', 'ai-development', 'flutter-development', 'cloud-and-network', 'llms.txt',
              'como-elegir', 'flutter-o-nativo', 'ia-para-empresas', 'vpn-para-empresas', 'nearshore', 'como-publicar',
              'flutter-vs-native', 'ai-for-business')
    for r, dias in sorted(rastreo.items(), key=lambda x: -sum(x[1].values())):
        n_nuevas = sum(1 for p in rastreadas[r] if any(k in p for k in nuevas))
        print(f'- **{r}:** {sum(dias.values())} peticiones, {len(rastreadas[r])} páginas distintas, {n_nuevas} de las nuevas (servicios, guías, llms.txt)')


if __name__ == '__main__':
    main()
