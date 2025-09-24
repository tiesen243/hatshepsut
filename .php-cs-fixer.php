<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
  ->in(__DIR__)
;

return (new Config())
  ->setRules([
    '@Symfony' => true,
  ])
  ->setIndent('  ')
  ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
  ->setFinder($finder);
