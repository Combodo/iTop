<?php

if (PHP_SAPI !== 'cli') {
    die('This script supports command line usage only. Please check your command.');
}

return \PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            // copied from the TYPO3 Core
            '@PSR2' => true,
            '@DoctrineAnnotation' => true,
            'no_leading_import_slash' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_unused_imports' => true,
            'concat_space' => ['spacing' => 'one'],
            'no_whitespace_in_blank_line' => true,
            'ordered_imports' => true,
            'single_quote' => true,
            'no_empty_statement' => true,
            'no_extra_consecutive_blank_lines' => true,
            'phpdoc_no_package' => true,
            'phpdoc_scalar' => true,
            'no_blank_lines_after_phpdoc' => true,
            'array_syntax' => ['syntax' => 'short'],
            'whitespace_after_comma_in_array' => true,
            'function_typehint_space' => true,
            'hash_to_slash_comment' => true,
            'no_alias_functions' => true,
            'lowercase_cast' => true,
            'no_leading_namespace_whitespace' => true,
            'native_function_casing' => true,
            'no_short_bool_cast' => true,
            'no_unneeded_control_parentheses' => true,
            'phpdoc_trim' => true,
            'no_superfluous_elseif' => true,
            'no_useless_else' => true,
            'phpdoc_types' => true,
            'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
            'return_type_declaration' => ['space_before' => 'none'],
            'cast_spaces' => ['space' => 'none'],
            'declare_equal_normalize' => ['space' => 'single'],
            'dir_constant' => true,

            // additional rules
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'compact_nullable_typehint' => true,
            // PHP >= 7.0
            // 'declare_strict_types' => true,
            'elseif' => true,
            'encoding' => true,
            'escape_implicit_backslashes' => ['single_quoted' => true],
            'is_null' => true,
            'linebreak_after_opening_tag' => true,
            'magic_constant_casing' => true,
            'method_separation' => true,
            'modernize_types_casting' => true,
            // not yet, but maybe later to improve performance
            // 'native_function_invocation' => true,
            'new_with_braces' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_extra_blank_lines' => true,
            'no_multiline_whitespace_before_semicolons' => true,
            'no_php4_constructor' => true,
            'no_short_echo_tag' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_inside_parenthesis' => true,
            'no_unneeded_curly_braces' => true,
            'no_useless_return' => true,
            'no_whitespace_before_comma_in_array' => true,
            'php_unit_construct' => true,
            'php_unit_fqcn_annotation' => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'phpdoc_add_missing_param_annotation' => true,
            'phpdoc_indent' => true,
            'phpdoc_separation' => true,
            'semicolon_after_instruction' => true,
            'short_scalar_cast' => true,
            'space_after_semicolon' => true,
            'standardize_not_equals' => true,
            'psr4' => true,
            'ternary_operator_spaces' => true,
            // PHP >= 7.0
            // 'ternary_to_null_coalescing' => true,
            'trailing_comma_in_multiline_array' => true,
            'unary_operator_spaces' => true,
        ]
    );
