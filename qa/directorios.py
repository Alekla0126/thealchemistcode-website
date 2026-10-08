#!/usr/bin/env python3
"""Imágenes para los perfiles en directorios (Clutch, GoodFirms, TechBehemoths, Sortlist…).

  ~/tools/cvenv/bin/python qa/directorios.py      # escribe en docs/real-deal/directorios/ (privado)

- caso-<app>-<es|en>.png  1600×1000, una por caso del portafolio: icono, título, cifras con fuente y pantallas
- portada-<es|en>.png     1920×640, portada del perfil
- logo-512.png        logo cuadrado con fondo (algunos directorios no aceptan transparencia)
Usa el estilo y las pantallas sin barra de estado de qa/og.py y qa/phones.py.
"""
import pathlib
import sys

from PIL import Image, ImageDraw

sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import og  # noqa: E402  (fondo, tarjeta, sombra, fuente, envolver, marca, colores)

RAIZ = og.RAIZ
SALIDA = RAIZ / 'docs/real-deal/directorios'
PANTALLAS = RAIZ / 'research/phones'
ICONOS = og.ICONOS

CASOS = [
    ('soccer24', 'Soccer24',
     ('Resultados en vivo de 1,100+ competiciones', 'Live results from 1,100+ competitions'),
     ((('75,685', 'instalaciones · Play Console, sep 2026'), ('4.5★', 'Google Play · oct 2026')),
      (('75,685', 'installs · Play Console, Sep 2026'), ('4.5★', 'Google Play · Oct 2026'))),
     'Flutter · Swift · Kotlin · Firebase', ('soccer24.png',)),
    ('komodo', 'Komodo VPN',
     ('VPN sobre una red de servidores propia', 'A VPN on our own server network'),
     ((('30,566', 'instalaciones · Play Console, sep 2026'), ('TLS 1.3', 'AES-256-GCM en cada nodo')),
      (('30,566', 'installs · Play Console, Sep 2026'), ('TLS 1.3', 'AES-256-GCM on every node'))),
     'Flutter · OpenVPN · Linux servers', ('komodo.png',)),
    ('inventra', 'Inventra',
     ('Inventario multisucursal en la nube', 'Multi-branch inventory in the cloud'),
     ((('B2B', 'productos, sucursales, ventas y compras'), ('Nube', 'sincronización y lector de códigos')),
      (('B2B', 'products, branches, sales and purchases'), ('Cloud', 'sync and barcode scanning'))),
     'Flutter · Cloud Firestore', ('inventra.png',)),
    ('peakplay', 'PeakPlay NFL',
     ('Una app que se adapta a plegables', 'One app that adapts to foldables'),
     ((('2 → 4', 'columnas al abrir un Pixel 9 Pro Fold'), ('32', 'equipos, marcadores y estadísticas')),
      (('2 → 4', 'columns as a Pixel 9 Pro Fold opens'), ('32', 'teams, live scores and stats'))),
     'Flutter · adaptive layout', ('fold-inner-teams.png',)),
    ('pdfmaster', 'PDF Master',
     ('Escáner de documentos con OCR en el teléfono', 'Document scanner with on-device OCR'),
     ((('OCR', 'el documento no sale del dispositivo'), ('Web', 'API en Cloud Run con pagos Stripe')),
      (('OCR', 'documents never leave the device'), ('Web', 'Cloud Run API with Stripe payments'))),
     'Flutter · OCR · Cloud Run', ('pdfmaster.png',)),
]


def caso(clave, nombre, titulos, cifras_l, stack, pantallas, L=0):
    titulo, cifras = titulos[L], cifras_l[L]
    W, H = 1600, 1000
    fin_velo = 1000
    im = og.fondo().resize((W, H)).convert('RGBA')
    d = ImageDraw.Draw(im)
    # pantallas a la derecha
    if pantallas[0].startswith('fold-'):
        t = og.tarjeta(PANTALLAS / pantallas[0], 600, 26, 0)
        x, y = W - t.width - 60, (H - t.height) // 2
        fin_velo = x - 10
        og.sombra(im, (x, y, x + t.width, y + t.height), 30)
        im.alpha_composite(t, (x, y))
    else:
        xs = [1040, 1250] if len(pantallas) > 1 else [1110]
        for i, (p, x) in enumerate(zip(pantallas, xs)):
            t = og.tarjeta(PANTALLAS / p, 820 if i == 0 else 740, 40, 0)
            y = 90 if i == 0 else 150
            og.sombra(im, (x + 20, y + 20, x + t.width - 20, y + t.height - 20), 40)
            im.alpha_composite(t, (x, y))
    velo = Image.new('RGBA', (W, H), (0, 0, 0, 0))
    vd = ImageDraw.Draw(velo)
    for x in range(0, fin_velo):
        vd.line((x, 0, x, H), fill=og.BG + (int(240 * max(0, min(1, (fin_velo - x) / 140))),))
    im.alpha_composite(velo)
    d = ImageDraw.Draw(im)
    icono = Image.open(ICONOS / f'{clave}.png').convert('RGBA').resize((120, 120), Image.LANCZOS)
    im.alpha_composite(og.redondear(icono, 28), (90, 96))
    d.text((90, 250), nombre, font=og.fuente(84, 'ExtraBold'), fill=og.INK)
    y = 360
    for ln in og.envolver(titulo, og.fuente(38, 'SemiBold'), 820, d)[:2]:
        d.text((92, y), ln, font=og.fuente(38, 'SemiBold'), fill=og.GRIS)
        y += 50
    x = 92
    for n, txt in cifras:
        d.rounded_rectangle((x, y + 60, x + 5, y + 160), radius=2, fill=og.AZUL)
        d.text((x + 26, y + 52), n, font=og.fuente(66, 'ExtraBold'), fill=og.INK)
        for i, ln in enumerate(og.envolver(txt, og.fuente(22, 'Medium'), 330, d)[:2]):
            d.text((x + 28, y + 132 + i * 28), ln, font=og.fuente(22, 'Medium'), fill=og.GRIS)
        x += 420
    d.text((92, 840), stack, font=og.fuente(24, 'Medium', og.MONO), fill=og.AZUL)
    og.marca(im, d, 900)
    return im.convert('RGB')


def portada(L=0):
    W, H = 1920, 640
    im = og.fondo().resize((W, H)).convert('RGBA')
    d = ImageDraw.Draw(im)
    for i, (p, x, y) in enumerate((('soccer24.png', 1240, 70), ('inventra.png', 1440, 40), ('komodo.png', 1640, 90))):
        t = og.tarjeta(PANTALLAS / p, 520 if i == 1 else 470, 34, 0)
        og.sombra(im, (x + 20, y + 20, x + t.width - 20, y + t.height - 20), 34)
        im.alpha_composite(t, (x, y))
    velo = Image.new('RGBA', (W, H), (0, 0, 0, 0))
    vd = ImageDraw.Draw(velo)
    for x in range(0, 1260):
        vd.line((x, 0, x, H), fill=og.BG + (int(235 * max(0, min(1, (1260 - x) / 160))),))
    im.alpha_composite(velo)
    d = ImageDraw.Draw(im)
    l1, l2, sub = (('Apps e IA', 'listas para producción.', '17 apps propias · 100,000+ instalaciones en Google Play · Puebla, México'),
                   ('Apps and AI,', 'built for production.', '17 apps of our own · 100,000+ Google Play installs · Puebla, Mexico'))[L]
    d.text((110, 150), l1, font=og.fuente(96, 'ExtraBold'), fill=og.INK)
    d.text((110, 260), l2, font=og.fuente(96, 'ExtraBold'), fill=og.CIAN)
    d.text((114, 400), sub, font=og.fuente(30, 'Medium'), fill=og.GRIS)
    og.marca(im, d, 520)
    return im.convert('RGB')


def logo():
    lienzo = Image.new('RGBA', (512, 512), og.BG + (255,))
    l = Image.open('/tmp/logo-tac.png').convert('RGBA').resize((360, 360), Image.LANCZOS)
    lienzo.alpha_composite(l, (76, 76))
    return lienzo.convert('RGB')


def main():
    SALIDA.mkdir(parents=True, exist_ok=True)
    for L, sufijo in ((0, 'es'), (1, 'en')):
        for c in CASOS:
            caso(*c, L=L).save(SALIDA / f'caso-{c[0]}-{sufijo}.png', optimize=True)
        portada(L).save(SALIDA / f'portada-{sufijo}.png', optimize=True)
    if pathlib.Path('/tmp/logo-tac.png').exists():
        logo().save(SALIDA / 'logo-512.png', optimize=True)
    print('\n'.join(sorted(p.name for p in SALIDA.glob('*.png'))))


if __name__ == '__main__':
    main()
