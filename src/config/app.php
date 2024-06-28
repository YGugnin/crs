<?php

declare(strict_types=1);

return array_merge([
    'supported_sapis' => ['cli', 'cli-server'],
    
    'endpoint_bin_list' => 'https://lookup.binlist.net/',
    'api_bin_list_cache_enabled' => true,
    'api_bin_list_cache_ttl' => 86400,
    
    'endpoint_exchange_rates' => 'http://api.exchangeratesapi.io/latest', //not secure, but free
    'api_exchange_rates_cache_enabled' => true,
    'api_exchange_rates_cache_ttl' => 3600,
    'api_exchange_rates_key' => 'ed56f9c775a7904c822111493c6d4c65',
    
    'api_cache_path' => dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'cache',
    
    'eu_countries_list' => ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'],
    
    'fixed_currency_list' => ['EUR'],
    
    'eu_rate_percent' => 0.01,
    'outside_eu_rate_percent' => 0.02,
    
    'logPath' => implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 2) , 'log', date('Y-m-d') . '.log']),
    
    'money_locale' => 'lt_LT',
    'default_money_locale' => 'en',
    'currency_code' => 'EUR',
    
    'display_errors_in_console' => true,
    
], require __DIR__ . DIRECTORY_SEPARATOR . 'di.php');