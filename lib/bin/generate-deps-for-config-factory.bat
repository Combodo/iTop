@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/generate-deps-for-config-factory
SET COMPOSER_RUNTIME_BIN_DIR=%~dp0
php "%BIN_TARGET%" %*
