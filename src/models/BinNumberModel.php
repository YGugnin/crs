<?php

declare(strict_types=1);

namespace App\models;

use App\core\Model;

/**
 * @method int getLength()
 * @method int setLength()
 * @method bool getLuhn()
 * @method bool setLuhn()
 */
class BinNumberModel extends Model {
    protected ?int $length;
    protected ?bool $luhn;
}
