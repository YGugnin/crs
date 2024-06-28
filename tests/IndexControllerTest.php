<?php

declare(strict_types=1);

use App\controllers\Cli\IndexController;
use App\exceptions\FileStorageException;
use App\interfaces\FileStorageInterface;
use App\interfaces\JsonParserInterface;
use App\interfaces\LoggerInterface;
use App\interfaces\OutputInterface;
use App\services\Api\BinListApi;
use App\services\Api\ExchangeApi;
use DI\DependencyException;
use DI\NotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use App\core\ProjectContainerBuilder;
use App\services\EUIdentifier\EUIdentifier;

//Use child for tests
final class IndexControllerTest extends TestCase {
    /**
     * @var vfsStreamDirectory
     */
    private vfsStreamDirectory $virtualDirectory;
    /**
     * @return void
     */
    public function setUp(): void {
        $this->virtualDirectory = vfsStream::setup('virtual');
    }
    
    /**
     * @return string
     */
    private function getFileContent(): string {
        return implode(PHP_EOL, ['{"bin":"45717360","amount":"100.00","currency":"EUR"}',
            '{"bin":"516793","amount":"50.00","currency":"USD"}',
            '{"bin":"45417360","amount":"10000.00","currency":"JPY"}']);
    }
    private function getMockOutput(): OutputInterface {
        return $this->getMockBuilder(OutputInterface::class)
                ->getMock();
    }
    private function getMockLogger(): LoggerInterface {
        return $this->getMockBuilder(LoggerInterface::class)
                ->getMock();
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanHelp(): void {
        $controller = new IndexController(
            ProjectContainerBuilder::get('fixed_currency_list'),
            ProjectContainerBuilder::get('eu_rate_percent'),
            ProjectContainerBuilder::get('outside_eu_rate_percent'),
            ProjectContainerBuilder::get('money_locale'),
            ProjectContainerBuilder::get('default_money_locale'),
            ProjectContainerBuilder::get('currency_code'),
            $this->getMockLogger(),
            ProjectContainerBuilder::get(FileStorageInterface::class),
            ProjectContainerBuilder::get(JsonParserInterface::class),
            ProjectContainerBuilder::get(BinListApi::class),
            ProjectContainerBuilder::get(ExchangeApi::class),
            ProjectContainerBuilder::get(EUIdentifier::class),
            $this->getMockOutput()
        );
        $this->assertNull($controller->helpAction());
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanCalculate(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'text.txt';
        ProjectContainerBuilder::get(FileStorageInterface::class)->save($fileName, $this->getFileContent());
        
        $controller = $api = new IndexController(
            ProjectContainerBuilder::get('fixed_currency_list'),
            ProjectContainerBuilder::get('eu_rate_percent'),
            ProjectContainerBuilder::get('outside_eu_rate_percent'),
            ProjectContainerBuilder::get('money_locale'),
            ProjectContainerBuilder::get('currency_code'),
            $this->getMockLogger(),
            ProjectContainerBuilder::get(FileStorageInterface::class),
            ProjectContainerBuilder::get(JsonParserInterface::class),
            ProjectContainerBuilder::get(BinListApi::class),
            ProjectContainerBuilder::get(ExchangeApi::class),
            ProjectContainerBuilder::get(EUIdentifier::class),
            $this->getMockOutput()
        );
        $this->assertNull($controller->indexAction($fileName));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowOnFileNOtExists(): void {
        $this->expectException(FileStorageException::class);
        
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'not_exists.txt';
        
        $controller = new IndexController(
            ProjectContainerBuilder::get('fixed_currency_list'),
            ProjectContainerBuilder::get('eu_rate_percent'),
            ProjectContainerBuilder::get('outside_eu_rate_percent'),
            ProjectContainerBuilder::get('money_locale'),
            ProjectContainerBuilder::get('currency_code'),
            $this->getMockLogger(),
            ProjectContainerBuilder::get(FileStorageInterface::class),
            ProjectContainerBuilder::get(JsonParserInterface::class),
            ProjectContainerBuilder::get(BinListApi::class),
            ProjectContainerBuilder::get(ExchangeApi::class),
            ProjectContainerBuilder::get(EUIdentifier::class),
            $this->getMockOutput()
        );
        $this->assertNull($controller->indexAction($fileName));
    }
}