<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Utils;

final class Path
{
    public static function basePath(string $path): string
    {
        return realpath(\dirname(__DIR__).'/../../').$path;
    }
}
