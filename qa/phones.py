#!/usr/bin/env python3
"""Pantallas completas para los teléfonos del sitio (hero, productos y páginas de app).

Cada una es la pantalla entera de un teléfono real —barra de estado con la hora,
batería y el hueco de la isla— para que dentro del marco CSS se vea como un
teléfono encendido y no como un recorte.

  ~/tools/cvenv/bin/python qa/phones.py   # escribe tac-uploads/tac-site/phones/<app>.webp y @2x
"""
import os
import pathlib

import numpy as np
from PIL import Image, ImageFilter

# carpeta con los repos de cada app (por defecto, la carpeta que contiene este sitio)
APPS = pathlib.Path(os.environ.get('TAC_APPS_DIR', pathlib.Path(__file__).resolve().parents[2]))
RAIZ = pathlib.Path(__file__).resolve().parents[1]
SALIDA = RAIZ / 'tac-uploads/tac-site/phones'
INVESTIGACION = RAIZ / 'research/phones'

PDF_HOME = APPS / 'PDF Master Mobile App/output/store-screenshots/raw/es-MX/iphone-6_9/06-keep-your-document-library-ready.png'


def recorte(ruta, caja):
    return Image.open(ruta).convert('RGB').crop(caja)


def soccer24():
    """La tarjeta de la ficha de App Store no trae barra de estado: se le pone la de una captura real."""
    src = APPS / 'Soccer24/soccer24/fastlane/screenshots/es-MX/1_APP_IPHONE_67_1.jpg'
    tarjeta = np.asarray(recorte(src, (96, 306, 1194, 2618))).copy()
    h, w = tarjeta.shape[:2]
    fondo = np.array([247, 246, 251])
    # las esquinas redondeadas de la tarjeta dejan ver el azul del póster: se rellenan
    hsv = np.asarray(Image.fromarray(tarjeta).convert('HSV'))
    yy, xx = np.mgrid[0:h, 0:w]
    esquina = ((yy < 140) | (yy > h - 140)) & ((xx < 140) | (xx > w - 140)) & (hsv[..., 1] > 50)
    tarjeta[esquina] = fondo
    # barra de estado de PDF Master (mismo iPhone, tema claro), teñida al gris de Soccer24
    barra = Image.open(PDF_HOME).convert('RGB').crop((0, 0, 1320, 170)).resize((w, round(170 * w / 1320)), Image.LANCZOS)
    b = np.asarray(barra).astype(float)
    fondo_pdf = b[8, w // 2 - 260]
    barra = np.clip(b / np.maximum(fondo_pdf, 1) * fondo, 0, 255).astype(np.uint8)
    return Image.fromarray(np.vstack([barra, tarjeta]))


def clutch():
    im = Image.open(APPS / 'nba_app/docs/screenshots/01-partidos.png').convert('RGB')
    a = np.asarray(im).copy()
    # «◂ PeakPlay» (la app desde la que se abrió en el simulador) bajo la hora
    a[112:172, 20:330] = a[100, 400]
    return Image.fromarray(a)


def inventra():
    # pantalla dentro del marco Android del póster de App Store (bisel medido: x 115–1175, y 501–2569)
    return recorte(APPS / 'Inventra Cross/marketing/store-v5/ios/01-dashboard.png', (115, 501, 1176, 2570))


def komodo():
    # pantalla dentro del marco de iPhone del póster (x 200–1092, y 754–2686)
    return recorte(APPS / 'Komodo IOS/fastlane/screenshots/en-US/01.png', (200, 754, 1093, 2687))


def pdfmaster():
    return Image.open(PDF_HOME).convert('RGB')


def caltracker():
    # ya era pantalla completa (barra de estado incluida) a partir de la ficha de App Store
    return Image.open(RAIZ / 'research/tac/screens-hi/caltracker.png').convert('RGB')


PANTALLAS = {'soccer24': soccer24, 'clutch': clutch, 'inventra': inventra, 'komodo': komodo, 'pdfmaster': pdfmaster, 'caltracker': caltracker}

# Alto (px de la imagen fuente) de la barra de estado de cada captura: hora, señal, batería y la isla dibujada.
# Se borra para que el teléfono muestre solo la app; la isla o la cámara las pone el marco CSS.
BARRA = {'soccer24': 132, 'clutch': 140, 'inventra': 62, 'komodo': 124, 'pdfmaster': 156, 'caltracker': 124}

# capturas del Pixel 9 Pro Fold (emulador): pantalla exterior e interior, con su barra de estado de Android
BARRA_PLEGABLE = {'cover-teams': 112, 'inner-teams': 108}


def sin_barra(im, alto, margen=0.075):
    """Recorta la barra de estado (hora, señal, batería, isla) y la franja que dejaba.

    La pantalla empieza en el fondo propio de la app. Si el contenido queda muy pegado
    al borde (las esquinas redondeadas del marco lo cortarían), se agrega arriba un margen
    repitiendo la primera fila limpia de la app, nunca un color inventado.
    """
    a = np.asarray(im.convert('RGB')).astype(np.uint8)
    h, w = a.shape[:2]
    inicio = alto + 2
    g = a[:, :, :].mean(axis=2)
    primera = None
    for y in range(inicio, int(h * .3)):
        fila = g[y, int(w * .015):int(w * .985)]
        if (np.abs(fila - np.median(fila)) > 28).mean() > .01:
            primera = y
            break
    hueco = (primera - inicio) if primera else 0
    relleno = max(0, round(w * margen) - hueco)
    cuerpo = a[inicio:]
    if relleno:
        cuerpo = np.vstack([np.repeat(a[inicio:inicio + 1], relleno, axis=0), cuerpo])
    return Image.fromarray(cuerpo)


def guardar(im, ruta, ancho, calidad):
    if im.width > ancho:
        im = im.resize((ancho, round(im.height * ancho / im.width)), Image.LANCZOS)
        im = im.filter(ImageFilter.UnsharpMask(radius=0.6, percent=35, threshold=2))
    im.save(ruta, 'WEBP', quality=calidad, method=6)
    return im.size


def main():
    SALIDA.mkdir(parents=True, exist_ok=True)
    INVESTIGACION.mkdir(parents=True, exist_ok=True)
    for clave, fn in PANTALLAS.items():
        im = sin_barra(fn(), BARRA[clave])
        im.save(INVESTIGACION / f'{clave}.png')
        # 1x a 600 px y @2x a la resolución completa de la fuente (hasta 1320 px), sin recompresión fuerte
        a = guardar(im, SALIDA / f'{clave}.webp', 600, 88)
        b = guardar(im, SALIDA / f'{clave}@2x.webp', 1320, 90)
        print(f'{clave:<11} origen {im.size[0]}×{im.size[1]} (w/h {im.size[0] / im.size[1]:.3f}) → {a[0]}×{a[1]}, @2x {b[0]}×{b[1]}')
    plegable = RAIZ / 'tac-uploads/tac-site/fold'
    plegable.mkdir(parents=True, exist_ok=True)
    for nombre, (salida, ancho) in {'cover-teams': ('peakplay-cover', 480), 'inner-teams': ('peakplay-inner', 1040)}.items():
        im = sin_barra(Image.open(RAIZ / f'research/fold/{nombre}.png'), BARRA_PLEGABLE[nombre])
        im.save(INVESTIGACION / f'fold-{nombre}.png')
        a = guardar(im, plegable / f'{salida}.webp', ancho, 88)
        b = guardar(im, plegable / f'{salida}@2x.webp', ancho * 2, 90)
        print(f'   proporción {im.size[0] / im.size[1]:.4f}')
        print(f'{salida:<15} → {a[0]}×{a[1]}, @2x {b[0]}×{b[1]}')


if __name__ == '__main__':
    main()
