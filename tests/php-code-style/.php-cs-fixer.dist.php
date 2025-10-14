<?php

$APPROOT=dirname(__DIR__, 2);

echo $APPROOT;
$finder = PhpCsFixer\Finder::create()
    ->exclude('oql')
	->in($APPROOT.'/addons')
	->in($APPROOT.'/application')
	->in($APPROOT.'/core')
	->in($APPROOT.'/datamodels')
	->in($APPROOT.'/dictionaries')
	->in($APPROOT.'/pages')
	->in($APPROOT.'/portal')
	->in($APPROOT.'/setup')
	->in($APPROOT.'/sources')
	->in($APPROOT.'/synchro')
	->in($APPROOT.'/tests')
    ->in($APPROOT . '/webservices')
    ;

$config = new PhpCsFixer\Config();
return $config->setRiskyAllowed(true)
    ->setRules([
	    '@PSR12'       => true,
        'no_extra_blank_lines' => true,
	    'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;