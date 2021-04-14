<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Service\User\Social\SocialAccount;
use App\Service\User\Social\VkontakteSocialAccount;
use Geecko\Socialite\GeeckoUser;
use Illuminate\Support\Str;

class UserRepository
{
    public function createFromOAuthProfile(VkontakteSocialAccount $oAuthUser): User
    {
        $user = new User(
            [
                'first_name' => $oAuthUser->first_name,
                'last_name' => $oAuthUser->last_name,
                'username' => $oAuthUser->getNickname(),
                'email' => $oAuthUser->getEmail(),
                'photo' => $oAuthUser->getAvatar(),

                'primary_programming_language' => $oAuthUser->primary_programming_language,
                'experience' => $oAuthUser->experience,
                'specializations' => $oAuthUser->specializations,

                'country_code' => $oAuthUser->country_code,
                'city' => $oAuthUser->city,
            ]
        );

        $user->geecko_user_id = $oAuthUser->getId();
        $user->access_token = $oAuthUser->token;
        $user->refresh_token = $oAuthUser->refreshToken;
        $user->expires_in = $oAuthUser->expiresIn;

        $user->save();

        return $user;
    }

    public function generateHash(): string
    {
        return Str::random();
    }

    public function existsWithEmail(string $email): bool
    {
        return User::query()->where('email', $email)->exists();
    }

    public function existsWithVkUserId(int $vkUserId): bool
    {
        return User::query()->where('vk_user_id', $vkUserId)->exists();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findByVkUserId(int $vkUserId): ?User
    {
        return User::query()->where('vk_user_id', $vkUserId)->first();
    }

    public function markEmailAsVerified(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->markEmailAsVerified();
    }
}
