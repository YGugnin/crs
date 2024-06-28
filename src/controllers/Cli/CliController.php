<?php

declare(strict_types=1);

namespace App\controllers\Cli;

use App\interfaces\OutputInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
readonly class CliController
{
    public function __construct(
        private OutputInterface $output
    )
    {
    
    }
    /**
     * @param string $class
     * @return string
     */
    public function getUsageContent(string $class) : string {
        try {
            $class = new ReflectionClass($class);
        } catch (ReflectionException) {
            return $this->output->colorize("Class not found:\n", 31);
        }
        
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $content = $this->output->colorize("Usage:\n", 35);
        
        foreach ($methods as $method) {
            if (str_starts_with($method->getName(), '__')) {
                continue;
            }
    
            if ($method->class == $class->getName()) {
                $className = strtolower(str_replace('Controller', '', $class->getShortName()));
                $methodName = strtolower(str_replace('Action', '', $method->getName()));
                $content .= $this->output->colorize('   php ./app.php' . ($className === 'index' ? '' : ' ' . $className) . ($methodName === 'index' ? '' : ' ' . $methodName), 94);
                $params = $method->getParameters();
                foreach ($params as $param) {
                    $type = (string) $param->getType();
                    if (!$type) {
                        $type = 'any';
                    }
                    if ($param->isOptional()) {
                        $content .= ' [' . $this->output->colorize($param->getName(), 93) . '(' . $type . ')';
                        try {
                            $content .= ' default: ' . $this->output->colorize(var_export($param->getDefaultValue(), true), 90);
                        } catch (ReflectionException) {
                            //do nothing
                        }
                        $content .= ']';
                    } else {
                        $content .= ' ' . $this->output->colorize($param->getName(), 93) . '(' . $type . ')';
                    }
                }
                $content .= "\n";
            }
        }
        return $content;
    }
}