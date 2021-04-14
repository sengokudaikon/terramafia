<?php

declare(strict_types=1);

namespace App\Enums;

use Tailflow\Enum\Enum;

class OAuthLoginResult extends Enum
{
    public const CreatedSuccessfully = 'success_created';
    public const LoggedInSuccessfully = 'success_logged_in';
    public const UnableToRetrieveUserDetails = 'err_user_details';
    public const UnableToCreateUser = 'err_user_create';
}
