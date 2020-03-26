@echo off
REM
REM To be scheduled by the following command:
REM
REM schtasks /create /tn "iTop Cron" /sc minute /tr "C:\www\iTop\webservices\cron.cmd C:\php\php.exe"
REM
REM
REM PHP_COMMAND must point to php.exe, adjust the first to suit your own installation
REM %~f1 expand the first argument to a fully qualified path name
SET PHP_COMMAND=%~f1
REM PHP_INI must contain the full path to a PHP.ini file suitable for Command Line mode execution
REM %~dp1 expands to the path of the first argument (php command)
SET PHP_INI=%~dp1php.ini
REM %~dp0 expands to the path to this file (including the trailing backslash)
SET CRON_PATH=%~dp0
REM Adjust the path below if you use a param files stored in a different location
SET PARAMS_FILE=%CRON_PATH%cron.params
REM Adjust the path below if you want the log files stored in a different location
SET LOG_FILE=%CRON_PATH%..\log\cron.log
REM
REM Actual PHP invocation
REM The double dash (--) separates the parameters parsed by php.exe from the script's specific parameters
"%PHP_COMMAND%" -c "%PHP_INI%" -f "%CRON_PATH%cron.php" -- --param_file="%PARAMS_FILE%" --verbose=1 >> "%LOG_FILE%"
