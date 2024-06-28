<?php

declare(strict_types=1);

namespace App\controllers\Cli;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
readonly class CliController
{
    /**
     * @param string $class
     * @return string
     */
    public function getUsageContent(string $class) : string {
        try {
            $class = new ReflectionClass($class);
        } catch (ReflectionException) {
            return $this->colorized("Class not found:\n", 31);
        }
        
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $content = $this->colorized("Usage:\n", 35);
        
        foreach ($methods as $method) {
            if (str_starts_with($method->getName(), '__')) {
                continue;
            }
    
            if ($method->class == $class->getName()) {
                $className = strtolower(str_replace('Controller', '', $class->getShortName()));
                $methodName = strtolower(str_replace('Action', '', $method->getName()));
                $content .= $this->colorized('   php ./app.php' . ($className === 'index' ? '' : ' ' . $className) . ($methodName === 'index' ? '' : ' ' . $methodName), 94);
                $params = $method->getParameters();
                foreach ($params as $param) {
                    $type = (string) $param->getType();
                    if (!$type) {
                        $type = 'any';
                    }
                    if ($param->isOptional()) {
                        $content .= ' [' . $this->colorized($param->getName(), 93) . '(' . $type . ')';
                        try {
                            $content .= ' default: ' . $this->colorized(var_export($param->getDefaultValue(), true), 90);
                        } catch (ReflectionException) {
                            //do nothing
                        }
                        $content .= ']';
                    } else {
                        $content .= ' ' . $this->colorized($param->getName(), 93) . '(' . $type . ')';
                    }
                }
                $content .= "\n";
            }
        }
        return $content;
    }
    
    /**
     * @param string $text
     * @return void
     */
    public function stdout(string $text): void{
        echo $text . "\n";
        if (ob_get_level()) {
            ob_flush();
        }
    }
    
    /**
     * @param string $message
     * @param int $color
     * @return string
     */
    public function colorized(string $message, int $color = 0): string {
        return "\e[{$color}m$message\e[0m";
    }
}