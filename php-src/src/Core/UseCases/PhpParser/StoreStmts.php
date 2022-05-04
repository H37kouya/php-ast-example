<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser;

use H37kouya\PhpAst\Core\Domain\PhpParser\Repositories\StmtsRepository;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;

final class StoreStmts
{
    public function __construct(
        private readonly StmtsRepository $stmtsRepository,
    ) {
    }

    public function __invoke(
        Stmts $stmts,
    ): void {
        $this->stmtsRepository->save($stmts);
    }
}
