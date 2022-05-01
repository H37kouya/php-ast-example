<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__.'/src',
    ])
;

$rules = [
    '@PhpCsFixer:risky' => true,
    '@PhpCsFixer' => true,
    '@PSR12' => true,
    '@PHP80Migration:risky' => true,
];

$config = new Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder)
;
