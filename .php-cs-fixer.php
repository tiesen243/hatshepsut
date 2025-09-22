<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
      '@PSR12' => true,
      'array_syntax' => ['syntax' => 'short'],
      'ordered_imports' => ['sort_algorithm' => 'alpha'],
      'single_quote' => true,
      'trailing_comma_in_multiline' => true,
      'indentation_type' => true,

      'phpdoc_trim' => true,
      'phpdoc_line_span' => [
        'property' => 'single',
        'method' => 'single',
        'const' => 'single',
      ],
    ])
    ->setIndent('  ')
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setFinder($finder);
