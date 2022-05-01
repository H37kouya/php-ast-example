<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Console\Commands;

use H37kouya\PhpAst\Core\Utils\Path;
use PhpParser\Lexer\Emulative;
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

        // PHP ファイルの解析
        // $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        // try {
        //     $ast = $parser->parse($fp);
        // } catch (Error $error) {
        //     echo "Parse error: {$error->getMessage()}\n";

        //     return;
        // }

        // AST の表示
        // $dumper = new NodeDumper();
        // echo $dumper->dump($ast)."\n";

        $lexer = new Emulative([
            'usedAttributes' => [
                'comments',
                'startLine',
                'endLine',
                'startTokenPos',
                'endTokenPos',
            ],
            'phpVersion' => Emulative::PHP_8_1,
        ]);
        $parser = (new ParserFactory())->create(
            ParserFactory::PREFER_PHP7,
            $lexer
        );

        $oldStmts = $parser->parse($fp);
        $oldTokens = $lexer->getTokens();

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
