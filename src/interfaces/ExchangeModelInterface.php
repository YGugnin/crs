<?php

declare(strict_types=1);

namespace App\interfaces;

interface ExchangeModelInterface {
    public function getSuccess(): bool;
    public function getRates(): array;
}