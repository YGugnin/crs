<?php

declare(strict_types=1);

namespace App\interfaces;
interface FileStorageInterface {
    public function isExists(string $path): bool;
    public function getArrayContent(string $path): array;
    public function get(string $path): string;
    public function isCachedExists(string $path, int $ttl): bool;
    public function save(string $path, string $content): bool;
    public function delete(string $path): bool;
    public function write(string $path, string $content): bool;
}