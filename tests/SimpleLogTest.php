<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\interfaces\FileStorageInterface;
use App\interfaces\LoggerInterface;
use App\interfaces\OutputInterface;
use App\services\Logger\Simple;
use DI\DependencyException;
use DI\NotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

final class SimpleLogTest extends TestCase {
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
     * @return OutputInterface
     */
    private function getMockOutput(): OutputInterface {
        return $this->getMockBuilder(OutputInterface::class)
            ->getMock();
    }
    
    private function getLogger(): LoggerInterface {
        return new Simple(
            false,
            $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'logs',
            ProjectContainerBuilder::get(FileStorageInterface::class),
            $this->getMockOutput()
        );
    }
    
    /**
     * @return void
     */
    public function testWrongTypesOnErrorLog() {
        $this->expectException(TypeError::class);
        $this->getLogger()->error(true, [1,4]);
    }
    
    /**
     * @return void
     */
    public function testWrongTypesOnLogLog() {
        $this->expectException(TypeError::class);
        $this->getLogger()->log(true, [1,4]);
    }
    
    /**
     * @return void
     */
    public function testCanAddLogOnErrorLog() {
        $error = new Exception("Test case exception");
        $this->assertNull($this->getLogger()->error('Unit test', $error));
    }
    
    /**
     * @return void
     */
    public function testCanAddLogOnLogLog() {
        $this->assertNull($this->getLogger()->log('Unit test', 'Some log'));
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        $this->getLogger()->unknownCall(1);
    }
}