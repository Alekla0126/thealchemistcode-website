#!/bin/bash
# Sube la v3 a la VISTA PREVIA de thealchemistcode.org (tema inactivo alchemist-lab-next)
# e imágenes nuevas a uploads/tac-site sin sobrescribir ninguna existente.
# Ver con: https://thealchemistcode.org/?tac_preview=<clave>
set -euo pipefail
cd "$(dirname "$0")"
# Datos del servidor en .env.deploy (no se versiona; ver .env.deploy.example)
[ -f .env.deploy ] && . ./.env.deploy
: "${TAC_SSH_TARGET:?Define TAC_SSH_TARGET (usuario@host) en .env.deploy}"
SSH=(ssh -i "${TAC_SSH_KEY:-$HOME/.ssh/id_ed25519}" -p "${TAC_SSH_PORT:-22}" -o ConnectTimeout=20 -o BatchMode=yes -o ServerAliveInterval=10 -o ServerAliveCountMax=3 -o LogLevel=ERROR "$TAC_SSH_TARGET")
WP_DIR="${TAC_WP_DIR:-thealchemistcode.org}"  # relativo al home del usuario SSH

echo "→ tema alchemist-lab-next"
COPYFILE_DISABLE=1 tar --no-xattrs -czf - -C tac-theme --exclude='.DS_Store' alchemist-lab-next \
  | "${SSH[@]}" "set -e; cd $WP_DIR/wp-content/themes"' && tar xzf - && for f in $(find alchemist-lab-next -name "*.php"); do php -l "$f" >/dev/null || { echo "ERROR de sintaxis: $f"; exit 1; }; done; echo "  ok ($(grep -m1 "^Version" alchemist-lab-next/style.css))"'

echo "→ imágenes nuevas (no sobrescribe)"
COPYFILE_DISABLE=1 tar --no-xattrs -czf - -C tac-uploads --exclude='.DS_Store' tac-site \
  | "${SSH[@]}" "cd $WP_DIR/wp-content/uploads"' && { tar xzkf - 2>/dev/null || true; }; echo "  ok"'

echo "Vista previa actualizada."
