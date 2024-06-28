<?php

declare(strict_types=1);

namespace App\core;

use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;

class ProjectContainerBuilder {
    /**
     * @return Container
     * @throws Exception
     */
    public static function build(): Container {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->addDefinitions(require implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 2), 'src', 'config', 'app.php']));
        
        return $containerBuilder->build();
    }
    
    /**
     * @param string $key
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function get(string $key): mixed {
        return self::build()->get($key);
    }
}