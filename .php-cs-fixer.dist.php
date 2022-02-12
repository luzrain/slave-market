<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'align_multiline_comment' => true,
        'cast_spaces' => true,
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'no_empty_comment' => true,
        'no_alternative_syntax' => true,
        'no_unused_imports' => true,
        'binary_operator_spaces' => true,
        'unary_operator_spaces' => true,
        'standardize_not_equals' => true,
        'linebreak_after_opening_tag' => true,
        'phpdoc_scalar' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        'phpdoc_var_annotation_correct_order' => true,
        'semicolon_after_instruction' => true,
        'no_empty_statement' => true,
        'no_spaces_around_offset' => true,
    ])
    ->setFinder($finder)
;
