<?php

declare(strict_types=1);

namespace App\core;

use App\interfaces\FileStorageInterface;
use App\interfaces\RequestInterface;

class Api {
    public function __construct(
        private readonly bool $cacheEnabled,
        private readonly int $cacheTtl,
        private readonly string $cachePath,
        private readonly string $cachePrefix,
        private readonly RequestInterface $request,
        private readonly FileStorageInterface $storage
    ) {
    
    }
    
    /**
     * @param string $url
     * @return string|null
     */
    public function get(string $url): ?string {
        if ($this->cacheEnabled) {
            $filePath = $this->getFileName($url);
            if ($this->storage->isCachedExists($filePath, $this->cacheTtl)) {
                return $this->storage->get($filePath);
            } else {
                $content = $this->request->get($url);
                $this->storage->save($filePath, $content);
            }
        } else {
            $content = $this->request->get($url);
        }
        
        return $content;
    }
    
    /**
     * @param string $url
     * @return string
     */
    protected function getFileName(string $url): string {
        return $this->cachePath . DIRECTORY_SEPARATOR . $this->cachePrefix . md5($url) . '.cache';
    }
}
