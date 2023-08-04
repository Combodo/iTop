<?php

include __DIR__ . '/vendor/autoload.php';

use Jfcherng\Utility\CliColor;

// colors in a string using a comma as the delimiter
echo CliColor::color('foo', 'f_light_cyan, b_yellow');  // "\033[1;36;43mfoo\033[0m"

echo \PHP_EOL;

// colors in an array
echo CliColor::color('foo', ['f_white', 'b_magenta']); // "\033[1;37;45mfoo\033[0m"

echo \PHP_EOL;

// do not auto reset color at the end of string
echo CliColor::color('foo', ['f_red', 'b_green', 'b', 'blk'], false); // "\033[31;42;1;5mfoo"

// manually add color reset
echo CliColor::color('', 'reset'); // "\033[0m"

echo \PHP_EOL;

// remove all color codes from a string
echo CliColor::noColor("\033[31;42;5mfoo\033[0mbar"); // "foobar"

echo \PHP_EOL;
