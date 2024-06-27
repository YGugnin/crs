<?php

declare(strict_types=1);

namespace App\services\Request;

use App\exceptions\RequestException;
use App\interfaces\RequestInterface;
use Throwable;

class Extended implements RequestInterface {
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
            curl_close($curl);
            
            if ($error) {
                throw new RequestException('Request failed: ' . $message);
            }
            return  $content;
        } catch (Throwable $exception) {
            throw new RequestException($exception->getMessage());
        }
    }
}