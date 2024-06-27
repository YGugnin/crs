<?php

declare(strict_types=1);

namespace App\models;

use App\core\Model;
/**
 * @method BinNumberModel getNumber()
 * @method BinNumberModel setNumber()
 * @method string getScheme()
 * @method string setScheme()
 * @method string getType()
 * @method string setType()
 * @method string getBrand()
 * @method string setBrand()
 * @method bool getPrepaid()
 * @method bool setPrepaid()
 * @method BinCountryModel getCountry()
 * @method BinCountryModel setCountry()
 * @method BinBankModel getBank()
 * @method BinBankModel setBank()
 */
class BinModel extends Model {
    protected ?BinNumberModel $number;
    protected ?string $scheme;
    protected ?string $type;
    protected ?string $brand;
    protected ?bool $prepaid;
    protected ?BinCountryModel $country;
    protected ?BinBankModel $bank;
    
}