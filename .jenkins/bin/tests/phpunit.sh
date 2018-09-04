#!/usr/bin/env bash

set -x

cd test

php vendor/bin/phpunit  --log-junit var/test/phpunit-log.junit.xml --teamcity