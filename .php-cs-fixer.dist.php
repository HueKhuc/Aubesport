<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true
    ])
    ->setFinder($finder)
;
