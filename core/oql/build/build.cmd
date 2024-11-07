rem must be run with current directory = the directory of the batch
rem PEAR is required to build
rem  PHP 8.0+ is not supported by the parser generator
php -d include_path=".;C:\iTop\PHP\PEAR" ".\PHP\LexerGenerator\cli.php" ..\oql-lexer.plex
php ".\PHP\ParserGenerator\cli.php" ..\oql-parser.y
php -r "echo date('Y-m-d');" > ..\version.txt
pause