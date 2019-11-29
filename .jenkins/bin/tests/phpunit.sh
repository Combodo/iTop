#!/usr/bin/env bash
set -x

cd test

export DEBUG_UNIT_TEST=0
RUN_NONREG_TESTS=0

if [ $# -ge 1 ]
then
  export DEBUG_UNIT_TEST=$1
fi

if [ $# -ge 2 ]
then
  RUN_NONREG_TESTS=$2
fi

#php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml --teamcity