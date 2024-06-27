<?php

declare(strict_types=1);

namespace App\models;

use App\core\Model;

/**
 * @method bool getSuccess()
 * @method bool setSuccess()
 * @method int getTimestamp()
 * @method int setTimestamp()
 * @method string getBase()
 * @method string setBase()
 * @method string getDate()
 * @method string setDate()
 * @method array getRates()
 * @method array setRates()
 */

class ExchangeModel extends Model {
    protected ?bool $success;
    protected ?int $timestamp;
    protected ?string $base;
    protected ?string $date;
    protected ?array $rates;
}