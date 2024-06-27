<?php

declare(strict_types=1);

namespace App\models;

use App\core\Model;

/**
 * @method int getBin()
 * @method int setBin()
 * @method int getAmount()
 * @method int setAmount()
 * @method int getCurrency()
 * @method int setCurrency()
 */
class InputRecordModel extends Model {
    protected ?int $bin;
    protected ?float $amount;
    protected ?string $currency;
}
