<?php

namespace App\Helpers;

class SecurityHashHelper
{
    public static function generateRandomString(int $length = 32): string
    {
        $bytes = random_bytes($length);

        return substr(base64_encode($bytes), 0, $length);
    }

    public static function generateRandomStringWithoutSpecialChars(int $length = 32): string
    {
        $bytes = random_bytes($length);

        return substr(md5($bytes), 0, $length);
    }

    public static function generatePasswordHash(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    public static function validatePassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
