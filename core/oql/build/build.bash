#!/bin/bash
php PHP/LexerGenerator/cli.php oql-lexer.plex
php PHP/ParserGenerator/cli.php oql-parser.y

