@echo off
REM
REM To be scheduled by the following command:
REM
REM schtasks /create /tn "iTop Cron" /sc minute /tr "\"C:\www\iTop\webservices\cron.cmd\""
REM
REM
REM PHP_PATH must point to php.exe, adjust the path to suit your own installation
SET PHP_PATH=%~f1
REM PHP_INI must contain the full path to a PHP.ini file suitable for Command Line mode execution
SET PHP_INI=%~dp1php.ini
REM The double dash (--) separates the parameters parsed by php.exe from the script's specific parameters
REM %~p0 expands to the path to this file (including the trailing backslash)
SET CRON_SCRIPT=%~dp0cron.php
REM Adjust the path below if you use a param files stored in a different location
SET PARAMS_FILE=%~dp0cron.params
REM Actual PHP invocation
"%PHP_PATH%" -c "%PHP_INI%" -f "%CRON_SCRIPT%" -- --param_file="%PARAMS_FILE%" --verbose=1 >> "%~dp0..\log\cron.log"
