<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\services\EUIdentifier\EUIdentifier;
use DI\DependencyException;
use DI\NotFoundException;
use PHPUnit\Framework\TestCase;

final class EUIdentifierTest extends TestCase {
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanBeInEU(): void {
        $this->assertTrue(ProjectContainerBuilder::get(EUIdentifier::class)->isEu('LT'));
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanNotBeInEU(): void {
        $this->assertFalse(ProjectContainerBuilder::get(EUIdentifier::class)->isEu('USA'));
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnInt(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(EUIdentifier::class)->isEu(1);
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnArray(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(EUIdentifier::class)->isEu(["test", 'case']);
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnEmpty(): void {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(EUIdentifier::class)->isEu();
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        ProjectContainerBuilder::get(EUIdentifier::class)->unknownCall(1);
    }
}