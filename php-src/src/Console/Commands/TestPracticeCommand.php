<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Console\Commands;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\RawCode;
use H37kouya\PhpAst\Core\UseCases\PhpParser\RawCodeToParseData;
use H37kouya\PhpAst\Core\Utils\Path;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TestPracticeCommand extends Command
{
    private RawCodeToParseData $rawCodeToParseData;

    public function __construct()
    {
        $this->rawCodeToParseData = new RawCodeToParseData(
            new ParserFactory()
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
        // 標準出力
        $output->writeln('Hello World!');

        // PHP ファイルの取得
        $output->writeln(
            Path::basePath('/sample/ddd/Domain/ValueObjects/UserId.php')
        );
        $fp = file_get_contents(
            Path::basePath('/sample/ddd/Domain/ValueObjects/UserId.php')
        );
        if (false === $fp) {
            throw new RuntimeException('ファイルの取得に失敗しました');
        }

        // ファイルから生のコードを AST に変換
        $rawCode = new RawCode($fp);
        $parseData = $this->rawCodeToParseData->__invoke($rawCode);
        $oldStmts = $parseData->hasStmts() ? $parseData->getStmts() : null;
        $oldTokens = $parseData->hasTokens() ? $parseData->getTokens() : [];

        // AST の修正
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());
        $newStmts = $traverser->traverse($oldStmts);

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new class () extends NodeVisitorAbstract {
            public function enterNode(Node $node): void
            {
                // クラス名の変更
                if ($node instanceof Class_) {
                    $node->name = 'UserIdCopy';
                }
            }
        });
        $newStmts = $traverser->traverse($newStmts);
        $printer = new Standard();
        $result = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);

        // PHP ファイルへの書き込み
        $newPath = Path::basePath('/sample/ddd/Domain/ValueObjects/UserIdCopy.php');
        if (false === file_put_contents($newPath, $result)) {
            throw new RuntimeException('ファイルの書き込みに失敗しました');
        }

        return self::SUCCESS;
    }
}
