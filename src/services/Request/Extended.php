<?php

declare(strict_types=1);

namespace App\services\Request;

use App\exceptions\RequestException;
use App\interfaces\LoggerInterface;
use App\interfaces\RequestInterface;
use Throwable;

readonly class Extended implements RequestInterface {
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
            $curl = curl_init();
            curl_setopt ($curl, CURLOPT_URL, $endpoint);
            curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($curl, CURLOPT_HEADER, false);
            $content = curl_exec ($curl);
            $error   = curl_errno($curl);
            $message = curl_error($curl);
            $code    = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            if ($error || $code !== 200) {
                throw new RequestException('Request failed: ' . $message);
            }
            $this->logger->log('Request', 'Sent to ' . $endpoint);
            return  $content;
        } catch (Throwable $exception) {
            throw new RequestException($exception->getMessage());
        }
    }
}