<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\Base\ValueObjects;

interface IValueObject
{
    public function get(): mixed;

    public function equals(self $valueObject): bool;

    public function __toString(): string;
}
