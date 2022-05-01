<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser;

use H37kouya\PhpAst\Core\Domain\PhpParser\Entities\ParseData;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\RawCode;
use PhpParser\Lexer\Emulative;
use PhpParser\ParserFactory;

/**
 * PHP の生のコードを AST に変換する UseCase.
 */
final class RawCodeToParseData
{
    private Emulative $lexer;

    public function __construct(
        private ParserFactory $parserFactory,
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

    public function __invoke(RawCode $rawCode): ParseData
    {
        $parser = $this->parserFactory->create(
            ParserFactory::PREFER_PHP7,
            $this->lexer
        );

        $stmts = $parser->parse($rawCode->get());
        $tokens = $this->lexer->getTokens();

        return new ParseData(
            stmts: $stmts,
            tokens: $tokens
        );
    }
}
