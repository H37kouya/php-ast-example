<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Console\Commands;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\RawPHPCode;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;
use H37kouya\PhpAst\Core\UseCases\PhpParser\CloneStmts;
use H37kouya\PhpAst\Core\UseCases\PhpParser\Commands\GeneratePHPCodeFormatOrigStmtsCommand;
use H37kouya\PhpAst\Core\UseCases\PhpParser\GeneratePHPCodeFormatOrigStmts;
use H37kouya\PhpAst\Core\UseCases\PhpParser\RawCodeToParseData;
use H37kouya\PhpAst\Core\UseCases\PhpParser\Visitor\ChangeClassNameVisitor;
use H37kouya\PhpAst\Core\Utils\Path;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TestPracticeCommand extends Command
{
    private readonly RawCodeToParseData $rawCodeToParseData;

    private readonly CloneStmts $cloneStmts;

    private readonly GeneratePHPCodeFormatOrigStmts $generatePHPCodeFormatOrigStmts;

    public function __construct()
    {
        $this->rawCodeToParseData = new RawCodeToParseData(
            new ParserFactory()
        );

        $this->generatePHPCodeFormatOrigStmts = new GeneratePHPCodeFormatOrigStmts(
            new Standard()
        );

        $this->cloneStmts = new CloneStmts(
            new NodeTraverser()
        );

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('test-practice')
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        // PHP ファイルの取得
        $fp = file_get_contents(
            Path::basePath('/sample/ddd/Domain/ValueObjects/UserId.php')
        );
        if (false === $fp) {
            throw new RuntimeException('ファイルの取得に失敗しました');
        }

        // ファイルから生のコードを AST に変換
        $rawPHPCode = new RawPHPCode($fp);
        $parseData = $this->rawCodeToParseData->__invoke($rawPHPCode);

        // AST のコピー
        $newStmts = $this->cloneStmts->__invoke($parseData->getStmts());

        // AST の修正
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ChangeClassNameVisitor('UserIdCopy'));
        $newStmts = new Stmts(
            $traverser->traverse($newStmts->get())
        );

        // AST から PHP コードを生成
        $parsedPHPCode = $this->generatePHPCodeFormatOrigStmts->__invoke(
            new GeneratePHPCodeFormatOrigStmtsCommand(
                stmts: $newStmts,
                origStmts: $parseData->getStmts(),
                codeTokens: $parseData->getTokens(),
            )
        );

        // PHP ファイルへの書き込み
        $newPath = Path::basePath('/sample/ddd/Domain/ValueObjects/UserIdCopy.php');
        if (false === file_put_contents($newPath, $parsedPHPCode->get())) {
            throw new RuntimeException('ファイルの書き込みに失敗しました');
        }

        return self::SUCCESS;
    }
}
