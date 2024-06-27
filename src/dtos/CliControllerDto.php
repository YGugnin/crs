<?php

declare(strict_types=1);

namespace App\dtos;
    use App\core\Dto;
    
    class CliControllerDto extends Dto {
        public string $controller;
        public string $function;
        public array  $arguments;
    }