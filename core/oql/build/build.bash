#!/bin/bash
#
# Rebuild the iTop Lexer / Parser
# PEAR is required to build (really?)
# Launch this batch from the core/oql/build directory
# with ./build.bash
#
php PHP/LexerGenerator/cli.php ../oql-lexer.plex
php PHP/ParserGenerator/cli.php ../oql-parser.y
php -r "echo date('Y-m-d');" > ../version.txt

