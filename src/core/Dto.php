<?php

declare(strict_types=1);

namespace App\core;
use App\exceptions\DtoException;

class Dto {
    /**
     * @throws DtoException
     */
    public function __construct() {
        $arguments = func_get_args();
        if (count($arguments) !== 1 || !is_array($arguments[0])) {
            throw new DtoException('Arguments must be passed to Dto::__construct() as array.');
        }
        foreach ($arguments[0] as $index => $argument) {
            if (!property_exists($this, $index)) {
                throw new DtoException('Unknown property ' . $index . '.');
            }
            $this->{$index} = $argument;
        }
    }
}