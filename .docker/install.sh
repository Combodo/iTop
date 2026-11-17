#!/usr/bin/env bash
# Builds and starts the Docker development environment, then installs iTop (ITIL, with demo data by default).
#
# Usage: .docker/install.sh [--force]
#   --force  Reinstall from scratch: DELETES the current configuration file, env-production/ and the database.
set -euo pipefail
cd "$(dirname "$0")"

CLEAN=0
if [ "${1:-}" = "--force" ]; then
  CLEAN=1
fi

if [ ! -f .env ]; then
  echo "Missing .docker/.env: copy .docker/.env.dist to .docker/.env and adjust it first." >&2
  exit 1
fi

if [ -f ../conf/production/config-itop.php ] && [ "$CLEAN" -eq 0 ]; then
  echo "iTop is already installed (conf/production/config-itop.php exists)." >&2
  echo "Use .docker/upgrade.sh to apply changes, or .docker/install.sh --force to reinstall from scratch." >&2
  exit 1
fi

echo "> Building and starting the containers"
docker compose up -d --build --wait

echo "> Installing iTop"
docker compose exec -T -u www-data web sh -c '
  php .docker/render-setup-params.php install > /tmp/itop-setup.xml || exit 1
  php setup/unattended-install/unattended-install.php \
    --param-file=/tmp/itop-setup.xml \
    --installation_xml=datamodels/2.x/installation.xml \
    --clean='"$CLEAN"'
  rc=$?
  rm -f /tmp/itop-setup.xml
  exit $rc
' | tail -n 5

docker compose restart web > /dev/null
PORT=$(grep -E '^HTTP_PORT=' .env | cut -d= -f2)
echo "Done: http://localhost:${PORT:-8080}/"
