<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects;

use H37kouya\PhpAst\Core\Domain\Base\ValueObjects\IValueObject;
use H37kouya\PhpAst\Core\Domain\Exception\DomainValueException;
use PhpParser\Node\Stmt;

/**
 * AST.
 */
final class Stmts implements IValueObject
{
    /**
     * @param Stmt[] $value
     */
    public function __construct(
        private readonly array $value
    ) {
        foreach ($value as $stmt) {
            if (!$stmt instanceof Stmt) {
                throw new DomainValueException(
                    'value は PhpParser\Node\Stmt のみを持つことができます'
                );
            }
        }
    }

    /**
     * @return Stmt[] $value
     */
    public function get(): array
    {
        return $this->value;
    }

    public function equals(IValueObject $vo): bool
    {
        return $this->get() === $vo->get();
    }

    public function __toString(): string
    {
        $json = json_encode($this->get());

        if (false === $json) {
            throw new DomainValueException('Stmts を json に変換できませんでした.');
        }

        return $json;
    }
}
