#!/usr/bin/env bash
set -x

cd test

export DEBUG_UNIT_TEST=0
RUN_NONREG_TESTS=0

#USAGE ${debugMode} ${runNonRegOQLTests} "${coverture}" "${testFile}"

if [ $# -ge 1 -a "x$1" == "xtrue" ]
then
  export DEBUG_UNIT_TEST=1
else
  export DEBUG_UNIT_TEST=0
fi

set -x
OPTION=""
if [ $# -ge 3 -a "x$3" == "xtrue" ]
then
  ##coverture
  OPTION="-dxdebug.coverage_enable=1 --coverage-clover ../var/test/coverage.xml"
fi

TESTFILE="$4"
if [ "x$TESTFILE" != "x" ]
then
  # shellcheck disable=SC2001
  TESTFILE=$(echo "$TESTFILE" | sed 's|test/||1')
  php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml $OPTION $TESTFILE --teamcity
  exit 0
fi

if [ $# -ge 2 -a "x$2" == "xtrue" ]
then
  php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml $OPTION --teamcity
else
  #echo php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml --teamcity
  php vendor/bin/phpunit  --log-junit ../var/test/phpunit-log.junit.xml $OPTION --exclude-group OQL --teamcity
fi
