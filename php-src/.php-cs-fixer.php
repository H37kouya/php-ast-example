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
    'single_line_comment_style' => [
        'comment_types' => [
            // 'asterisk', PHP Doc が無効になるので、コメントアウトした
            'hash',
        ],
    ],
    'phpdoc_to_comment' => [
        'ignored_tags' => [
            'var',
        ],
    ],
];

$config = new Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder)
;
