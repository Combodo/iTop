@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../laminas/laminas-servicemanager/bin/generate-deps-for-config-factory
php "%BIN_TARGET%" %*
