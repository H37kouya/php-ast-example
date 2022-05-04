<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Infra\PhpParser\Visitor;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\ClassName;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitorAbstract;

/**
 * クラス名を変更する Visitor.
 */
final class ChangeClassNameVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly ClassName $newClassName
    ) {
    }

    public function enterNode(Node $node): null|int|Node
    {
        // クラス名の変更
        if ($node instanceof Class_) {
            $node->name = new Identifier($this->newClassName->get());
        }

        return null;
    }
}
