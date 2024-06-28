<?php

declare(strict_types=1);

namespace App\services\Api;

use App\core\Api;
use App\interfaces\FileStorageInterface;
use App\interfaces\RequestInterface;

readonly class ExchangeApi extends Api {
    protected const string CACHE_PREFIX = 'ex_';
    
    public function __construct(
        private string $endpoint,
        private bool $cacheEnabled,
        private int $cacheTtl,
        private string $key,
        private string $cachePath,
        private RequestInterface $request,
        private FileStorageInterface $storage
    )
    {
        parent::__construct(
            $this->cacheEnabled,
            $this->cacheTtl,
            $this->cachePath,
            self::CACHE_PREFIX,
            $this->request,
            $this->storage
        );
    }
    
    /**
     * @return string|null
     */
    public function getRates(): ?string {
        // 250 requests per month http because it's free
        return $this->get(trim($this->endpoint, '/') . '?access_key=' . $this->key);
    }
    
    /**
     * @return void
     */
    public function removeCache(): void {
        $this->storage->delete($this->getFileName(trim($this->endpoint, '/') . '?access_key=' . $this->key));
    }
}