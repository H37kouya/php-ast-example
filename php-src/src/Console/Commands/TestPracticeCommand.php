<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Console\Commands;

use DateTime;
use H37kouya\PhpAst\Core\Domain\PhpParser\Aggregates\StmtsAggregate;
use H37kouya\PhpAst\Core\Domain\PhpParser\Repositories\PHPCodeRepository;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\ClassName;
use H37kouya\PhpAst\Core\Infra\PhpParser\RepositoryImpl\FpPHPCodeRepositoryImpl;
use H37kouya\PhpAst\Core\Infra\PhpParser\RepositoryImpl\FpStmtsRepositoryImpl;
use H37kouya\PhpAst\Core\UseCases\PhpParser\ChangeClassNameStmts;
use H37kouya\PhpAst\Core\UseCases\PhpParser\Commands\GeneratePHPCodeFormatOrigStmtsCommand;
use H37kouya\PhpAst\Core\UseCases\PhpParser\GeneratePHPCodeFormatOrigStmts;
use H37kouya\PhpAst\Core\UseCases\PhpParser\RawCodeToParseData;
use H37kouya\PhpAst\Core\UseCases\PhpParser\StoreStmts;
use H37kouya\PhpAst\Core\Utils\Path;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TestPracticeCommand extends Command
{
    private readonly RawCodeToParseData $rawCodeToParseData;

    private readonly ChangeClassNameStmts $changeClassNameStmts;

    private readonly GeneratePHPCodeFormatOrigStmts $generatePHPCodeFormatOrigStmts;

    private readonly StoreStmts $storeStmtsToJsonFile;

    private readonly PHPCodeRepository $origFpPHPCodeRepository;

    private readonly PHPCodeRepository $newFpPHPCodeRepository;

    public function __construct()
    {
        $now = new DateTime();

        $this->rawCodeToParseData = new RawCodeToParseData(
            new ParserFactory()
        );

        $this->generatePHPCodeFormatOrigStmts = new GeneratePHPCodeFormatOrigStmts(
            new Standard()
        );

        $this->changeClassNameStmts = new ChangeClassNameStmts(
            new StmtsAggregate()
        );

        $this->storeStmtsToJsonFile = new StoreStmts(
            new FpStmtsRepositoryImpl(
                Path::basePath(
                    "/storage/php/json/UserIdCopyAST_{$now->format('YmdHis')}.json"
                )
            )
        );

        $this->origFpPHPCodeRepository = new FpPHPCodeRepositoryImpl(
            Path::basePath(
                '/sample/ddd/Domain/ValueObjects/UserId.php'
            )
        );

        $this->newFpPHPCodeRepository = new FpPHPCodeRepositoryImpl(
            Path::basePath(
                '/sample/ddd/Domain/ValueObjects/UserIdCopy.php'
            )
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
        $rawPHPCode = $this->origFpPHPCodeRepository->get()->toRawPHPCode();
        // ファイルから生のコードを AST に変換
        $parseData = $this->rawCodeToParseData->__invoke($rawPHPCode);

        $stmts = $parseData->getStmts();
        if (null === $stmts) {
            throw new RuntimeException('AST が取得できませんでした');
        }

        // AST の変更 (クラス名の変更)
        $newStmts = $this->changeClassNameStmts->__invoke(
            $stmts,
            new ClassName('UserIdCopy')
        );

        // Json の保存
        $this->storeStmtsToJsonFile->__invoke(
            stmts: $newStmts,
        );

        // AST から PHP コードを生成
        $parsedPHPCode = $this->generatePHPCodeFormatOrigStmts->__invoke(
            new GeneratePHPCodeFormatOrigStmtsCommand(
                stmts: $newStmts,
                origStmts: $stmts,
                codeTokens: $parseData->getTokens(),
            )
        );

        // PHP ファイルへの書き込み
        $this->newFpPHPCodeRepository->store(
            phpCode: $parsedPHPCode
        );

        return self::SUCCESS;
    }
}
