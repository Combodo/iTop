#!/usr/bin/env bash
# Runs the iTop setup again in "upgrade" mode, e.g. after changing a datamodel XML file or adding an extension.
# The database is dumped first in .docker/backups/.
set -euo pipefail
cd "$(dirname "$0")"

STAMP=$(date +%Y%m%d-%H%M%S)
mkdir -p backups
echo "> Dumping the database to .docker/backups/itop-$STAMP.sql.gz"
docker compose exec -T db sh -c 'mariadb-dump -uroot -p"$MARIADB_ROOT_PASSWORD" --single-transaction "$MARIADB_DATABASE"' \
  | gzip > "backups/itop-$STAMP.sql.gz"

echo "> Upgrading iTop"
docker compose exec -T -u www-data web sh -c '
  php .docker/render-setup-params.php upgrade > /tmp/itop-setup.xml || exit 1
  php setup/unattended-install/unattended-install.php \
    --param-file=/tmp/itop-setup.xml \
    --installation_xml=datamodels/2.x/installation.xml \
    --use_itop_config
  rc=$?
  rm -f /tmp/itop-setup.xml
  exit $rc
' | tail -n 5

docker compose restart web > /dev/null
echo "Done."
