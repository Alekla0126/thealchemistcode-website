#!/bin/bash
# Sube la copia de trabajo (tac-theme/alchemist-lab-next) al tema ACTIVO de thealchemistcode.org
# (carpeta alchemist-lab en el servidor), valida PHP y vacía la caché de LiteSpeed.
# La v2 anterior quedó en themes/alchemist-lab-v2 por si hay que volver.
set -euo pipefail
cd "$(dirname "$0")"
# Datos del servidor en .env.deploy (no se versiona; ver .env.deploy.example)
[ -f .env.deploy ] && . ./.env.deploy
: "${TAC_SSH_TARGET:?Define TAC_SSH_TARGET (usuario@host) en .env.deploy}"
SSH=(ssh -i "${TAC_SSH_KEY:-$HOME/.ssh/id_ed25519}" -p "${TAC_SSH_PORT:-22}" -o ConnectTimeout=20 -o BatchMode=yes -o ServerAliveInterval=10 -o ServerAliveCountMax=3 -o LogLevel=ERROR "$TAC_SSH_TARGET")
WP_DIR="${TAC_WP_DIR:-thealchemistcode.org}"  # relativo al home del usuario SSH
COPYFILE_DISABLE=1 tar --no-xattrs -czf - -C tac-theme --exclude='.DS_Store' -s ',^alchemist-lab-next,alchemist-lab,' alchemist-lab-next \
  | "${SSH[@]}" "set -e; cd $WP_DIR/wp-content/themes"' && tar xzf - && for f in $(find alchemist-lab -name "*.php"); do php -l "$f" >/dev/null || { echo "ERROR de sintaxis: $f"; exit 1; }; done; find ~/lscache -type f -delete; echo "Producción actualizada: $(grep -m1 "^Version" alchemist-lab/style.css)"'
