<?php

declare(strict_types=1);

namespace App\interfaces;

interface RequestInterface {
    public function get(string $endpoint): ?string;
}