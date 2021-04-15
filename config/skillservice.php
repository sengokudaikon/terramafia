<?php

return [
    'api_url' => env('SKILLSERVICE_URL', 'https://skills.geecko.ru/'),
    'api_key' => env('SKILLSERVICE_KEY'),
    'x_company_id' => env('SKILLSERVICE_X_COMPANY_ID'),
    'prefix' => env('SKILLSERVICE_ROUTE_PREFIX', ''),
];
