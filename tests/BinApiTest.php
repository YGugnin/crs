<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\exceptions\RequestException;
use App\interfaces\FileStorageInterface;
use App\interfaces\LoggerInterface;
use App\services\Api\BinListApi;
use App\services\Request\Simple;
use DI\DependencyException;
use DI\NotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

final class BinApiTest extends TestCase {
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
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanGetBin(): void {
        $mockJSON = '{"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"рџ‡©рџ‡°","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}';
        
        $mock = $this->getMockBuilder(Simple::class)
            ->setConstructorArgs([ProjectContainerBuilder::get(LoggerInterface::class)])
            ->getMock();
        $mock->method('get')->willReturn($mockJSON);
        $api = new BinListApi(
                'unused-url',
                ProjectContainerBuilder::get('api_bin_list_cache_enabled'),
                ProjectContainerBuilder::get('api_bin_list_cache_ttl'),
                $this->virtualDirectory->url(),
                $mock,
                ProjectContainerBuilder::get(FileStorageInterface::class)
            );
        $this->assertEquals($api->getBin(1), $mockJSON);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowBinBadRequest(): void {
        $this->expectException(RequestException::class);
        $api = new BinListApi(
            'WrongUrl',
            ProjectContainerBuilder::get('api_exchange_rates_cache_enabled'),
            ProjectContainerBuilder::get('api_exchange_rates_cache_ttl'),
            ProjectContainerBuilder::get('api_cache_path'),
            ProjectContainerBuilder::get(Simple::class),
            ProjectContainerBuilder::get(FileStorageInterface::class)
        );
        $api->getBin(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanRemoveCache(): void {
        $this->assertNull(ProjectContainerBuilder::get(BinListApi::class)->removeCache(1));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        ProjectContainerBuilder::get(BinListApi::class)->unknownCall(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowBadTypeOnBin(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(BinListApi::class)->getBin([]);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowBadTypeOnCacheRemove(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(BinListApi::class)->removeCache([]);
    }
}