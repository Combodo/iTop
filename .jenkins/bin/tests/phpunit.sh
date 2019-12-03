#!/usr/bin/env bash
set -x

cd test

export DEBUG_UNIT_TEST=0
RUN_NONREG_TESTS=0

if [ $# -ge 1 -a "x$1" == "xtrue" ]
then
  export DEBUG_UNIT_TEST=1
else
  export DEBUG_UNIT_TEST=0
fi

if [ $# -ge 2 -a "x$2" == "xtrue" ]
then
  echo php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml --teamcity
else
  echo php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml --teamcity
  #echo php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml --exclude-group OQL --teamcity
fi
