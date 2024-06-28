<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\exceptions\FileStorageException;
use App\services\FileStorage\Simple;
use DI\DependencyException;
use DI\NotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

final class SimpleFileStorageTest extends TestCase {
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
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        ProjectContainerBuilder::get(Simple::class)->unknownCall(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanGet(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.get';
        $content = 'some content';
        ProjectContainerBuilder::get(Simple::class)->save($fileName, $content);
        ProjectContainerBuilder::get(Simple::class)->get($fileName);
        $this->assertEquals($content, ProjectContainerBuilder::get(Simple::class)->get($fileName, $content));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanSave(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.save';
        ProjectContainerBuilder::get(Simple::class)->save($fileName, 'someContent');
        $this->assertFileExists($fileName);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanDelete(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.delete';
        ProjectContainerBuilder::get(Simple::class)->save($fileName, 'something');
        ProjectContainerBuilder::get(Simple::class)->delete($fileName);
        $this->assertTrue(ProjectContainerBuilder::get(Simple::class)->delete($fileName));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testIsExists(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.exists';
        ProjectContainerBuilder::get(Simple::class)->save($fileName, 'something');
        $this->assertTrue(ProjectContainerBuilder::get(Simple::class)->isExists($fileName));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testIsNotExists(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.not.exists';
        $this->assertFalse(ProjectContainerBuilder::get(Simple::class)->isExists($fileName));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testIsCachedExists(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.cached.exists';
        ProjectContainerBuilder::get(Simple::class)->save($fileName, 'something');
        $this->assertTrue(ProjectContainerBuilder::get(Simple::class)->isCachedExists($fileName, 100));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testIsCachedNotExists(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.cached.not.exists';
        ProjectContainerBuilder::get(Simple::class)->save($fileName, 'something');
        $this->assertFalse(ProjectContainerBuilder::get(Simple::class)->isCachedExists($fileName, -1));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanGetArrayContent(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.array';
        $array = ['some content line 1', 'some content line 2', 'some content line 3'];
        ProjectContainerBuilder::get(Simple::class)->save($fileName, implode(PHP_EOL, $array));
        $this->assertEquals($array, ProjectContainerBuilder::get(Simple::class)->getArrayContent($fileName));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanGetArrayContentEmptyFile(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.array.empty';
        ProjectContainerBuilder::get(Simple::class)->save($fileName, '');
        $this->assertEquals([], ProjectContainerBuilder::get(Simple::class)->getArrayContent($fileName));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanWrite(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.write';
        $line1 = 'Line 1';
        $line2 = 'Line 2';
        ProjectContainerBuilder::get(Simple::class)->write($fileName, $line1);
        ProjectContainerBuilder::get(Simple::class)->write($fileName, $line2);
        $this->assertEquals($line1 . $line2, ProjectContainerBuilder::get(Simple::class)->get($fileName));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowGet(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.get.not.exists';
        $this->expectException(FileStorageException::class);
        ProjectContainerBuilder::get(Simple::class)->get($fileName);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowSave(): void {
        $fileName = 'https://someurl.com';
        $this->expectException(FileStorageException::class);
        ProjectContainerBuilder::get(Simple::class)->save($fileName, '');
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowGetArrayContent(): void {
        $fileName = $this->virtualDirectory->url() . DIRECTORY_SEPARATOR . 'someDir' . DIRECTORY_SEPARATOR . md5((string)time()) . '.example.get.array.not.exists';
        $this->expectException(FileStorageException::class);
        ProjectContainerBuilder::get(Simple::class)->getArrayContent($fileName);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowWrite(): void {
        $fileName = 'https://someurl.com';
        $this->expectException(FileStorageException::class);
        ProjectContainerBuilder::get(Simple::class)->write($fileName, '');
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowGetWrongType(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->get(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowDeleteWrongType(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->delete(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowSaveWrongType(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->save(1, 1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowIsExistsWrongType(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->isExists(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowIsCachedExistsWrongType(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->isCachedExists(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowGetArrayContentWrongType(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->getArrayContent(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowWriteWrongType(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->write(1, 1);
    }
    
}