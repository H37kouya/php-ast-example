<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\Entities;

use H37kouya\PhpAst\Core\Domain\Base\Entities\IEntity;
use H37kouya\PhpAst\Core\Domain\Exception\DomainValueException;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\CodeTokens;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;
use PhpParser\Node\Stmt;

final class ParseData implements IEntity
{
    /**
     * @param null|Stmts $stmts
     * @param CodeTokens $tokens
     */
    public function __construct(
        private readonly ?Stmts $stmts,
        private readonly CodeTokens $tokens,
    ) {
        if (null !== $stmts) {
            foreach ($stmts as $stmt) {
                if (!$stmt instanceof Stmt) {
                    throw new DomainValueException(
                        'stmts は PhpParser\Node\Stmt のみを持つことができます'
                    );
                }
            }
        }
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
