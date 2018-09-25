#!/usr/bin/env bash

set -x

# on the root dir
composer install


# under the test dir
cd test
composer install
