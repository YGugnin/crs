<?php

declare(strict_types=1);

namespace App\interfaces;

interface BinModelInterface {
    public function getAlpha2(): ?string;
    public function getCountryName(): ?string;
}