<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\Repositories;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;

interface StmtsRepository
{
    public function save(Stmts $stmts): void;
}
