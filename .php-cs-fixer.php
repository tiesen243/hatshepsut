<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
      '@PSR12' => true,
      'array_syntax' => ['syntax' => 'short'],
      'indentation_type' => true,
      'no_trailing_whitespace' => true,
      'ordered_imports' => ['sort_algorithm' => 'alpha'],
      'phpdoc_line_span' => [
        'property' => 'single',
        'method' => 'single',
        'const' => 'single',
      ],
      'phpdoc_trim' => true,
      'single_quote' => true,
      'trailing_comma_in_multiline' => true,
    ])
    ->setIndent('  ')
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setFinder($finder);
