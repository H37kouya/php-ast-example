<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\Base\Entities;

interface IEntity
{
    public function ofArray(array $arr): self;

    public function toArray(): array;
}
