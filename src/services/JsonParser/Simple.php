<?php

declare(strict_types=1);

namespace App\services\JsonParser;

use App\exceptions\JsonParserException;
use App\interfaces\JsonParserInterface;
use stdClass;
use Throwable;

class Simple implements JsonParserInterface {
    /**
     * @param string $json
     * @return stdClass|null
     * @throws JsonParserException
     */
    public function parse(string $json): ?array {
        try {
            return json_decode($json, true);
        } catch (Throwable $exception) {
            throw new JsonParserException($exception->getMessage());
        }
    }
    
    /**
     * @param string[] $content
     * @return string[]
     * @throws JsonParserException
     */
    public function parseArray(array $content): array {
        return array_map(function (string $json) {
           return $this->parse($json);
        }, $content);
    }
    
}