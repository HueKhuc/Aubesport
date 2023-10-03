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
        'strict_param' => true,
        'class_attributes_separation' => true,
        'no_unused_imports' => true,
        'blank_line_before_statement' => true,
        'single_space_after_construct' => true
    ])
    ->setFinder($finder)
;
