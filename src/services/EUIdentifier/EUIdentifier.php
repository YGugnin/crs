<?php

declare(strict_types=1);

namespace App\services\EUIdentifier;

readonly class EUIdentifier
{
    public function __construct(
        private array $countries
    )
    {
    
    }
    
    /**
     * @param string $countryCode
     * @return bool
     */
    public function isEU(string $countryCode): bool
    {
        return in_array($countryCode, $this->countries);
    }
}
