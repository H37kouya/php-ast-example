<?php

declare(strict_types=1);

namespace H37kouya\SampleDDD\Domain\ValueObjects;

interface IValueObject
{
    public function get();

    public function equals(self $valueObject): bool;

    public function __toString(): string;
}
