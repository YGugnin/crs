<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\exceptions\JsonParserException;
use App\services\JsonParser\Simple;
use DI\DependencyException;
use DI\NotFoundException;
use PHPUnit\Framework\TestCase;

final class SimpleJsonParserTest extends TestCase {
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
    public function testWrongTypesOnParse() {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->parse(true);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testWrongTypesOnParseArray() {
        $this->expectException(TypeError::class);
        ProjectContainerBuilder::get(Simple::class)->parse(true);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testWrongJsonOnParseArray() {
        $this->expectException(JsonParserException::class);
        ProjectContainerBuilder::get(Simple::class)->parseArray(['true', 1, true, false]);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testWrongJsonOnParse() {
        $this->expectException(JsonParserException::class);
        ProjectContainerBuilder::get(Simple::class)->parse('Invalid JSON');
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanParseArray() {
        $this->assertIsArray(ProjectContainerBuilder::get(Simple::class)->parseArray(['{"valid":"json"}', '{"value": 1, "correct": true}']));
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanParse() {
        $this->assertIsArray(ProjectContainerBuilder::get(Simple::class)->parse('[{"valid":"json"},{"value": 1, "correct": true}]'));
    }
}