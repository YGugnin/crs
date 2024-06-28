<?php

declare(strict_types=1);

use App\core\Model;
use App\exceptions\ModelException;
use App\models\BinBankModel;
use PHPUnit\Framework\TestCase;

//Use child for tests
final class ModelTest extends TestCase {
    /**
     * @throws ModelException
     */
    public function testCanBeFilled(): void {
        $this->assertInstanceOf(Model::class, new BinBankModel(['name' => 'test']));
    }
    
    /**
     * @return void
     */
    public function testCanSet(): void {
        $value = 'Some';
        $model = new BinBankModel();
        $model->setName($value);
        $this->assertEquals($value, $model->setName($value));
    }
    
    /**
     * @return void
     */
    public function testCanGetToArray(): void {
        $values = [['name' => 'Some 1'], ['name' => 'Some 2'], ['name' => 'Some 2']];
        
        $model = new BinBankModel();
        $result = $model->toArray($values);
        $this->assertIsArray($result);
        $this->assertEquals($values[1]['name'], $result[1]->getName());
    }
    
    /**
     * @return void
     * @throws ModelException
     */
    public function testThrowCanBeFilled(): void {
        $this->expectException(ModelException::class);
        new BinBankModel(['field' => 'test']);
    }
    
    /**
     * @return void
     */
    public function testCanSetWrongType(): void {
        $value = 1;
        $model = new BinBankModel();
        $model->setName(1);
        $this->assertTrue($value == $model->getName());
    }
    
    /**
     * @return void
     */
    public function testThrowSet(): void {
        $this->expectException(ModelException::class);
        $model = new BinBankModel();
        $model->setField(1);
    }
    /**
     * @return void
     */
    public function testThrowCanGetToArray(): void {
        $this->expectException(ModelException::class);
        
        $values = [['field 1' => 'Some 1'], ['field2' => 'Some 2'], ['' => 'Some 2']];
        $model = new BinBankModel();
        $model->toArray($values);
    }
}