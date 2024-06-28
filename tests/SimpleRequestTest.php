<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\exceptions\RequestException;
use App\services\Request\Simple;
use DI\DependencyException;
use DI\NotFoundException;
use PHPUnit\Framework\TestCase;

final class SimpleRequestTest extends TestCase {
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanReadUrl() {
        $this->assertIsString(ProjectContainerBuilder::get(Simple::class)->get('https://google.com'));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCantReadUrl() {
        $this->expectException(RequestException::class);
        ProjectContainerBuilder::get(Simple::class)->get('https://notgooglelink.com');
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCantRead404Url() {
        $this->expectException(RequestException::class);
        ProjectContainerBuilder::get(Simple::class)->get('https://google.com/404error');
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnInt(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->get(1);
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnArray(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->get(["test", 'case']);
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnEmpty(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->get();
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
    public function testThrowExceptionOnNotUrl(): void {
        $this->expectException(RequestException::class);
        ProjectContainerBuilder::get(Simple::class)->get('just string');
    }
}