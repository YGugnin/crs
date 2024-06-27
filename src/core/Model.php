<?php

declare(strict_types=1);

namespace App\core;

use App\exceptions\ModelException;
use ReflectionProperty;
use Throwable;

class Model {
    private const array SCALAR_TYPES = ['boolean', 'bool', 'integer', 'int', 'float', 'double', 'string', 'array', 'object', 'null'];
    /**
     * @param array $params
     * @throws ModelException
     */
    public function __construct(array $params = []) {
        if ($params) {
            foreach ($params as $name => $value) {
                $this->set($name, $value);
            }
        }
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws ModelException
     */
    private function set(string $name, mixed $value): mixed{
        try {
            $property = new ReflectionProperty(get_class($this), $name);
            $type = $property->getType()->getName();
            if (in_array($type, self::SCALAR_TYPES)) {
                settype($value, $type);
            } else {
                if (class_exists($type)) {
                    $value = new $type($value ?? []);
                } else {
                    throw new ModelException('Unknown model type: ' . $type);
                }
            }
            
            $this->$name = $value;
            return $this->$name;
        } catch (Throwable $exception) {
            throw new ModelException($exception->getMessage());
        }
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    private function get(string $name): mixed{
        return $this->$name;
    }
    
    /**
     * @param array $data
     * @return array
     */
    public function toArray(array $data): array {
        return array_map(function (array $row) {
            $class = get_class($this);
            return new $class($row);
        }, $data);
    }
    
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed|null
     * @throws ModelException
     */
    public function __call(string $name, array $arguments): mixed {
        return match (substr($name, 0, 3)) {
            'set' => $this->set(strtolower(substr($name, 3)), ...$arguments),
            'get' => $this->get(strtolower(substr($name, 3))),
            default => $this->$name(...$arguments)
        };
    }
    
}
