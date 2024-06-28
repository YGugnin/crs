<?php

declare(strict_types=1);

namespace App\services\Logger;

use App\interfaces\FileStorageInterface;
use App\interfaces\LoggerInterface;
use Throwable;

readonly class Simple implements LoggerInterface {
    public function __construct(
        private string $logPath,
        private FileStorageInterface $storage
    ){
    
    }
    
    /**
     * @param string $level
     * @param Throwable $exception
     * @return void
     */
    public function error(string $level, Throwable $exception): void {
        $message = $level . ': ' . $exception->getMessage();
        
        $this->saveToFile(date('Y-m-d H:i:s') . ' ' . $message . PHP_EOL);
        
        $this->print($message, 31);
        $this->print($exception->getTraceAsString(), 32);
    }
    
    /**
     * @param string $level
     * @param string $message
     * @return void
     */
    public function log(string $level, string $message): void {
        $this->saveToFile(date('Y-m-d H:i:s') . ' ' . $level . ': ' . $message);
    }
    
    /**
     * @param string $content
     * @return void
     */
    private function saveToFile(string $content): void {
        $this->storage->write($this->logPath, $content . PHP_EOL);
    }
    
    /**
     * @param string $message
     * @param int $color
     * @return void
     */
    private function print(string $message, int $color): void {
        echo "\e[{$color}m{$message}\e[0m" . PHP_EOL;
        if (ob_get_level()) {
            ob_flush();
        }
    }
}
