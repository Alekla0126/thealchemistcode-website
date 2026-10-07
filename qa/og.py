#!/usr/bin/env python3
"""Imágenes para compartir en redes (1200×630) de thealchemistcode.org.

Una por app y por idioma (og/<clave>-<es|en>.jpg) y dos del estudio
(og/estudio-<es|en>.jpg), con el estilo oscuro del sitio.

  ~/tools/cvenv/bin/python qa/og.py            # genera en tac-uploads/tac-site/og/
Necesita research/og/Jakarta.ttf (convertida de la woff2 del tema), el apps.json
de MarketingEngine y las capturas originales bajadas a research/tac/.
"""
import json
import os
import pathlib

from PIL import Image, ImageDraw, ImageFilter, ImageFont

RAIZ = pathlib.Path(__file__).resolve().parents[1]
ME = pathlib.Path(os.environ.get('TAC_MARKETING_ENGINE', pathlib.Path.home() / '.aida/workspaces/Intrauia/MarketingEngine'))
APPS = json.loads((ME / 'build/sitios_web/alchemist-apps/apps.json').read_text())
ICONOS = ME / 'build/sitios_web/alchemist-apps/icons'
GALERIA = json.loads((RAIZ / 'research/tac/gallery-match.json').read_text())
FUENTE = str(RAIZ / 'research/og/Jakarta.ttf')
MONO = str(RAIZ / 'research/og/PlexMono-Medium.ttf')
LOGO = RAIZ / 'tac-theme/alchemist-lab-next/assets/img/logo-256.webp'
SALIDA = RAIZ / 'tac-uploads/tac-site/og'

W, H = 1200, 630
BG = (6, 13, 26)
INK = (233, 238, 247)
GRIS = (147, 163, 188)
AZUL = (76, 155, 238)
CIAN = (22, 181, 232)


def fuente(tam, peso='Bold', ruta=FUENTE):
    f = ImageFont.truetype(ruta, tam)
    if ruta == FUENTE:
        f.set_variation_by_name(peso)
    return f


def fondo():
    im = Image.new('RGB', (W, H), BG)
    luz = Image.new('RGB', (W, H), (0, 0, 0))
    d = ImageDraw.Draw(luz)
    d.ellipse((620, -420, 1500, 420), fill=(22, 120, 200))
    d.ellipse((-420, 260, 360, 1000), fill=(10, 70, 150))
    luz = luz.filter(ImageFilter.GaussianBlur(170))
    im = Image.blend(im, luz, 0.42)
    # retícula de puntos como en el hero
    d = ImageDraw.Draw(im)
    for y in range(14, H, 26):
        for x in range(14, W, 26):
            if x > 560:
                d.ellipse((x - 1, y - 1, x + 1, y + 1), fill=(28, 52, 88))
    return im


def redondear(im, radio):
    m = Image.new('L', im.size, 0)
    ImageDraw.Draw(m).rounded_rectangle((0, 0, im.width - 1, im.height - 1), radius=radio, fill=255)
    out = im.convert('RGBA')
    out.putalpha(m)
    return out


def sombra(lienzo, caja, radio, desp=(0, 26), blur=34, alfa=150):
    s = Image.new('RGBA', lienzo.size, (0, 0, 0, 0))
    x0, y0, x1, y1 = caja
    ImageDraw.Draw(s).rounded_rectangle((x0 + desp[0], y0 + desp[1], x1 + desp[0], y1 + desp[1]), radius=radio, fill=(0, 0, 0, alfa))
    lienzo.alpha_composite(s.filter(ImageFilter.GaussianBlur(blur)))


def tarjeta(src, alto, radio=26, giro=0.0):
    """Captura de tienda como tarjeta redondeada, con borde fino y giro opcional."""
    im = Image.open(src).convert('RGB')
    im = im.resize((round(im.width * alto / im.height), alto), Image.LANCZOS)
    im = redondear(im, radio)
    borde = Image.new('RGBA', (im.width + 4, im.height + 4), (0, 0, 0, 0))
    ImageDraw.Draw(borde).rounded_rectangle((0, 0, borde.width - 1, borde.height - 1), radius=radio + 2, fill=(70, 100, 150, 255))
    borde.alpha_composite(im, (2, 2))
    if giro:
        borde = borde.rotate(giro, resample=Image.BICUBIC, expand=True)
    return borde


def envolver(texto, f, ancho, d):
    lineas, actual = [], ''
    for p in texto.split():
        prueba = (actual + ' ' + p).strip()
        if d.textlength(prueba, font=f) <= ancho:
            actual = prueba
        else:
            lineas.append(actual)
            actual = p
    if actual:
        lineas.append(actual)
    return lineas


def marca(lienzo, d, y):
    logo = Image.open(LOGO).convert('RGBA').resize((40, 40), Image.LANCZOS)
    lienzo.alpha_composite(logo, (64, y - 6))
    d.text((114, y), 'The Alchemist Code', font=fuente(25, 'Bold'), fill=INK)
    d.text((114, y + 34), 'thealchemistcode.org', font=fuente(17, 'Medium', MONO), fill=GRIS)


def capturas(k):
    """Hasta dos capturas originales (las mismas que la galería del sitio)."""
    if k == 'coder':
        return [RAIZ / 'research/tac/coder/es-board.webp']
    if k == 'padely':
        return [RAIZ / f'research/tac/ios/padely/{i}.png' for i in (2, 1)]
    fuentes = [v[1] for _, v in sorted(GALERIA.get(k, {}).items()) if v[1] and v[0] < 300]
    return [RAIZ / f for f in fuentes[:2]]


def app_og(a, lang):
    k = a['clave']
    im = fondo().convert('RGBA')
    d = ImageDraw.Draw(im)

    # capturas a la derecha
    caps = capturas(k)
    if k == 'coder':
        t = tarjeta(caps[0], 470, 18, 0)
        x, y = 650, (H - t.height) // 2 + 6
        sombra(im, (x, y, x + t.width, y + t.height), 16)
        im.alpha_composite(t, (x, y))
    else:
        if len(caps) > 1:
            t2 = tarjeta(caps[1], 470, 26, -7)
            x2, y2 = 900, 120
            sombra(im, (x2 + 20, y2 + 20, x2 + t2.width - 20, y2 + t2.height - 20), 26, alfa=120)
            im.alpha_composite(t2, (x2, y2))
        t1 = tarjeta(caps[0], 540, 28, 4)
        x1, y1 = 700, 42
        sombra(im, (x1 + 20, y1 + 20, x1 + t1.width - 20, y1 + t1.height - 20), 28)
        im.alpha_composite(t1, (x1, y1))

    # velo para que el texto respire si la captura invade la columna
    velo = Image.new('RGBA', (W, H), (0, 0, 0, 0))
    vd = ImageDraw.Draw(velo)
    for x in range(0, 700):
        a_ = int(235 * max(0, min(1, (700 - x) / 120)))
        vd.line((x, 0, x, H), fill=BG + (a_,))
    im.alpha_composite(velo)
    d = ImageDraw.Draw(im)

    # icono, categoría y nombre
    icono = Image.open(ICONOS / a['icono']).convert('RGBA').resize((112, 112), Image.LANCZOS)
    icono = redondear(icono, 26)
    sombra(im, (64, 70, 176, 182), 26, desp=(0, 14), blur=20, alfa=170)
    im.alpha_composite(icono, (64, 70))
    cat = APPS['categorias'][a['categoria']][lang].upper()
    d.text((64, 214), cat, font=fuente(19, 'Medium', MONO), fill=AZUL)

    nombre_f = fuente(76, 'ExtraBold')
    if d.textlength(a['nombre'], font=nombre_f) > 600:
        nombre_f = fuente(60, 'ExtraBold')
    d.text((60, 244), a['nombre'], font=nombre_f, fill=INK)

    desc_f = fuente(28, 'Medium')
    texto = a[lang]
    lineas = envolver(texto, desc_f, 560, d)
    # si no cabe en 3 líneas, se quita la última frase (p. ej. las plataformas, que ya van en las etiquetas)
    while len(lineas) > 3 and '. ' in texto.rstrip('.'):
        texto = texto.rstrip('.').rsplit('. ', 1)[0] + '.'
        lineas = envolver(texto, desc_f, 560, d)
    lineas = lineas[:3]
    y = 352
    for ln in lineas:
        d.text((64, y), ln, font=desc_f, fill=GRIS)
        y += 40

    # tiendas
    tiendas = []
    if 'ios' in a['enlaces']:
        tiendas.append('App Store')
    if 'android' in a['enlaces']:
        tiendas.append('Google Play')
    if 'escritorio' in a['enlaces']:
        tiendas.append('macOS · Windows · Linux')
    x = 64
    pf = fuente(18, 'SemiBold')
    for t in tiendas:
        tw = d.textlength(t, font=pf)
        d.rounded_rectangle((x, 500, x + tw + 32, 538), radius=19, outline=(52, 82, 128), width=2, fill=(13, 24, 43))
        d.text((x + 16, 508), t, font=pf, fill=INK)
        x += tw + 44

    marca(im, d, 566)
    return im.convert('RGB')


def estudio_og(lang):
    im = fondo().convert('RGBA')
    pantallas = RAIZ / 'research/phones'  # sin hora ni batería, como en el sitio (qa/phones.py)
    # tres teléfonos como en el hero
    for archivo, alto, x, y, giro in (('soccer24.png', 440, 700, 150, 7), ('caltracker.png', 440, 990, 160, -7), ('inventra.png', 500, 835, 70, 0)):
        t = tarjeta(pantallas / archivo, alto, 34, giro)
        sombra(im, (x + 20, y + 20, x + t.width - 20, y + t.height - 20), 34)
        im.alpha_composite(t, (x, y))
    velo = Image.new('RGBA', (W, H), (0, 0, 0, 0))
    vd = ImageDraw.Draw(velo)
    for x in range(0, 730):
        vd.line((x, 0, x, H), fill=BG + (int(235 * max(0, min(1, (730 - x) / 110))),))
    im.alpha_composite(velo)
    d = ImageDraw.Draw(im)

    pill = 'Estudio de apps en Puebla, México' if lang == 'es' else 'App studio in Puebla, Mexico'
    pf = fuente(19, 'SemiBold')
    tw = d.textlength(pill, font=pf)
    d.rounded_rectangle((64, 64, 64 + tw + 50, 104), radius=20, fill=(13, 24, 43), outline=(36, 55, 90), width=2)
    d.ellipse((82, 79, 92, 89), fill=(34, 197, 94))
    d.text((104, 73), pill, font=pf, fill=INK)

    l1, l2 = (('Apps y IA', 'para tu negocio.') if lang == 'es' else ('Apps and AI', 'for your business.'))
    f1 = fuente(78, 'ExtraBold')
    while d.textlength(l2, font=f1) > 640:
        f1 = fuente(f1.size - 2, 'ExtraBold')
    d.text((60, 140), l1, font=f1, fill=INK)
    # segunda línea con el degradado de marca
    capa = Image.new('L', (W, H), 0)
    ImageDraw.Draw(capa).text((60, 228), l2, font=f1, fill=255)
    grad = Image.new('RGBA', (W, H))
    gd = ImageDraw.Draw(grad)
    for x in range(W):
        t = min(1, x / 640)
        c = tuple(int(a + (b - a) * t) for a, b in zip((10, 95, 180), CIAN))
        gd.line((x, 0, x, H), fill=c + (255,))
    im.paste(grad, (0, 0), capa)

    cifras = (('17', 'apps publicadas' if lang == 'es' else 'apps live'), ('100,000+', 'instalaciones en Google Play' if lang == 'es' else 'Google Play installs'))
    x = 64
    for n, txt in cifras:
        d.rounded_rectangle((x, 368, x + 4, 440), radius=2, fill=AZUL)
        d.text((x + 20, 360), n, font=fuente(46, 'ExtraBold'), fill=INK)
        d.text((x + 20, 416), txt, font=fuente(19, 'Medium'), fill=GRIS)
        x += 300
    sub = 'Diseñamos, programamos y lanzamos apps para iOS, Android y escritorio.' if lang == 'es' else 'We design, build and launch apps for iOS, Android and desktop.'
    y = 470
    for ln in envolver(sub, fuente(24, 'Medium'), 560, d)[:2]:
        d.text((64, y), ln, font=fuente(24, 'Medium'), fill=GRIS)
        y += 34
    marca(im, d, 566)
    return im.convert('RGB')


# páginas de servicio: etiqueta, dos líneas de titular, dos cifras, subtítulo y pantallas (None = red dibujada)
SERVICIOS = {
    'movil': {'es': ('Desarrollo de apps móviles', 'Apps iOS y Android', 'que se publican.', (('17', 'apps publicadas'), ('100,000+', 'instalaciones en Google Play')), 'Diseño, desarrollo, tienda y mantenimiento, desde Puebla.'),
              'en': ('Mobile app development', 'iOS and Android apps', 'that actually ship.', (('17', 'apps live'), ('100,000+', 'Google Play installs')), 'Design, build, store release and maintenance, from Mexico.'),
              'pantallas': ('soccer24.png', 'komodo.png', 'inventra.png')},
    'flutter': {'es': ('Desarrollo en Flutter', 'Flutter, de un código', 'a todas las pantallas.', (('75,685', 'instalaciones de Soccer24'), ('4.5★', 'en Google Play')), 'La mayoría de nuestras 17 apps están hechas en Flutter.'),
                'en': ('Flutter development', 'Flutter, one codebase', 'for every screen.', (('75,685', 'Soccer24 installs'), ('4.5★', 'on Google Play')), 'Most of our 17 apps are built in Flutter.'),
                'pantallas': ('soccer24.png', 'clutch.png', 'komodo.png')},
    'ia': {'es': ('Inteligencia artificial', 'IA aplicada', 'que ahorra trabajo.', (('2025', 'investigación en Springer'), ('OCR', 'en el teléfono')), 'Documentos, imágenes, asistentes y detección de fraude.'),
           'en': ('AI development', 'Applied AI', 'that saves real work.', (('2025', 'Springer research'), ('OCR', 'on the device')), 'Documents, images, assistants and fraud detection.'),
           'pantallas': ('pdfmaster.png', 'caltracker.png', 'pdfmaster.png')},
    'infra': {'es': ('Infraestructura y redes', 'Servidores, nube', 'y redes que operamos.', (('2', 'nodos VPN propios'), ('TLS 1.3', 'en cada nodo')), 'Google Cloud, Firebase, VPN, seguridad y monitoreo.'),
              'en': ('Infrastructure and networks', 'Servers, cloud and', 'networks we run.', (('2', 'VPN nodes we run'), ('TLS 1.3', 'on every node')), 'Google Cloud, Firebase, VPNs, security and monitoring.'),
              'pantallas': None},
}


def red(im):
    """Topología como la del sitio: centro, seis nodos y enlaces."""
    d = ImageDraw.Draw(im)
    cx, cy = 930, 300
    nodos = [(760, 140), (760, 470), (1100, 140), (1100, 470), (930, 80), (930, 530)]
    for i, (x, y) in enumerate(nodos):
        col = CIAN if i < 2 else (70, 120, 180)
        d.line((cx, cy, x, y), fill=col, width=3 if i < 2 else 2)
    for r, a in ((180, 40), (260, 25)):
        d.ellipse((cx - r, cy - r, cx + r, cy + r), outline=(30, 60, 100), width=2)
    for x, y in nodos:
        d.ellipse((x - 16, y - 16, x + 16, y + 16), fill=(12, 39, 72), outline=AZUL, width=3)
    d.ellipse((cx - 58, cy - 58, cx + 58, cy + 58), fill=(10, 95, 180), outline=CIAN, width=4)


def servicio_og(clave, lang):
    cfg = SERVICIOS[clave]
    pill, l1, l2, cifras, sub = cfg[lang]
    im = fondo().convert('RGBA')
    if cfg['pantallas']:
        pantallas = RAIZ / 'research/phones'  # sin hora ni batería, como en el sitio (qa/phones.py)
        a, b, c = cfg['pantallas']
        for archivo, alto, x, y, giro in ((a, 440, 700, 150, 7), (c, 440, 990, 160, -7), (b, 500, 835, 70, 0)):
            t = tarjeta(pantallas / archivo, alto, 34, giro)
            sombra(im, (x + 20, y + 20, x + t.width - 20, y + t.height - 20), 34)
            im.alpha_composite(t, (x, y))
    else:
        red(im)
    velo = Image.new('RGBA', (W, H), (0, 0, 0, 0))
    vd = ImageDraw.Draw(velo)
    for x in range(0, 730):
        vd.line((x, 0, x, H), fill=BG + (int(235 * max(0, min(1, (730 - x) / 110))),))
    im.alpha_composite(velo)
    d = ImageDraw.Draw(im)
    pf = fuente(19, 'SemiBold')
    tw = d.textlength(pill, font=pf)
    d.rounded_rectangle((64, 64, 64 + tw + 50, 104), radius=20, fill=(13, 24, 43), outline=(36, 55, 90), width=2)
    d.ellipse((82, 79, 92, 89), fill=(34, 197, 94))
    d.text((104, 73), pill, font=pf, fill=INK)
    f1 = fuente(72, 'ExtraBold')
    while max(d.textlength(l1, font=f1), d.textlength(l2, font=f1)) > 640:
        f1 = fuente(f1.size - 2, 'ExtraBold')
    d.text((60, 140), l1, font=f1, fill=INK)
    capa = Image.new('L', (W, H), 0)
    ImageDraw.Draw(capa).text((60, 224), l2, font=f1, fill=255)
    grad = Image.new('RGBA', (W, H))
    gd = ImageDraw.Draw(grad)
    for x in range(W):
        t = min(1, x / 640)
        col = tuple(int(a_ + (b_ - a_) * t) for a_, b_ in zip((10, 95, 180), CIAN))
        gd.line((x, 0, x, H), fill=col + (255,))
    im.paste(grad, (0, 0), capa)
    x = 64
    for n, txt in cifras:
        d.rounded_rectangle((x, 368, x + 4, 440), radius=2, fill=AZUL)
        d.text((x + 20, 360), n, font=fuente(46, 'ExtraBold'), fill=INK)
        d.text((x + 20, 416), txt, font=fuente(19, 'Medium'), fill=GRIS)
        x += 300
    y = 470
    for ln in envolver(sub, fuente(24, 'Medium'), 560, d)[:2]:
        d.text((64, y), ln, font=fuente(24, 'Medium'), fill=GRIS)
        y += 34
    marca(im, d, 566)
    return im.convert('RGB')


def main():
    SALIDA.mkdir(parents=True, exist_ok=True)
    for lang in ('es', 'en'):
        estudio_og(lang).save(SALIDA / f'estudio-{lang}.jpg', quality=86, optimize=True, progressive=True)
        for clave in SERVICIOS:
            servicio_og(clave, lang).save(SALIDA / f'{clave}-{lang}.jpg', quality=86, optimize=True, progressive=True)
        for a in APPS['apps']:
            app_og(a, lang).save(SALIDA / f"{a['clave']}-{lang}.jpg", quality=86, optimize=True, progressive=True)
    tam = sorted(os.path.getsize(p) for p in SALIDA.glob('*.jpg'))
    print(f'{len(tam)} imágenes en {SALIDA.relative_to(RAIZ)} · {tam[0] // 1024}–{tam[-1] // 1024} KB')


if __name__ == '__main__':
    main()
