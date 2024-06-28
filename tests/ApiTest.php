<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\exceptions\RequestException;
use App\interfaces\FileStorageInterface;
use App\services\Api\BinListApi;
use App\services\Request\Simple;
use DI\DependencyException;
use DI\NotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

//use child
final class ApiTest extends TestCase {
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
    public function testCanGet(): void {
        $url = 'https://google.com';
        $api = new BinListApi(
                $url,
                ProjectContainerBuilder::get('api_bin_list_cache_enabled'),
                ProjectContainerBuilder::get('api_bin_list_cache_ttl'),
                $this->virtualDirectory->url(),
                ProjectContainerBuilder::get(Simple::class),
                ProjectContainerBuilder::get(FileStorageInterface::class)
            );
        $this->assertIsString($api->get($url));
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCacheCreatedGet(): void {
        $url = 'https://google.com';
        $api = new BinListApi(
            $url,
                true,
                ProjectContainerBuilder::get('api_bin_list_cache_ttl'),
                $this->virtualDirectory->url(),
                ProjectContainerBuilder::get(Simple::class),
                ProjectContainerBuilder::get(FileStorageInterface::class)
            );
        $fileName = $api->getFileName($url);
        $api->get($url);
        $this->assertTrue(ProjectContainerBuilder::get(FileStorageInterface::class)->isExists($fileName));
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCacheNotCreatedGet(): void {
        $url = 'https://google.com?notExists';
        $api = new BinListApi(
            $url,
                false,
                ProjectContainerBuilder::get('api_bin_list_cache_ttl'),
                $this->virtualDirectory->url(),
                ProjectContainerBuilder::get(Simple::class),
                ProjectContainerBuilder::get(FileStorageInterface::class)
            );
        $fileName = $api->getFileName($url);
        $api->get($url);
        $this->assertFalse(ProjectContainerBuilder::get(FileStorageInterface::class)->isExists($fileName));
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testWrongUrl(): void {
        $this->expectException(RequestException::class);
        $url = 'https://not-exist-url.com?notExists';
        $api = new BinListApi(
            $url,
                false,
                ProjectContainerBuilder::get('api_bin_list_cache_ttl'),
                $this->virtualDirectory->url(),
                ProjectContainerBuilder::get(Simple::class),
                ProjectContainerBuilder::get(FileStorageInterface::class)
            );
        $api->get($url);
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testNotUrl(): void {
        $this->expectException(RequestException::class);
        $url = 'some string';
        $api = new BinListApi(
            $url,
                false,
                ProjectContainerBuilder::get('api_bin_list_cache_ttl'),
                $this->virtualDirectory->url(),
                ProjectContainerBuilder::get(Simple::class),
                ProjectContainerBuilder::get(FileStorageInterface::class)
            );
        $api->get($url);
    }
}