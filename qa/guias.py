#!/usr/bin/env python3
"""Convierte las guías de content/blog/*.md en bloques de WordPress y las deja listas para publicar.

  python3 qa/guias.py > /tmp/guias.json      # luego: wp eval-file qa/publicar-guias.php /tmp/guias.json

Cada .md lleva un encabezado con title, slug, lang (es|en), servicio (vista de la página de servicio),
seo_title, seo_desc y excerpt. El cuerpo admite ## y ###, párrafos, listas (- y 1.), tablas,
**negritas**, `código` y [enlaces](/ruta/).
"""
import html
import json
import pathlib
import re
import sys

RAIZ = pathlib.Path(__file__).resolve().parents[1]
GUIAS = RAIZ / 'content/blog'


def frontmatter(texto):
    m = re.match(r'^---\n(.*?)\n---\n', texto, re.S)
    datos = {}
    for linea in m.group(1).splitlines():
        k, v = linea.split(':', 1)
        datos[k.strip()] = json.loads(v.strip()) if v.strip().startswith('"') else v.strip()
    return datos, texto[m.end():]


def en_linea(t):
    t = html.escape(t, quote=False)
    t = re.sub(r'`([^`]+)`', r'<code>\1</code>', t)
    t = re.sub(r'\*\*([^*]+)\*\*', r'<strong>\1</strong>', t)
    t = re.sub(r'\[([^\]]+)\]\(([^)]+)\)', lambda m: f'<a href="{html.escape(m.group(2))}">{m.group(1)}</a>', t)
    return t


def bloque_lista(items, ordenada):
    tag = 'ol' if ordenada else 'ul'
    attrs = ' {"ordered":true}' if ordenada else ''
    lis = ''.join(f'<!-- wp:list-item -->\n<li>{en_linea(i)}</li>\n<!-- /wp:list-item -->\n' for i in items)
    return f'<!-- wp:list{attrs} -->\n<{tag} class="wp-block-list">{lis}</{tag}>\n<!-- /wp:list -->'


def bloque_tabla(filas):
    celdas = [[c.strip() for c in f.strip().strip('|').split('|')] for f in filas]
    cab, cuerpo = celdas[0], [c for c in celdas[2:]]
    th = ''.join(f'<th>{en_linea(c)}</th>' for c in cab)
    trs = ''.join('<tr>' + ''.join(f'<td>{en_linea(c)}</td>' for c in f) + '</tr>' for f in cuerpo)
    return ('<!-- wp:table -->\n<figure class="wp-block-table"><table><thead><tr>' + th + '</tr></thead><tbody>'
            + trs + '</tbody></table></figure>\n<!-- /wp:table -->')


def a_bloques(md):
    out, parrafo, lista, ordenada, tabla = [], [], [], False, []

    def cerrar():
        nonlocal parrafo, lista, tabla
        if parrafo:
            out.append(f'<!-- wp:paragraph -->\n<p>{en_linea(" ".join(parrafo))}</p>\n<!-- /wp:paragraph -->')
            parrafo = []
        if lista:
            out.append(bloque_lista(lista, ordenada))
            lista = []
        if tabla:
            out.append(bloque_tabla(tabla))
            tabla = []

    for linea in md.splitlines():
        s = linea.rstrip()
        if not s.strip():
            cerrar()
            continue
        if s.startswith('### ') or s.startswith('## '):
            cerrar()
            nivel = 3 if s.startswith('### ') else 2
            texto = s[nivel + 1:]
            attrs = ' {"level":3}' if nivel == 3 else ''
            out.append(f'<!-- wp:heading{attrs} -->\n<h{nivel} class="wp-block-heading">{en_linea(texto)}</h{nivel}>\n<!-- /wp:heading -->')
            continue
        if s.startswith('|'):
            if parrafo or lista:
                cerrar()
            tabla.append(s)
            continue
        m_ul = re.match(r'^- (.*)', s)
        m_ol = re.match(r'^\d+\. (.*)', s)
        if m_ul or m_ol:
            if parrafo or tabla or (lista and ordenada != bool(m_ol)):
                cerrar()
            ordenada = bool(m_ol)
            lista.append((m_ol or m_ul).group(1))
            continue
        if lista or tabla:
            cerrar()
        parrafo.append(s.strip())
    cerrar()
    return '\n\n'.join(out)


def main():
    guias = []
    for f in sorted(GUIAS.glob('*.md')):
        datos, cuerpo = frontmatter(f.read_text())
        datos['content'] = a_bloques(cuerpo)
        datos['words'] = len(re.findall(r'\w+', cuerpo))
        guias.append(datos)
        print(f"{datos['lang']}  {datos['words']:>5} palabras  /{datos['slug']}/", file=sys.stderr)
    json.dump(guias, sys.stdout, ensure_ascii=False, indent=1)


if __name__ == '__main__':
    main()
