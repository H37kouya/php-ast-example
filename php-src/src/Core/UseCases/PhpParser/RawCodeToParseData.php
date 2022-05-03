<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser;

use H37kouya\PhpAst\Core\Domain\PhpParser\Entities\ParseData;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\CodeTokens;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\RawPHPCode;
use PhpParser\Lexer\Emulative;
use PhpParser\ParserFactory;

/**
 * PHP の生のコードを AST に変換する UseCase.
 */
final class RawCodeToParseData
{
    private readonly Emulative $lexer;

    public function __construct(
        private readonly ParserFactory $parserFactory,
    ) {
        $this->lexer = new Emulative([
            'usedAttributes' => [
                'comments',
                'startLine',
                'endLine',
                'startTokenPos',
                'endTokenPos',
            ],
            'phpVersion' => Emulative::PHP_8_1,
        ]);
    }

    public function __invoke(
        RawPHPCode $rawPHPCode
    ): ParseData {
        $parser = $this->parserFactory->create(
            ParserFactory::PREFER_PHP7,
            $this->lexer
        );

        $stmts = $parser->parse($rawPHPCode->get());
        $tokens = new CodeTokens($this->lexer->getTokens());

        return new ParseData(
            stmts: $stmts,
            tokens: $tokens
        );
    }
}
