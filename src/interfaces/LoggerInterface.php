<?php

declare(strict_types=1);

namespace App\interfaces;

use Throwable;

interface LoggerInterface {
    public function add(string $level, Throwable $exception): void;
}