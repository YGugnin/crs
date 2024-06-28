<?php

declare(strict_types=1);

namespace App\interfaces;

use Throwable;

interface LoggerInterface {
    public function error(string $level, Throwable $exception): void;
    public function log(string $level, string $message): void;
}