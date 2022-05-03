<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\Entities;

use H37kouya\PhpAst\Core\Domain\Base\Entities\IEntity;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\CodeTokens;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;

final class ParseData implements IEntity
{
    public function __construct(
        private readonly ?Stmts $stmts,
        private readonly CodeTokens $tokens,
    ) {
    }

    public function getStmts(): Stmts
    {
        return $this->stmts;
    }

    public function getTokens(): CodeTokens
    {
        return $this->tokens;
    }

    public function hasStmts(): bool
    {
        return null !== $this->stmts;
    }

    public function hasTokens(): bool
    {
        return null !== $this->tokens;
    }

    public function ofArray(array $arr): self
    {
        return new self(
            stmts: $arr['stmts'] ? new Stmts($arr['stmts']) : null,
            tokens: new CodeTokens($arr['tokens'] ?? []),
        );
    }

    public function toArray(): array
    {
        return [
            'stmts' => $this->getStmts(),
            'tokens' => $this->hasTokens()
                ? $this->getTokens()->get()
                : null,
        ];
    }
}
