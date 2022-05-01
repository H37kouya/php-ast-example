<?php

declare(strict_types=1);

namespace H37kouya\SampleDDD\Domain\ValueObjects;

final class UserId implements IValueObject
{
    public function __construct(
        private readonly int $value
    ) {
    }

    public function get(): int
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
