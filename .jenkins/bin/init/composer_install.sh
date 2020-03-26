#!/usr/bin/env bash

set -x

# on the root dir
# composer install -a # => Not needed anymore (libs were added to git with N°2435)


# under the test dir
cd test
composer install
