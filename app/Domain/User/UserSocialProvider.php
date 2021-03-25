<?php

namespace App\Domain\User;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class UserSocialProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $listen = [
        SocialiteWasCalled::class => [
            'SocialiteProviders\\VKontakte\\VKontakteExtendSocialite@handle',
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
            'SocialiteProviders\\Facebook\\FacebookExtendSocialite@handle',
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        parent::boot();
    }
}
