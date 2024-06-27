<?php

declare(strict_types=1);

namespace App\models;

use App\core\Model;

/**
 * @method string getName()
 * @method string setName()
 * @method string getUrl()
 * @method string setUrl()
 * @method string getPhone()
 * @method string setPhone()
 * @method string getCity()
 * @method string setCity()
 */
class BinBankModel extends Model {
    protected ?string $name;
    protected ?string $url;
    protected ?string $phone;
    protected ?string $city;
}
