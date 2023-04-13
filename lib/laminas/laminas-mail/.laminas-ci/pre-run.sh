#!/bin/bash

set -euo pipefail

INTL_EXTENSION=$(php -r 'echo sprintf("php%s.%s-intl",PHP_MAJOR_VERSION,PHP_MINOR_VERSION);')

echo -e "Removing $INTL_EXTENSION extension:\n"
sudo apt remove "$INTL_EXTENSION" -y

echo -e "Cleaning up:\n"
sudo apt autoclean && sudo apt autoremove

echo -e "Installed extensions:\n"
/usr/local/bin/php-extensions-with-version.php