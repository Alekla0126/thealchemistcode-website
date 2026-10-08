---
title: "VPN para empresas con sucursales: guía práctica para conectar tu operación"
slug: vpn-para-empresas-con-sucursales
lang: es
servicio: infra
seo_title: "VPN para empresas con sucursales y personal remoto: guía"
seo_desc: "Cómo conectar sucursales, bodegas y personal remoto con una VPN para empresas: tipos de VPN, OpenVPN o WireGuard, seguridad, costos y errores comunes."
excerpt: "Una VPN bien montada permite que tus sucursales, tu bodega y tu personal remoto usen los mismos sistemas sin dejarlos expuestos a internet. Te explicamos los tipos de VPN, cómo elegir y los errores que vemos más seguido."
---

Muchas empresas en México crecen sucursal por sucursal, y cada una termina con su propio módem, su propia computadora "servidor" y sus propias contraseñas. Un día alguien necesita ver el inventario de la otra sucursal desde casa, y la solución improvisada es abrir un puerto o compartir una cuenta de escritorio remoto.

Una VPN (red privada virtual) resuelve ese problema: conecta tus ubicaciones y a tu personal por un túnel cifrado, como si todos estuvieran en la misma oficina.

## Para qué sirve una VPN en una empresa

- **Unir sucursales y bodegas** para que el punto de venta, el inventario y los reportes usen el mismo sistema.
- **Dar acceso remoto** al personal que trabaja desde casa, en ruta o con clientes.
- **Proteger sistemas internos** que nunca deberían quedar abiertos a internet, como un ERP, una base de datos o un panel de administración.
- **Cifrar la conexión** en redes Wi-Fi públicas de hoteles, aeropuertos y cafeterías.

## Los dos tipos que vas a escuchar

| Tipo | Qué conecta | Ejemplo |
|---|---|---|
| Sitio a sitio | Una red completa con otra | La sucursal norte con la oficina central, de forma permanente |
| Acceso remoto | Una persona con la red de la empresa | Una vendedora que consulta precios desde su laptop |

La mayoría de las empresas con sucursales necesita las dos.

## OpenVPN o WireGuard

Son los dos protocolos de código abierto más usados para VPN propias:

- **OpenVPN** existe desde hace muchos años, funciona en casi cualquier equipo y puede ir por el puerto 443, el mismo de las páginas web, lo que ayuda en redes que bloquean otros puertos. Es el que operamos en producción para Komodo VPN, con cifrado TLS 1.3 y AES-256-GCM.
- **WireGuard** es más reciente, más simple y suele ser más rápido. Está integrado en el kernel de Linux y tiene clientes para los sistemas principales.

Para una empresa, la diferencia práctica suele estar en los equipos que ya tienes (algunos routers y firewalls solo admiten uno) y en cómo vas a administrar los accesos.

## Cómo se ve una VPN bien montada

1. **Accesos por persona, no compartidos.** Cada empleado tiene su propio acceso, que se da de alta o se revoca en minutos cuando alguien entra o sale de la empresa.
2. **Acceso mínimo.** Ventas ve el sistema de ventas; no necesita entrar al servidor de contabilidad.
3. **Segundo factor de autenticación** para el acceso remoto, sobre todo para administradores.
4. **Monitoreo.** Si un nodo se cae o alguien intenta entrar muchas veces, te enteras antes que tus usuarios.
5. **Configuración documentada y reproducible.** Si mañana cambias de proveedor de internet o de servidor, el montaje se repite sin depender de la memoria de una persona.

## Errores que vemos seguido

- **Abrir el escritorio remoto directo a internet.** Es una de las puertas de entrada más comunes para ataques de ransomware.
- **Una sola contraseña para todos.** Cuando alguien se va, nadie la cambia.
- **El "servidor" es una computadora de escritorio** debajo de un mostrador, sin respaldos ni energía de respaldo.
- **Nadie revisa las actualizaciones** del router o del firewall.

## ¿Servidor propio, en la nube o en el router?

- **En el router o firewall de cada sucursal**: buena opción para conexiones sitio a sitio permanentes si tus equipos lo permiten.
- **En un servidor en la nube**: un servidor Linux pequeño puede ser el punto central de la VPN para todas las sucursales y para el personal remoto, con un costo mensual bajo y predecible.
- **Servicio administrado de terceros**: rápido de arrancar, con costo por usuario y menos control.

Antes de comprar equipo, conviene medir cuántas personas se conectan a la vez y cuánto tráfico mueven. En Komodo VPN calculamos que el autoescalado no ahorraba nada hasta tener cientos de usuarios Premium conectados al mismo tiempo, así que la decisión correcta fue dejar todo listo para escalar en minutos, no pagar de más desde el inicio.

## Lista para empezar

- Lista de ubicaciones y cuántas personas trabajan en cada una.
- Sistemas que deben compartirse y quién necesita cada uno.
- Equipos de red actuales: marca y modelo del router o firewall.
- Quién va a administrar los accesos dentro de tu empresa.

## Te ayudamos a montarla

Diseñamos, montamos y operamos infraestructura: servidores Linux, Google Cloud, Firebase, VPN, seguridad y monitoreo. Operamos nuestra propia red de servidores VPN en Europa para Komodo VPN, una app con más de 30,000 instalaciones en Google Play. Conoce nuestro servicio de [infraestructura y redes](/infraestructura-y-redes/).
