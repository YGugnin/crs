<?php

declare(strict_types=1);

use App\core\CliRouter;
use App\dtos\CliControllerDto;
use App\exceptions\ApplicationException;
use App\exceptions\DtoException;
use PHPUnit\Framework\TestCase;

final class CliRouterTest extends TestCase {
    
    /**
     * @return void
     * @throws DtoException
     * @throws ApplicationException
     */
    public function testCanGetRoute(): void {
        $this->assertInstanceOf(CliControllerDto::class, (new CliRouter())->getProcessedClass(['script', 'files/input.txt', '1']));
    }
    
    /**
     * @return void
     * @throws DtoException
     * @throws ApplicationException
     */
    public function testThrowGetRouteEmpty(): void {
        $this->expectException(ArgumentCountError::class);
        (new CliRouter())->getProcessedClass();
    }
    
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     * @throws DtoException
     */
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        (new CliRouter())->unknownCall();
    }
    
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    //can't throw exception because all requests use index if not found
    
   
    
}