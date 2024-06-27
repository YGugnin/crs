<?php

declare(strict_types=1);

namespace App\services\Api;

use App\core\Api;
use App\interfaces\FileStorageInterface;
use App\interfaces\RequestInterface;

class BinListApi extends Api {
    protected const string CACHE_PREFIX = 'bl_';
    
    public function __construct(
        private readonly string $apiEndpoint,
        private readonly bool $apiCacheEnabled,
        private readonly int $apiCacheTtl,
        private readonly string $apiCachePath,
        private readonly RequestInterface $request,
        private readonly FileStorageInterface $storage
    )
    {
        parent::__construct(
            $this->apiCacheEnabled,
            $this->apiCacheTtl,
            $this->apiCachePath,
            self::CACHE_PREFIX,
            $this->request,
            $this->storage
        );
    }
    public function getBin(int $bin): ?string {
        // You have exceeded the rate limit of 5 requests/hour. Please wait a bit and try again.
        return $this->get(trim($this->apiEndpoint, '/') . '/' . $bin);
    }
}