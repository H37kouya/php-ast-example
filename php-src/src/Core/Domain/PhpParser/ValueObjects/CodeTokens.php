<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects;

use H37kouya\PhpAst\Core\Domain\Base\ValueObjects\IValueObject;

/**
 * ファイルのフォーマットを表す.
 */
final class CodeTokens implements IValueObject
{
    /** @param mixed[] $value */
    public function __construct(
        private readonly array $value
    ) {
    }

    /**
     * @return mixed[]
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
        return implode(separator: ',', array: $this->get());
    }
}
