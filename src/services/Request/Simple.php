<?php

declare(strict_types=1);

namespace App\services\Request;

use App\exceptions\RequestException;
use App\interfaces\RequestInterface;
use Throwable;

class Simple implements RequestInterface {
    /**
     * @param string $endpoint
     * @return string|null
     * @throws RequestException
     */
    public function get(string $endpoint): ?string {
        try {
            return file_get_contents($endpoint);
        } catch (Throwable $exception) {
            throw new RequestException($exception->getMessage());
        }
    }
}