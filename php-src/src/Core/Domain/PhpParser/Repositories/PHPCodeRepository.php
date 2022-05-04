<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\Repositories;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\PHPCode;

interface PHPCodeRepository
{
    public function get(): PHPCode;

    public function store(PHPCode $phpCode): void;
}
