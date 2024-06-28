<?php

declare(strict_types=1);

namespace App\services\Output;

use App\exceptions\RequestException;
use App\interfaces\LoggerInterface;
use App\interfaces\OutputInterface;
use App\interfaces\RequestInterface;
use Throwable;

class Simple implements OutputInterface {
    public function print(string $message, bool $flush = true): void {
        echo $message . PHP_EOL;
        if ($flush && ob_get_level()) {
            ob_flush();
        }
    }
    
    public function colorize(string $message, int $color): string {
        return "\e[{$color}m$message\e[0m";
    }
}