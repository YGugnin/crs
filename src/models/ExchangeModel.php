<?php

declare(strict_types=1);

namespace App\models;

use App\core\Model;
use App\interfaces\ExchangeModelInterface;

/**
 * @method int getTimestamp()
 * @method int setTimestamp()
 * @method string getBase()
 * @method string setBase()
 * @method string getDate()
 * @method string setDate()
 */

class ExchangeModel extends Model implements ExchangeModelInterface {
    protected ?bool $success = false;
    protected ?int $timestamp;
    protected ?string $base;
    protected ?string $date;
    protected ?array $rates = [];
    
    /**
     * @return bool
     */
    public function getSuccess(): bool {
        return parent::getSuccess();
    }
    
    /**
     * @return array
     */
    public function getRates(): array {
        return parent::getRates();
    }
}
