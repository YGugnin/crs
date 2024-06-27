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
    public function add(string $level, Throwable $exception): void {
        $message = $level . ': ' . $exception->getMessage();
        
        $this->storage->write($this->logPath, date('Y-m-d H:i:s') . ' ' . $message . PHP_EOL);
        
        echo "\e[31m$message\e[0m" . PHP_EOL;
        echo "\e[32m{$exception->getTraceAsString()}\e[0m" . PHP_EOL;
        if (ob_get_level()) {
            ob_flush();
        }
    }
}