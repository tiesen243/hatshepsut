<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
  ->in(__DIR__)
;

return (new Config())
  ->setRules([
    '@Symfony' => true,
    'control_structure_braces' => false,
  ])
  ->setIndent('  ')
  ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
  ->setFinder($finder);
