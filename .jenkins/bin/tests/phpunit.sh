#!/usr/bin/env bash

set -x


php test/vendor/bin/phpunit test/ --log-junit var/test/phpunit-log.junit.xml --teamcity