<?php

declare(strict_types=1);

use App\services\Output\Simple;
use PHPUnit\Framework\TestCase;

//Use child for tests
final class OutputTest extends TestCase {
    /**
     * @return void
     */
    public function testCanPrint(): void {
        $text = 'test';
        (new Simple())->print($text, false);
        $this->expectOutputString($text . PHP_EOL);
    }
    /**
     * @return void
     */
    public function testCanColorize(): void {
        $text = 'test';
        $color = 31;
        $this->assertEquals((new Simple())->colorize($text, $color), "\e[{$color}m$text\e[0m");
    }
    
    /**
     * @return void
     */
    public function testThrowCanPrintWrongType(): void {
        $this->expectException(TypeError::class);
        (new Simple())->print(1);
    }
    
    /**
     * @return void
     */
    public function testThrowCanColorizeWrongType(): void {
        $this->expectException(TypeError::class);
        (new Simple())->print(1, 1);
    }
    

    
}