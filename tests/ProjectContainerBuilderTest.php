<?php

declare(strict_types=1);

use App\core\Model;
use App\core\ProjectContainerBuilder;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use PHPUnit\Framework\TestCase;

final class ProjectContainerBuilderTest extends TestCase {
    /**
     * @return void
     * @throws Exception
     */
    public function testCanBuild(): void {
        $this->assertInstanceOf(Container::class, ProjectContainerBuilder::build());
    }
    
    /**
     * @return void
     */
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        ProjectContainerBuilder::unknownCall();
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanGetVariable(): void {
        $this->assertIsArray(ProjectContainerBuilder::get('supported_sapis'));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanGetObject(): void {
        $this->assertSame(ProjectContainerBuilder::get(Model::class)::class, Model::class);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowType(): void {
        $this->expectException(TypeError::class);
       ProjectContainerBuilder::get(1);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testUnknownType(): void {
       $this->expectException(NotFoundException::class);
       ProjectContainerBuilder::get('Not exists');
    }
    
   
}