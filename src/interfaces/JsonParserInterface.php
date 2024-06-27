<?php

declare(strict_types=1);

namespace App\interfaces;

interface JsonParserInterface {
    public function parse(string $json): ?array;
    public function parseArray(array $content): array;
}