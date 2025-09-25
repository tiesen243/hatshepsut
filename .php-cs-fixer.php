<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
  ->in(__DIR__.'/app')
  ->in(__DIR__.'/config')
  ->in(__DIR__.'/routes')
  ->in(__DIR__.'/src')
  ->ignoreDotFiles(true);

return new Config()
  ->setRules([
    '@Symfony' => true,
    'control_structure_braces' => false,
  ])
  ->setIndent('  ')
  ->setFinder($finder);
