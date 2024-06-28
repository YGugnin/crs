<?php

declare(strict_types=1);

use App\core\Dto;
use App\dtos\CliControllerDto;
use App\exceptions\DtoException;
use PHPUnit\Framework\TestCase;

//Use child for tests
final class DtoTest extends TestCase {
    
    /**
     * @return void
     * @throws DtoException
     */
    public function testDtoCanBeFilled(): void {
        $this->assertInstanceOf(Dto::class, new CliControllerDto(['controller' => 'test']));
    }
    
    /**
     * @return void
     */
    public function testDtoCanNotBeFilled(): void {
        $this->expectException(DtoException::class);
        $this->assertInstanceOf(Dto::class, new CliControllerDto(['controllers' => 'test']));
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     * @throws DtoException
     */
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        (new CliControllerDto(['controller' => 'test']))->unknownCall(1);
    }
    
    /**
     * @return void
     * @throws DtoException
     */
    public function testDtoCanBeFilledThrowType(): void {
        $this->expectException(TypeError::class);
        $this->assertInstanceOf(Dto::class, new CliControllerDto(['controller' => 1]));
    }
    
}