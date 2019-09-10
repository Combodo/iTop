#!/usr/bin/env bash

set -x

# create target dirs
mkdir -p var
mkdir -p toolkit

# cleanup target dirs
rm -rf toolkit/*

# fill target dirs
curl https://www.combodo.com/documentation/iTopDataModelToolkit-2.3.zip > toolkit.zip
unzip toolkit.zip
rm toolkit.zip
cp -r .jenkins/configuration/default-environment/unattended_install/* toolkit
