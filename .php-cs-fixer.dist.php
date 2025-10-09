<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('oql')
	->in(__DIR__.'/addons')
	->in(__DIR__.'/application')
	->in(__DIR__.'/core')
	->in(__DIR__.'/datamodels')
	->in(__DIR__.'/dictionaries')
	->in(__DIR__.'/pages')
	->in(__DIR__.'/portal')
	->in(__DIR__.'/setup')
	->in(__DIR__.'/sources')
	->in(__DIR__.'/synchro')
	->in(__DIR__.'/tests')
    ->in(__DIR__ . '/webservices')
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