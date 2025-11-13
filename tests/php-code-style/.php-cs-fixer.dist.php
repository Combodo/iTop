<?php

$APPROOT = dirname(__DIR__, 2);

echo $APPROOT;
$finder = PhpCsFixer\Finder::create()
	->in($APPROOT)
	->exclude([
		'core/oql',
		'data',
		'extensions',
		'lib',
		'node_modules',
	])
	->notPath([
		// Exclude environment folders based on a regex as we can't use a regex in ->exclude()
		'|^env-(.*)|',
		// Exclude third-party sub-folders
		'vendor',
		'node_modules'
	])
;

$config = new PhpCsFixer\Config();
return $config->setRiskyAllowed(true)
	->setRules([
		'@PSR12'       => true,
		'indentation_type' => true,
		'no_extra_blank_lines' => true,
		'array_syntax' => ['syntax' => 'short'],
		'concat_space' => true,
		'trailing_comma_in_multiline' => true,
	])
	->setIndent("\t")
	->setLineEnding("\n")
	->setFinder($finder)
;