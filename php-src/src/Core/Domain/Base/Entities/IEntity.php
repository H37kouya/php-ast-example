<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\Base\Entities;

/**
 * @template T as array
 */
interface IEntity
{
    /**
     * @param T $arr
     */
    public function ofArray(array $arr): self;

    /**
     * @return T
     */
    public function toArray(): array;
}
