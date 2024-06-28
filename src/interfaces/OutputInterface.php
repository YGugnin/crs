<?php

declare(strict_types=1);

namespace App\interfaces;

interface OutputInterface {
    public function print(string $message): void;
    public function colorize(string $message, int $color): string;
}
