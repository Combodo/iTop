<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('oql')
    //->in(__DIR__ . '/addons')
//    ->in(__DIR__ . '/application')
    //->in(__DIR__ . '/core')
//    ->in(__DIR__ . '/datamodels')
//    ->in(__DIR__ . '/dictionaries')
//    ->in(__DIR__ . '/pages')
//    ->in(__DIR__ . '/portal')
//    ->in(__DIR__ . '/setup')
//    ->in(__DIR__ . '/sources')
//    ->in(__DIR__ . '/synchro')
//    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/webservices')
    ;

$config = new PhpCsFixer\Config();
return $config->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        // We use PSR12 with consistent brace placement.
        'curly_braces_position' => [
            'functions_opening_brace' => 'same_line',
            'classes_opening_brace' => 'same_line',
        ],
        // declare(strict_types=1) on the same line as <?php.
        'blank_line_after_opening_tag' => false,
        'declare_strict_types' => true,
        // Keep argument formatting for now.
        'method_argument_space' => ['on_multiline' => 'ignore'],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_trim' => true,
        'no_empty_phpdoc' => true,
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true],
        'no_extra_blank_lines' => true,
    ])
    ->setFinder($finder)
;