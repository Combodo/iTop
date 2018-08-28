rem must be run with current directory = the directory of the batch
rem PEAR is required to build
php -d include_path=".;C:\Dev\wamp64\bin\php\php5.6.31\pear" ".\PHP\LexerGenerator\cli.php" ..\oql-lexer.plex
php ".\PHP\ParserGenerator\cli.php" ..\oql-parser.y
php -r "echo date('Y-m-d');" > ..\version.txt
pause