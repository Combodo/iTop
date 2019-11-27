#!/usr/bin/env bash

set -x

chmod 666 conf/production/config-itop.php

cd toolkit
php unattended_install.php --response_file=default-params.xml --clean=true
