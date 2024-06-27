<?php

declare(strict_types=1);

namespace App\services\FileStorage;

use App\exceptions\FileStorageException;
use App\interfaces\FileStorageInterface;
use Throwable;

class Simple implements FileStorageInterface {
    /**
     * @param string $path
     * @return string
     * @throws FileStorageException
     */
    public function get(string $path): string {
        try {
            return file_get_contents($path);
        } catch (Throwable $exception) {
            throw new FileStorageException($exception->getMessage());
        }
    }
    
    /**
     * @param string $path
     * @return bool
     * @throws FileStorageException
     */
    public function delete(string $path): bool {
        try {
            return !$this->isExists($path) || unlink($path);
        } catch (Throwable $exception) {
            throw new FileStorageException($exception->getMessage());
        }
    }
    
    /**
     * @param string $path
     * @param string $content
     * @return bool
     * @throws FileStorageException
     */
    public function save(string $path, string $content): bool
    {
        try {
            if ($this->isExists($path)) {
                $this->delete($path);
            }
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            return (bool)file_put_contents($path, $content);
        } catch (Throwable $exception) {
            throw new FileStorageException($exception->getMessage());
        }
    }
    
    /**
     * @param string $path
     * @return bool
     */
    public function isExists(string $path): bool {
        return file_exists($path);
    }
    
    /**
     * @param string $path
     * @param int $ttl
     * @return bool
     */
    public function isCachedExists(string $path, int $ttl): bool
    {
        return $this->isExists($path) && (time() - filemtime($path) < $ttl);
    }
    
    /**
     * @param string $path
     * @return array
     * @throws FileStorageException
     */
    public function getArrayContent(string $path): array {
        try {
            if (!$this->isExists($path)) {
                throw new FileStorageException('File not found: ' . $path);
            }
            return file($path, FILE_IGNORE_NEW_LINES);
        } catch (Throwable $exception) {
            throw new FileStorageException($exception->getMessage());
        }
    }
    
    /**
     * @param string $path
     * @param string $content
     * @return bool
     * @throws FileStorageException
     */
    public function write(string $path, string $content): bool {
        try {
            if (!$this->isExists($path)) {
                return $this->save($path, $content);
            }
            return (bool)file_put_contents($path, $content, FILE_APPEND);
        } catch (Throwable $exception) {
            throw new FileStorageException($exception->getMessage());
        }
    }
}