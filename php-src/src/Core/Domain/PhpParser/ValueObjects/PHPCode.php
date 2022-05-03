<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects;

use H37kouya\PhpAst\Core\Domain\Base\ValueObjects\IValueObject;

/**
 * PHP のコード.
 */
class PHPCode implements IValueObject
{
    public function __construct(
        private readonly string $value
    ) {
    }

    public function get(): string
    {
        return $this->value;
    }

    public function equals(IValueObject $vo): bool
    {
        return $this->get() === $vo->get();
    }

    public function __toString(): string
    {
        return (string) $this->get();
    }
}
