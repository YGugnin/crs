<?php

declare(strict_types=1);

namespace App\core;

use App\dtos\CliControllerDto;
use App\exceptions\ApplicationException;
use App\exceptions\DtoException;
    
class CliRouter {
    /**
     * @param array $arguments
     * @return CliControllerDto
     * @throws ApplicationException
     * @throws DtoException
     */
    public static function getProcessedClass(array $arguments): CliControllerDto {
        array_shift($arguments);
        $className = implode('\\', ['App', 'controllers', 'Cli', 'IndexController']);
        if (count($arguments)) {
            $classSearchName = implode('\\', ['App', 'controllers', 'Cli', $arguments[0] . 'Controller']);
            if (class_exists($classSearchName)) {
                $className = $classSearchName;
                array_shift($arguments);
            }
        }
        
        if (!class_exists($className)) {
            throw new ApplicationException('Class ' . $className . ' not exists.');
        }
        
        $functionName = 'indexAction';
        if (count($arguments)) {
            $functionSearchName = $arguments[0] . 'Action';
            if (method_exists($className, $functionSearchName)) {
                $functionName = $functionSearchName;
                array_shift($arguments);
            }
        }
        
        if (!method_exists($className, $functionName)) {
            throw new ApplicationException('Method ' . $functionName . ' in class ' . $className . ' not exists.');
        }
        
        return new CliControllerDto(['controller' => $className, 'function' => $functionName, 'arguments' => $arguments]);
    }
}