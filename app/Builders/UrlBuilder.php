<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Support\Facades\Session;
use Spatie\Url\Url;

class UrlBuilder
{
    public function getOAuthRedirectUrl(array $queryParameters = [], array $path = []): string
    {
        $uri = Url::fromString(Session::get('oauth_redirect',''));

        foreach ($path as $fragment) {
            $uri = $uri->withPath($fragment);
        }

        foreach ($queryParameters as $queryParameter => $queryParameterValue) {
            $uri = $uri->withQueryParameter($queryParameter, $queryParameterValue);
        }

        return (string) $uri;
    }
}
