<?php

declare(strict_types=1);

namespace App\services\Request;

use App\exceptions\RequestException;
use App\interfaces\LoggerInterface;
use App\interfaces\RequestInterface;
use Throwable;

readonly class Simple implements RequestInterface {
    public function __construct(
        private LoggerInterface $logger
    )
    {
    
    }
    /**
     * @param string $endpoint
     * @return string|null
     * @throws RequestException
     */
    public function get(string $endpoint): ?string {
        try {
            $content = file_get_contents($endpoint);
            if ($content) {
                $this->logger->log('Request', 'Sent to ' . $endpoint);
            }
            return file_get_contents($endpoint);
        } catch (Throwable $exception) {
            throw new RequestException($exception->getMessage());
        }
    }
}