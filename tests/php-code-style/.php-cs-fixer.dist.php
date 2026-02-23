<?php

$APPROOT = dirname(__DIR__, 2);

echo $APPROOT;
$finder = PhpCsFixer\Finder::create()
	->in($APPROOT)
	->exclude(['oql', 'data', 'extensions'])
	->notPath(['/env-*/', '/cache-*/', 'lib', 'vendor', 'node_modules', 'config-itop', 'php-static-analysis', 'module.__MODULE__.php'])
;

$config = new PhpCsFixer\Config();
return $config->setRiskyAllowed(true)
	->setRules([
		'@PSR12'       => true,
		'no_extra_blank_lines' => true, // default value ['tokens' => ['extra']]
		'array_syntax' => true, // default value ['syntax' => 'short']
		'concat_space' => true, // default value ['spacing' => 'none']
		'trailing_comma_in_multiline' => true, // default value ['after_heredoc' => false, 'elements' => ['arrays']]
	])
	->setIndent("\t")
	->setLineEnding("\n")
	->setFinder($finder)
;
