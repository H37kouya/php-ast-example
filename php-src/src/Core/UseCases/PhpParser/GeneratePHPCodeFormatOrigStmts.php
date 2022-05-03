<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\ParsedPHPCode;
use H37kouya\PhpAst\Core\UseCases\PhpParser\Commands\GeneratePHPCodeFormatOrigStmtsCommand;
use PhpParser\PrettyPrinter\Standard;

/**
 * AST から PHP のコードを生成する UseCase.
 * OrigStmts と同じ形式 PHP コードをフォーマットする.
 */
final class GeneratePHPCodeFormatOrigStmts
{
    public function __construct(
        private readonly Standard $prettyPrinter,
    ) {
    }

    public function __invoke(
        GeneratePHPCodeFormatOrigStmtsCommand $command
    ): ParsedPHPCode {
        $code = $this->prettyPrinter->printFormatPreserving(
            stmts: $command->getStmts(),
            origStmts: $command->getOrigStmts(),
            origTokens: $command->getCodeTokens()->get(),
        );

        return new ParsedPHPCode($code);
    }
}
