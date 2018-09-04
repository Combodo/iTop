#!/usr/bin/env bash

set -x

cd test


export DEBUG_UNIT_TEST="0"

php vendor/bin/phpunit  --log-junit var/test/phpunit-log.junit.xml --teamcity