<?php

declare(strict_types=1);

use App\interfaces\FileStorageInterface;
use App\interfaces\JsonParserInterface;
use App\interfaces\RequestInterface;
use App\interfaces\LoggerInterface;
use App\services\Api\BinListApi;
use App\services\Api\ExchangeApi;
use App\services\FileStorage\Simple as FileReaderSimple;
use App\services\JsonParser\Simple as JsonParserSimple;
//use App\services\Request\Simple as RequestSimple;
use App\services\Request\Extended as RequestExtended;
use App\services\Logger\Simple as LoggerSimple;
use App\controllers\Cli\IndexController;
use App\services\EUIdentifier\EUIdentifier;

return [
    FileStorageInterface::class => DI\autowire(FileReaderSimple::class),
    JsonParserInterface::class => DI\autowire(JsonParserSimple::class),
    //RequestInterface::class => DI\autowire(RequestSimple::class),
    RequestInterface::class => DI\autowire(RequestExtended::class),
    LoggerInterface::class => DI\autowire(LoggerSimple::class)->constructor(
        DI\get('logPath'),
    ),
    BinListApi::class => DI\autowire(BinListApi::class)->constructor(
        DI\get('endpoint_bin_list'),
        DI\get('api_bin_list_cache_enabled'),
        DI\get('api_bin_list_cache_ttl'),
        DI\get('api_cache_path'),
    ),
    ExchangeApi::class => DI\autowire(ExchangeApi::class)->constructor(
        DI\get('endpoint_exchange_rates'),
        DI\get('api_exchange_rates_cache_enabled'),
        DI\get('api_exchange_rates_cache_ttl'),
        DI\get('api_exchange_rates_key'),
        DI\get('api_cache_path'),
    ),
    EUIdentifier::class => DI\autowire(EUIdentifier::class)->constructor(
        DI\get('eu_countries_list'),
    ),
    IndexController::class => DI\autowire(IndexController::class)->constructor(
        DI\get('fixed_currency_list'),
        DI\get('eu_rate_percent'),
        DI\get('outside_eu_rate_percent'),
        DI\get('money_locale'),
        DI\get('currency_code'),
    ),
];