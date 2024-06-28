<?php

declare(strict_types=1);

namespace App\core;

use App\exceptions\ApplicationException;
use App\exceptions\DtoException;
use App\interfaces\LoggerInterface;
use DI\Container;
use Exception;
use DI\DependencyException;
use DI\NotFoundException;
use Throwable;

final class Application {
    private static Application|null $instance = null;
    private const array REQUIRED_CONFIG_KEYS = ['supported_sapis', 'endpoint_bin_list', 'endpoint_exchange_rates'];
    private bool $initialised = false;
    private string $sapi = '';
    private Container $container;
    
    /**
     * @return Application
     */
    static public function getInstance(): Application {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * @param array $appConfig
     * @return Application
     * @throws ApplicationException
     * @throws Exception
     */
    public function init(array $appConfig): Application {
        if (count(array_intersect(array_keys($appConfig), self::REQUIRED_CONFIG_KEYS)) !== count(self::REQUIRED_CONFIG_KEYS)) {
            throw new ApplicationException('Config does\'t have required keys: ' . implode(', ', array_diff(self::REQUIRED_CONFIG_KEYS, array_keys($appConfig))));
        }
        $this->sapi = php_sapi_name();
        if (!in_array($this->sapi, $appConfig['supported_sapis'])) {
            throw new ApplicationException('Unsupported sapi ' . $this->sapi . '.');
        }
        
        $this->container = ProjectContainerBuilder::build();
        
        $this->initialised = true;
        return self::getInstance();
    }
    
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws ApplicationException
     */
    public function __call(string $name, array $arguments): mixed {
        if (!$this->initialised) {
            throw new ApplicationException('Application not initialised. Please use init() method.');
        }
        if (!method_exists($this, $name)) {
            throw new ApplicationException('Call to undefined method Application::' . $name . '.');
        }
        return call_user_func_array([$this, $name], $arguments);
    }
    
    /**
     * @param array $arguments
     * @return void
     * @throws ApplicationException
     * @throws DtoException
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function run(array $arguments): void {
        switch ($this->sapi) {
            case 'cli':
            case 'cli-server':
                $controller = CliRouter::getProcessedClass($arguments);
                try {
                    $class = $this->container->get($controller->controller);
                    $class->{$controller->function}(...$controller->arguments);
                } catch (Throwable $exception) {
                    $this->container->get(LoggerInterface::class)->add('Exception', $exception);
                }
                break;
            default:
                throw new ApplicationException('No instructions for sapi ' . $this->sapi);
        }
    }
    
    private function __construct() {}
    
    /**
     * @return void
     */
    protected function __clone(): void {}
    
    /**
     * @return mixed
     * @throws ApplicationException
     */
    public function __wakeup()
    {
        throw new ApplicationException("Cannot unserialize a singleton.");
    }
}
