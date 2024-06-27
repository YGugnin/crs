<?php

declare(strict_types=1);

namespace App\models;

use App\core\Model;

/**
 * @method string getNumeric()
 * @method string setNumeric()
 * @method string getAlpha2()
 * @method string setAlpha2()
 * @method string getName()
 * @method string setName()
 * @method string getEmoji()
 * @method string setEmoji()
 * @method string getCurrency()
 * @method string setCurrency()
 * @method int getLatitude()
 * @method int setLatitude()
 * @method int getLongitude()
 * @method int setLongitude()
 */
class BinCountryModel extends Model {
    protected ?string $numeric;
    //Can be empty see https://lookup.binlist.net/41417360
    protected string $alpha2 = '';
    protected ?string $name = '*UNKNOWN*';
    protected ?string $emoji;
    protected ?string $currency;
    protected ?int $latitude;
    protected ?int $longitude;
}
