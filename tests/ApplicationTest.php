<?php

declare(strict_types=1);

use App\core\Application;
use App\exceptions\ApplicationException;
use App\exceptions\DtoException;
use DI\DependencyException;
use DI\NotFoundException;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase {
    /**
     * @return void
     */
    public function testThrowAccessToUndefined(): void {
        $this->expectException(Error::class);
        Application::$variable;
    }
    /**
     * @return void
     */
    public function testThrowAccessToPrivateVariable(): void {
        $this->expectException(Error::class);
        Application::$initialised;
    }
    
    /**
     * @return void
     */
    public function testCanGetInstance(): void {
        $this->assertInstanceOf(Application::class, Application::getInstance());
    }
    
    /**
     * @return void
     * @throws ApplicationException
     */
    public function testCanInit(): void {
        $this->assertInstanceOf(Application::class, Application::getInstance()->init(require implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'src', 'config', 'app.php'])));
    }
    
    /**
     * @return void
     * @throws ApplicationException
     */
    public function testThrowCanInitWithBadConfig(): void {
        $this->expectException(ApplicationException::class);
        $this->assertInstanceOf(Application::class, Application::getInstance()->init([]));
    }
    
    /**
     * @return void
     */
    public function testThrowCallUndefined(): void {
        $this->expectException(ApplicationException::class);
        $this->assertInstanceOf(Application::class, Application::getInstance()->func());
    }
    
    /**
     * @return void
     */
    public function testThrowConstruct(): void {
        $this->expectException(Error::class);
        new Application;
    }
    
    /**
     * @return void
     */
    public function testThrowClone(): void {
        $this->expectException(Error::class);
        clone Application::getInstance();
    }
    
    /**
     * @return void
     */
    public function testThrowSerialize(): void {
        $this->expectException(ApplicationException::class);
        unserialize(serialize(Application::getInstance()));
    }
    
   
    
   
    
   
    
}