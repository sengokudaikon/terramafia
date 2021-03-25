<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_KEY'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('AUTH_URL') . '/login/google/callback')
    ],

    'facebook' => [
        'client_id' => env('FB_KEY'),
        'client_secret' => env('FB_SECRET'),
        'redirect' => env('FB_REDIRECT_URI', env('AUTH_URL') . '/api/login/facebook/callback'),
        'bind_redirect' => env('FB_BIND_REDIRECT_URI', env('AUTH_URL') . '/api/account/facebook/callback'),
    ],

    'vkontakte' => [
        'client_id' => env('VK_KEY'),
        'client_secret' => env('VK_SECRET'),
        'redirect' => env('VK_REDIRECT_URI', env('AUTH_URL') . '/api/login/vkontakte/callback'),
        'bind_redirect' => env('VK_BIND_REDIRECT_URI', env('AUTH_URL') . '/api/login/vkontakte/callback'),
    ],

];
