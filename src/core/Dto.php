<?php

declare(strict_types=1);

namespace App\core;
use App\exceptions\DtoException;

class Dto {
    /**
     * @throws DtoException
     */
    public function __construct(array $arguments) {
        foreach ($arguments as $index => $argument) {
            if (!property_exists($this, $index)) {
                throw new DtoException('Unknown property ' . $index . '.');
            }
            $this->{$index} = $argument;
        }
    }
}